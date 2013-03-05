<?php 

    /**
     * Creates a list of fields available for use in EM.
     * @param int $surveyId
     */

     
    function createFieldList($surveyId)
    {
        $questions = Questions::model()->findAllByAttributes(array(
            'sid' => $surveyId
        ));
        
        $fields = array();
        foreach ($questions as $question)
        {
            $questionObject = App()->getPluginManager()->constructQuestionFromGUID($question->questiontype, $question->qid);
            $fields = array_merge($questionObject->getVariables(), $fields);
        }
        
        
        
        return $fields;
        
        
    }


?>