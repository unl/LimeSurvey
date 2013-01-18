<?php

class ConfigController extends LSYii_Controller {

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }

    public function init()
    {
        parent::init();
        $this->_sessioncontrol();
        Yii::app()->setConfig('adminimageurl', Yii::app()->getConfig('styleurl') . Yii::app()->getConfig('admintheme') . '/images/');
        Yii::app()->setConfig('adminstyleurl', Yii::app()->getConfig('styleurl') . Yii::app()->getConfig('admintheme') . '/');
    }

    /**
     * Load and set session vars
     * (copied from admincontroller)
     *
     * @access protected
     * @return void
     */
    protected function _sessioncontrol()
    {
        Yii::import('application.libraries.Limesurvey_lang');
        // From personal settings
        if (Yii::app()->request->getPost('action') == 'savepersonalsettings')
        {
            if (Yii::app()->request->getPost('lang') == 'auto')
            {
                $sLanguage = getBrowserLanguage();
            } else
            {
                $sLanguage                       = Yii::app()->request->getPost('lang');
            }
            Yii::app()->session['adminlang'] = $sLanguage;
        }

        if (empty(Yii::app()->session['adminlang']))
            Yii::app()->session["adminlang"] = Yii::app()->getConfig("defaultlang");

        Yii::app()->setLang(new Limesurvey_lang(Yii::app()->session['adminlang']));

        if (!empty($this->user_id))
            $this->_GetSessionUserRights($this->user_id);
    }

    public function actionScript()
    {
        // Retrieve config options that should be available in JS.
        $configOptions = array(
            //    'DBVersion'
            'adminimageurl'
        );
        $data = array();

        foreach ($configOptions as $option) {
            $data[$option]         = Yii::app()->getConfig($option);
        }
        $data['baseUrl']       = Yii::app()->getBaseUrl(true);
        $data['layoutPath']    = Yii::app()->getLayoutPath();
        $data['adminImageUrl'] = Yii::app()->getConfig('adminimageurl');

        $this->layout = false;
        $this->render('/js', compact('data'));
    }

    public function beforeRender($view)
    {
        return parent::beforeRender($view);
    }

    public function actionPlugins()
    {
        // Scan the plugins folder.
        $discoveredPlugins = App()->getPluginManager()->scanPlugins();

        // Register plugins that are not registered yet.
        $registeredPlugins = Plugins::model()->findAll();

        $dataProvider = new CActiveDataProvider(Plugins::model(), array(
        'criteria' => array(
        'order' => 'active DESC, plugin ASC'
        ),
        'pagination' => array(
        'pageSize' => 20)));

        $gridColumns = array(
        'plugin', // display the 'title' attribute
        'active', // display the 'name' attribute of the 'category' relation
        array(            // display a column with "view", "update" and "delete" buttons
        'class' => 'CLinkColumn',
        'labelExpression'=>'$data->active == 1 ? "deactivate" :  "activate"',
        'urlExpression'=>'Yii::app()->createUrl("/config/toggle", array("id"=>$data["id"]))',
        ),
        );
        echo $this->render('/config/plugins', compact('discoveredPlugins', 'registeredPlugins', 'dataProvider', 'gridColumns'));
    }

    public function actionToggle($id)
    {
        $plugin = Plugins::model()->findByPk($id);
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
        $this->forward('/config/plugins');
    }

}