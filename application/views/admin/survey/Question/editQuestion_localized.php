<div id="localized" class='tabs'>
    <ul>
        <?php
            $addlanguages=Survey::model()->findByPk($surveyid)->additionalLanguages;
            array_unshift($addlanguages, $eqrow['language']);
            foreach  ($addlanguages as $addlanguage)
            { 
                echo CHtml::tag('li', array('id' => "{$addlanguage}_tabheader", 'data-language' => $addlanguage), CHtml::link(getLanguageNameFromCode($addlanguage, false), "#{$addlanguage}"));
            }
        ?>
    </ul>
    <?php
        $eqrow  = array_map('htmlspecialchars', $eqrow);
        $this->renderPartial('/admin/survey/Question/editQuestion_localized_lang', array(
            'form' => $form,
            'clang' => $clang,
            'question' => $eqrow['question'],
            'language' => $eqrow['language'],
            'help' => $eqrow['help'],
            'surveyid' => $surveyid,
            'gid' => $gid,
            'qid' => $qid,
            'action' => $action
        ));
        $addlanguages=Survey::model()->findByPk($surveyid)->additionalLanguages;
        foreach  ($addlanguages as $addlanguage)
        { 
          
            $this->renderPartial('/admin/survey/Question/editQuestion_localized_lang', array(
                'form' => $form,
                'clang' => $clang,
                'question' => $eqrow['question'],
                'language' => $addlanguage,
                'help' => $eqrow['help'],
                'surveyid' => $surveyid,
                'gid' => $gid,
                'qid' => $qid,
                'action' => $action
            ));
        }
    ?>
        <div id='questionbottom'>
            <ul>
                <?php if ($copying) { ?>

                    <li>
                        <label for='copysubquestions'><?php $clang->eT("Copy subquestions?"); ?></label>
                        <input type='checkbox' class='checkboxbtn' checked='checked' id='copysubquestions' name='copysubquestions' value='Y' />
                    </li>
                    <li>
                        <label for='copyanswers'><?php $clang->eT("Copy answer options?"); ?></label>
                        <input type='checkbox' class='checkboxbtn' checked='checked' id='copyanswers' name='copyanswers' value='Y' />
                    </li>
                    <li>
                        <label for='copyattributes'><?php $clang->eT("Copy advanced settings?"); ?></label>
                        <input type='checkbox' class='checkboxbtn' checked='checked' id='copyattributes' name='copyattributes' value='Y' />
                    </li>

                <?php } ?>

            </ul>

                <?php if ($adding)
                    { ?>
                    <input form="<?php echo $form; ?>" type='hidden' name='action' value='insertquestion' />
                    <input form="<?php echo $form; ?>" type='hidden' name='gid' value='<?php echo $eqrow['gid']; ?>' />
                    <p><input form ="<?php echo $form; ?>" type='submit' value='<?php $clang->eT("Add question"); ?>' />
                    <?php }
                    elseif ($copying)
                    { ?>
                    <input form="<?php echo $form; ?>" type='hidden' name='action' value='copyquestion' />
                    <input form="<?php echo $form; ?>" type='hidden' id='oldqid' name='oldqid' value='<?php echo $qid; ?>' />
                    <p><input type='submit' value='<?php $clang->eT("Copy question"); ?>' />
                    <?php }
                    else
                    { ?>
                    <input form="<?php echo $form; ?>" type='hidden' name='action' value='updatequestion' />
                    <input form="<?php echo $form; ?>" type='hidden' id='newpage' name='newpage' value='' />
                    <input form="<?php echo $form; ?>" type='hidden' id='qid' name='qid' value='<?php echo $qid; ?>' />
                    <p><input form="<?php echo $form; ?>" type='button' class="saveandreturn" value='<?php $clang->eT("Save") ?>' />
                    <input form="<?php echo $form; ?>" type='submit' value='<?php $clang->eT("Save and close"); ?>' />
                    <?php } ?>
                <input form="<?php echo $form; ?>" type='hidden' id='sid' name='sid' value='<?php echo $surveyid; ?>' /></p><br />
        </div></form></div>
<script type="text/javascript">
    $(document).ready(function() {
        LS.ajax = {};
        $( ".localized_language").tabs( "option", "collapsible", true );
        $( ".localized_language").tabs( "option", "active", false);
        $('.localized_language').on('tabsbeforeactivate', function(event, ui)
        {
            var language = $(this).data('language');
            var id = ui.newPanel.attr('id');
            if (ui.newPanel.hasClass('advanced'))
            {
                var url = local_advanced_attr_url;
            }
            else
            {
                var url = local_attr_url;
            }
            
            if (LS.ajax[id] == $('#question_type').val())
            {
                return;
            }
            else
            {
                LS.ajax[id] = $('#question_type').val();
            }
            ui.newPanel.html('');

            ui.newPanel.load(url,
            {
                'language' : language,
                'qid': $('#qid').val(),
                'questionType_id' : $('#question_type').val(),
                'sid': $('#sid').val()
            }, function(){
                $('label[title]').qtip({
                    style: {name: 'cream',
                        tip: true,
                        color:'#111111',
                        border: {
                            width: 1,
                            radius: 5,
                            color: '#EADF95'}
                    },
                    position: {adjust: {
                            screen: true, scroll:true},
                        corner: {
                            target: 'bottomRight'}
                    },
                    show: {effect: {length:50}}
                });
                initializeHtmlEditors();
            }
            );    

        });

    });
</script>