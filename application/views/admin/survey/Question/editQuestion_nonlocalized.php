<div id="nonlocalized" class="nonlocalized tabs">
    <ul>
    <?php
        echo CHtml::tag('li', array('id' => 'basic_tabheader'), CHtml::link($clang->gT("Basic settings"), '#basic'));
        echo CHtml::tag('li', array('id' => 'advanced_tabheader'), CHtml::link($clang->gT("Advanced settings"), '#advanced'));
    ?>
    </ul>
    <div id="basic" class="">
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
                       'label' => gT('Question code:'),
                       'current' => $question['title']
                   ),
                   'gid' => array(
                       'type' => 'select',
                       'label' => gT('Question group:'),
                       'current' => $question['gid'],
                       'options' => $groupList
                   ),
                   'relevance' => array(
                       'type' => 'relevance',
                       'label' => gT('Relevance equation:')
                   ),
                   'position' => array(
                       'type' => 'select',
                       'label' => gT("Position:"),
                       'current' => 'last',
                       'options' => $questionOrderList
                   ),
                   'randomgroup' => array(
                       'type' => 'string',
                       'label' => gT('Randomization group:')
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
    <div id="advanced">
        
    </div>
</div>
