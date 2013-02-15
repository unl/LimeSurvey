<?php 
    foreach ($renderedQuestions as $renderedQuestion)
    {
        echo CHtml::tag('div', array('class' => 'question'), $renderedQuestion);
        
    }
?>