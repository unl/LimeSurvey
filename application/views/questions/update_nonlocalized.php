<div id="nonlocalized" class="nonlocalized tabs">
    <ul>
        <li><a href="#basic"><span><?php echo gT('Basic settings'); ?></span></a></li>
        <li><a href="#advanced"><span><?php echo gT('Advanced settings'); ?></span></a></li>
    </ul>
    <div id="basic">
        <ul>
            <?php
               $basicSettings = array(
                   'questiontypes' => array(
                       'type' => 'select',
                       'label' => gt('Question type:'),
                       'options' => CHtml::listData($questiontypes, 'guid', 'name'),
                       'current' => $question['questiontype'] 
                   ),
                   'code' => array(
                       'type' => 'string',
                       'label' => gT('Question code:'),
                       'current' => $question['code']
                   ),
                   'gid' => array(
                       'type' => 'select',
                       'label' => gT('Question group:'),
                       'current' => $question['gid'],
                       'options' => $groups
                   ),
                   'relevance' => array(
                       'type' => 'relevance',
                       'label' => gT('Relevance equation:'),
                       'current' => $question['relevance']
                   ),
                   /*
                   'position' => array(
                       'type' => 'select',
                       'label' => gT("Position:"),
                       'current' => 'last',
                       'options' => $questions
                   )
                    * 
                    */
               );

               foreach ($basicSettings as $name => $setting)
               {
                   echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true)); 
               }
            ?>
           
        </ul>
    </div>
    <div id="advanced" class="">
        <ul>
        <?php
        foreach ($attributes as $name => $setting)
        {
            if (!$setting['localized'])
            {
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