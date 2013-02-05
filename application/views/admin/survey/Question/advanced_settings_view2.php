<?php
    // This view is used for advanced, settings.

    Yii::import("application.helpers.PluginSettingsHelper");
    $PluginSettings = new PluginSettingsHelper();
    echo CHtml::openTag('ol');
    if (!isset($localized))
    {
        $localized = false;
    }
    foreach ($settings as $name => $metaData)
    {
        if (isset($language))
        {
            $metaData['language'] = $language;
        }
        if ($metaData['localized'] == $localized)
        {
            echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $metaData, "frmeditquestion", true));
        }

    }
    echo CHtml::closeTag('ol');

?>
