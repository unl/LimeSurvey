<?php
    
    doHeader();
    echo templatereplace(file_get_contents("$templatePath/startpage.pstpl"), array(), null, null, null, null, null, $id);
    echo templatereplace(file_get_contents("$templatePath/welcome.pstpl"), array(), null, null, null, null, null, $id);
    foreach ($renderedQuestions as $questionGroup)
    {
        echo templatereplace(file_get_contents("$templatePath/startgroup.pstpl"), array(), null, null, null, null, null, $id);
        echo CHtml::tag('span', array(), "Question group " . $questionGroup['id']);
        foreach ($questionGroup['questions'] as $question)
        {
            echo CHtml::tag('div', array('class' => 'question-wrapper'), $question);
        }
        echo templatereplace(file_get_contents("$templatePath/endgroup.pstpl"), array(), null, null, null, null, null, $id);
        
    }
    
    echo templatereplace(file_get_contents("$templatePath/endpage.pstpl"), array(), null, null, null, null, null, $id);
    doFooter();
?>