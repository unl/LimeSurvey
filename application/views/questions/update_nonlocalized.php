<div id="nonlocalized" class="nonlocalized tabs">
    <ul>
        <li><a href="#basic"><span><?php echo gT('Basic settings'); ?></span></a></li>
        <li><a href="#advanced"><span><?php echo gT('Advanced settings'); ?></span></a></li>
    </ul>
    <div id="basic" class="settings">
        <ul>
            <?php
               
               foreach ($attributes as $name => $setting)
               {
                   if (!$setting['localized'] && !$setting['advanced'])
                   {
                    echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true)); 
                   }
               }
            ?>
           
        </ul>
    </div>
    <div id="advanced" class="settings">
        <ul>
        <?php
        foreach ($attributes as $name => $setting)
        {
            if (!$setting['localized'] && $setting['advanced'])
            {
                echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true));
            }
        }
        ?>
        </ul>
    </div>
</div>
