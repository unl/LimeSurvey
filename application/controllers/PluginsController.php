<?php
    
    class PluginsController extends LSYii_Controller 
    {
        
        public function actionIndex()
        {
            // Scan the plugins folder.
            $discoveredPlugins = App()->getPluginManager()->scanPlugins();
            $plugins = Plugin::model()->findAll();
            $discoveredNames = array_keys($discoveredPlugins);
            $pluginNames = array_map(function(Plugin $plugin) { return $plugin->attributes['name']; }, $plugins);
            
            $allNames = array_unique(array_merge($pluginNames, $discoveredNames));
            foreach ($allNames as $pluginName)
            {
                $data[$pluginName]['installed'] = in_array($pluginName, $pluginNames);
                $data[$pluginName]['discovered'] = in_array($pluginName, $pluginNames);
                $data[$pluginName]['new'] = !$data[$pluginName]['installed'];
                $data[$pluginName]['missing'] = !$data[$pluginName]['discovered'];
            }
            // Register plugins that are not registered yet.
            
            Yii::import('application.extensions.*');
            $dataProvider = new CActiveDataProvider(Plugin::model(), array(
            'criteria' => array(
            'order' => 'active DESC, name ASC'
            ),
            'pagination' => array(
            'pageSize' => 20)));

            $gridColumns = array(
                'name', // display the 'title' attribute
                'active', // display the 'name' attribute of the 'category' relation
                array(            // display a column with "view", "update" and "delete" buttons
                    'class' => 'CallbackColumn',
                    'label' => function($data) { return ($data->active == 1) ? "deactivate": "activate"; },
                    'url' => function($data) { return array("/config/toggle", "id"=>$data["id"]); }
                )
            );
                
            echo $this->render('/plugins/index', compact('discoveredPlugins', 'registeredPlugins', 'dataProvider', 'gridColumns'));
        }
        
         public function actionActivate($id)
        {
            $plugin = Plugin::model()->findByPk($id);
            if (!is_null($plugin)) {
                $status = $plugin->active;
                if ($status == 1) {
                    $result = App()->getPluginManager()->dispatchEvent(new PluginEvent('beforeDeactivate', $this), $plugin->plugin);
                    if ($result->get('success', true)) {
                        $status = 0;
                    } else {
                        echo "Failed to deactivate";
                        Yii::app()->end();
                    }

                } else {
                    $result = App()->getPluginManager()->dispatchEvent(new PluginEvent('beforeActivate', $this), $plugin->plugin);
                    if ($result->get('success', true)) {
                        $status = 1;
                    } else {
                        echo "Failed to activate";
                        Yii::app()->end();
                    }
                }
                $plugin->active = $status;
                $plugin->save();
            }
            $this->redirect(array('/plugin/'));
        }


    }
?>
