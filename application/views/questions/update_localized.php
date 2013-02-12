<div id="localized-<?php echo $language; ?>" class="localized tabs">
    <ul>
        <li><a href="#basic"><span><?php echo gT('Basic settings'); ?></span></a></li>
        <li><a href="#advanced"><span><?php echo gT('Advanced settings'); ?></span></a></li>
    </ul>
    <div id="basic">
        <ul>
        <?php
            foreach ($attributes as $name => $setting)
            {
                if ($setting['localized'] && !$setting['advanced'])
                {
                    $setting['language'] = $language;
                    echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true)); 
                }
            }
        ?>
        </ul>

    </div>
    <div id="advanced" class="">
        <ul>
        <?php
            foreach ($attributes as $name => $setting)
            {
                if ($setting['localized'] && $setting['advanced'])
                {
                    $setting['language'] = $language;
                    echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true)); 
                }
            }
        ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $( "#nonlocalized").tabs();//"option", "collapsible", true );
    });
        
    
</script>