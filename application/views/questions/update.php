<?php 
    $form = 'updateform';
    Yii::import('application.helpers.PluginSettingsHelper');
    $PluginSettings = new PluginSettingsHelper();
    
    // Render basic and advanced non localized settings.
    $this->renderPartial('/questions/update_nonlocalized', compact('question', 'survey', 'groups', 'questions', 'questiontypes', 'form', 'attributes', 'PluginSettings'));
    
?>
<div id="localized" class="tabs">
    <ul>
        <?php
            // Create tab headers.
            foreach ($languages as $language)
            {
                echo CHtml::tag('li', array(), CHtml::link(getLanguageNameFromCode($language, false), '#localized-' . $language));
                
                
            }
        ?>
    </ul>
    <?php
        // Render  basic and advanced localized settings for each language.
        foreach ($languages as $language)
        {
            $this->renderPartial('/questions/update_localized', compact('question', 'survey', 'language', 'attributes', 'form', 'PluginSettings'));
        }
    ?>
</div>

<?php
    echo CHtml::beginForm();
    echo CHtml::submitButton(gT('Save'));
    echo CHtml::endForm();
    
?>
    
    