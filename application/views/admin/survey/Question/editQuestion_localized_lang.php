<div id="<?php echo $language; ?>" data-language="<?php echo $language; ?>" class="localized_language tabs">
    <ul>
    <?php
        echo CHtml::tag('li', array(), CHtml::link($clang->gT("Basic settings"), "#basic_$language"));
        echo CHtml::tag('li', array(), CHtml::link($clang->gT("Advanced settings"), "#advanced_$language"));
    ?>
    </ul>
    <?php 
        echo CHtml::openTag('div', array('id' => "basic_$language", 'class' => 'form30'));

   /* 

    <ul><li>
            <label for='question_<?php echo $language; ?>'><?php $clang->eT("Question:"); ?></label>
            <div class="htmleditor">
            <textarea cols='50' rows='4' id='question_<?php echo $language; ?>' name='question_<?php echo $language; ?>'><?php echo $question; ?></textarea>
            </div>
            <?php echo getEditor("question-text","question_$language", "[".$clang->gT("Question:", "js")."](".$language.")",$surveyid,$gid,$qid,$action); ?>
        </li><li>
            <label for='help_<?php echo $language; ?>'><?php $clang->eT("Help:"); ?></label>
            <div class="htmleditor">
            <textarea cols='50' rows='4' id='help_<?php echo $language; ?>' name='help_<?php echo $language; ?>'><?php echo $help; ?></textarea>
            </div>
            <?php echo getEditor("question-help","help_$language", "[".$clang->gT("Help:", "js")."]($language)",$surveyid,$gid,$qid,$action); ?>
        </li>
    </ul>
    * 
    */
       echo CHtml::closeTag('div');
    ?>
    <?php
        echo CHtml::tag('div', array('id' => "advanced_$language", 'class' => 'advanced form30'), 'Advanced localized settings are not yet supported.');
    ?>

</div>