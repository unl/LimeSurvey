<?php
    Yii::import("application.helpers.PluginSettingsHelper");
    $PluginSettings = new PluginSettingsHelper();
    echo CHtml::openTag('ol');
    foreach ($settings as $name => $setting)
    {
        echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, "frmeditquestion", true));

    }
    echo CHtml::closeTag('ol');

?>
