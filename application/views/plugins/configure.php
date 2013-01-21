

<h1>Settings for plugin "<?php echo $plugin['name']; ?>".</h1>
<div class="pluginsettings">
<?php

    // @var PluginsController Description
    $this;
    
    echo CHtml::beginForm('', 'post', array('id' => 'pluginsettings'));
    
    foreach ($settings as $name => $setting)
    {
        $this->PluginSettings->renderSetting($name, $setting, 'pluginsettings');
    }
    echo CHtml::submitButton('Save plugin settings');
    echo CHtml::endForm();
    
?>

</div>