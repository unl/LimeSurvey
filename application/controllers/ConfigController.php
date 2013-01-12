<?php

    class ConfigController extends LSYii_Controller
    {
        
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
        
        public function actionTest()
        {
            App()->loadHelper('twig');
          
            $twig = Twig::getTwigEnvironment();
            
            $nav = array(
                'previous' => true,
                'next' => true,
                'clearall' => true
            );
            $twig->display('copy_of_default/navigator.twig', array('navigator' => $nav));
            
        }
        public function beforeRender($view)
        {
            return parent::beforeRender($view);
        }
       
    }

?>
