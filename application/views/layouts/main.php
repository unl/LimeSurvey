<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <?php 
        
        /* @var $cs CClientScript */
        $cs=Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile(Yii::app()->getConfig('third_party') . 'jqueryui/js/jquery-ui-1.10.0.custom.js');
        $cs->registerScriptFile(Yii::app()->getConfig('generalscripts') . 'jquery/jquery.ui.touch-punch.min.js');
        $cs->registerScriptFile(Yii::app()->getConfig('generalscripts') . 'jquery/jquery.qtip.js');
        $cs->registerScriptFile(Yii::app()->getConfig('generalscripts') . 'jquery/jquery.notify.js');
        $cs->registerScriptFile(Yii::app()->createUrl('config/script'));
        $cs->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'admin_core.js');
        ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('third_party');?>jqueryui/css/smoothness/jquery-ui-1.10.0.custom.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('adminstyleurl');?>/jquery-ui/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('adminstyleurl');?>printablestyle.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('adminstyleurl');?>adminstyle.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('styleurl');?>adminstyle.css" />
        <?php
        if(!empty($css_admin_includes)) {
            foreach ($css_admin_includes as $cssinclude)
            {
                ?>
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo $cssinclude; ?>" />
                <?php
            }
        }
        /*if ($bIsRTL){?>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getConfig('adminstyleurl');?>adminstyle-rtl.css" /><?php
        }*/
        ?>
                <link rel="shortcut icon" href="<?php echo App()->baseUrl; ?>styles/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="<?php echo App()->baseUrl; ?>styles/favicon.ico" type="image/x-icon" />
        <?php //echo $firebug ?>

        
        <title>Limesurvey Administration</title>
    </head>
    <body>
        <?php $this->widget('application.extensions.FlashMessage.FlashMessage'); ?>
        <?php $this->widget('application.extensions.Menu.MenuWidget', $this->navData); ?>
        
        <?php echo $content; ?>
    </body>

</html>
