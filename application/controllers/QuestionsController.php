<?php

    /**
     * @todo Add proper security for this controller.
     */

    class QuestionsController extends LSYii_Controller
    {
        
        public function __construct($id, $module = null) {
            parent::__construct($id, $module);
            Yii::import('application.libraries.Limesurvey_lang');
            Yii::import('application.helpers.surveytranslator_helper', true);
        }
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
            if ($question->save())
            {
                $questionObject = tidToQuestion($_POST['type']);
                $questionObject->saveAttributes($question->qid, $attributes);
            }
            
            $this->redirect(array('admin/survey', 'sa' => 'view', 'surveyid' => $question->sid, 'gid' => $question->gid, 'qid' => $question->qid));
        }
        
        
        public function actionUpdate($qid)
        {
            /**
             * @todo Remove language column from question table; make qid the primary key.
             */
            $question = Questions::model()->findByAttributes(array('qid' => $qid));
            if (isset($question))
            {
                $question = $question->attributes;
                
                $attributes = App()->getPluginManager()->constructQuestionFromGUID($question['questiontype'], $question['qid'])->getAttributes('*');
                $this->navData['surveyId'] = $question['sid'];
                $survey = Survey::model()->findByPk($question['sid']);
                $languages = $survey->getLanguages();
                $groups = Groups::model()->findListByAttributes(array('sid' => $question['sid']), 'group_name');
                $questions = Questions::model()->findListByAttributes(array('sid' => $question['sid']), 'code', null, array('order'=> 'sortorder'));
                $questiontypes = App()->getPluginManager()->loadQuestionObjects();
                $this->render('/questions/update', compact('question', 'languages', 'groups', 'questions', 'questiontypes', 'attributes'));
                
            }
            
        }
        
        
    }

?>