<div id="nonlocalized" class="nonlocalized tabs">
<ul>
    <?php
        echo CHtml::tag('li', array('id' => 'basic_tabheader'), CHtml::link($clang->gT("Basic settings"), '#basic'));
        echo CHtml::tag('li', array('id' => 'advanced_tabheader'), CHtml::link($clang->gT("Advanced settings"), '#advanced'));
    ?>
</ul>
    <div id="basic" class="form30">
        <ul>
            <li><label for='question_type'><?php $clang->eT("Question Type:"); ?></label>
                <select id='question_type' form="<?php echo $form; ?>" style='margin-bottom:5px' name='type' class='<?php echo $selectormodeclass; ?>'>
                    <?php
                        foreach ($qTypeGroups as $group => $members)
                        {
                            echo '<optgroup label="' . $group . '">';
                            foreach ($members as $type)
                            {
                                echo "<option value='{$type['tid']}'";
                                if ($eqrow['class'] == $type['class'])
                                {
                                    echo " selected='selected'";
                                }
                                echo ">{$type['name']}</option>\n";
                            }
                            echo '</optgroup>';
                        }; 
                    ?>
                </select>
            </li>
            <?php
               

               $basicSettings = array(
                   'title' => array(
                       'type' => 'string',
                       'label' => $clang->gT('Question code:'),
                       'current' => $eqrow['title']
                   ),
                   'gid' => array(
                       'type' => 'select',
                       'label' => $clang->gT('Question group:'),
                       'current' => $eqrow['gid'],
                       'options' => $groupList
                   ),
                   'relevance' => array(
                       'type' => 'relevance',
                       'label' => $clang->gT('Relevance equation:')
                   ),
                   'position' => array(
                       'type' => 'select',
                       'label' => $clang->gT("Position:"),
                       'current' => 'last',
                       'options' => $questionOrderList
                   )
               );

               Yii::import('application.helpers.PluginSettingsHelper');
               $PluginSettings = new PluginSettingsHelper();
               foreach ($basicSettings as $name => $setting)
               {
                   echo CHtml::tag('li', array(), $PluginSettings->renderSetting($name, $setting, $form, true)); 
               }
            ?>
           
        </ul>
    </div>
    <div id="advanced" class="form30">
        
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $( "#nonlocalized").tabs( "option", "collapsible", true );
        $('#nonlocalized').on('tabsbeforeactivate', function(event, ui)
        {
            if (ui.newTab.attr('id') == 'advanced_tabheader')
            {
                updatequestionattributes();
            }
        });
        
    });
    
</script>