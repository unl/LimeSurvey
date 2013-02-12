<?php

class ConfigController extends LSYii_Controller {

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
        $data['showScriptName'] = Yii::app()->urlManager->showScriptName;
        $data['urlFormat'] = Yii::app()->urlManager->urlFormat;
        $data['layoutPath']    = Yii::app()->getLayoutPath();
        $data['adminImageUrl'] = Yii::app()->getConfig('adminimageurl');
        $data['replacementFields']['path'] = $this->createUrl("admin/limereplacementfields/sa/index/");
        $this->layout = false;
        $this->render('/config/script', compact('data'));
    }
   
}