<?php

    class ConfigController extends LSYii_Controller
    {
        
        public function __construct($id, $module = null) 
        {
            parent::__construct($id, $module);
            Yii::import('application.libraries.Limesurvey_lang');
            Yii::app()->setLang( new Limesurvey_lang(Yii::app()->getConfig("defaultlang")));
        }

        public function actionScript()
        {
            // Retrieve config options that should be available in JS.
            $configOptions = array(
            //    'DBVersion'
                'adminimageurl'
            );
            $data = array();

            foreach ($configOptions as $option)
            {
                $data[$option] = Yii::app()->getConfig($option);
            }
            $data['baseUrl'] = Yii::app()->getBaseUrl(true);
            $data['layoutPath'] = Yii::app()->getLayoutPath();
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
            $plugins = App()->getPluginManager()->scanPlugins();
            echo $this->render('/config/plugins', compact('plugins'));
        }
        
        
       
    }

?>
