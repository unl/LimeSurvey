<?php

    /**
     * @todo Add proper security for this controller.
     */

    class QuestionsController extends LSYii_Controller
    {
        /**
         * Creates a new question object.
         */
        public function actionCreate()
        {
            $question = new Questions();
            
            
            if ($_POST['position'] == 'last')
            {
                $cmd = App()->db->createCommand();
                $cmd->select(array('max(question_order) as max'));
                $cmd->from(Questions::model()->tableName());
                $cmd->where('sid = :sid', array('sid' =>$_POST['sid']));
                $maxPosition =  intval($cmd->queryScalar());
                $question->question_order = $maxPosition + 1;
            }
            elseif ($_POST['position'] == 'first') 
            {
                // We save the new question with order 0.
                // After saving we will increase all ordering numbers by 1.
                $question->question_order = 0;
            }
            else
            {
                $question->question_order = intval($_POST['position']) + 1;
            }
                
            $question->title = $_POST['title'];
            $question->relevance = $_POST['relevance'];
            $question->questiontype_id = $_POST['type'];
            $question->sid = $_POST['sid'];
            $question->gid = $_POST['gid'];
            
            
            $attributes = $_POST;
            unset($attributes['sid'], $attributes['gid'], $attributes['relevance'], $attributes['type'], $attributes['title'], $attributes['position']);
            if (true || $question->save())
            {
                // @var $question iQuestion
                
                echo '<pre>';
                $questionObject = tidToQuestion($_POST['type']);
                $questionObject->saveAttributes($question->qid, $attributes);
                //$question->save();

                echo '</pre>';
                die();
            }
        }
    }

?>