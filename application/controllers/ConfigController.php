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
    
    public function actionMap($id)
    {
        $array = array();
        while (count($array) < 1000)
        {
            $array[] = mt_rand();
        }
        
        $reps = 0;
        srand(123);
        $first = $array;
        shuffle($first);
            
        $result = array();
        $start = microtime(true);
        $correct = 0;
        while ($reps < 10000)
        {
            srand(123);
            $array2 = $array;
            shuffle($array2);
            
            if ($array2 == $first)
            {
                $correct++;
            }
            unset($array2);
            $reps++;
        }
        debug("Correct: $correct");
        $end = microtime(true);
        $time = $end - $start;
        debug("Time: $time seconds");
        debug($result);
        App()->loadHelper('survey');
        $em = new ExpressionManager();
        $expr = 'Q1.NAOK - Q2.NAOK';
        debug($em->RDP_Evaluate($expr));
        debug($em->GetErrors());
        debug($em->GetJavaScriptEquivalentOfExpression());
        debug($em->GetAllJsVarsUsed());
        //debug(createFieldMap($id, true, false, 'en'));
    }
    
    public function GetVarAttribute($name, $attr, $default)
    {
        
        
        debug('GetVarAttribute');
        debug(func_get_args());
        if ($attr == 'jsName') 
        {
            return $attr;
        }
        elseif ($attr == NULL)
        {
            return 'null';
        }
        elseif ($attr == 'varName')
        {
            return 'hippe shit';
        }
        return $default;
    }
            
   
}