<div id="plugin-<?php echo $plugin['name']; ?>">
    <h1>Settings for plugin "<?php echo $plugin['name']; ?>".</h1>
    <div class="pluginsettings">
    <?php

        Yii::import("application.helpers.PluginSettingsHelper");
        $PluginSettings = new PluginSettingsHelper();
        
        echo CHtml::beginForm('', 'post', array('id' => "pluginsettings-{$plugin['name']}"));

        foreach ($settings as $name => $setting)
        {
            $PluginSettings->renderSetting($name, $setting, "pluginsettings-{$plugin['name']}");
        }
        echo CHtml::submitButton('Save plugin settings');
        echo CHtml::endForm();

    ?>

    </div>
</div>