<?php 
    App()->getClientScript()->registerScriptFile('scripts/questions/update.js');
    App()->getClientScript()->registerScriptFile('http://www.keyframesandcode.com/resources/javascript/jQuery/populate/jquery.populate.js');
    $form = 'updateform';
    Yii::import('application.helpers.PluginSettingsHelper');
    $PluginSettings = new PluginSettingsHelper();
    
    // Render basic and advanced non localized settings.
    $this->renderPartial('/questions/update_nonlocalized', compact('basicSettings', 'question', 'survey', 'groups', 'questions', 'questiontypes', 'form', 'attributes', 'PluginSettings'));
    
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
    echo CHtml::beginForm('', 'post', array('id' => $form));
    echo CHtml::submitButton(gT('Save'));
    echo CHtml::endForm();
    
?>
<script type="text/javascript">
    $(document).ready(function() {
        $( ".tabs").tabs();
    });
</script>