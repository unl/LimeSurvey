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
        public function actionCreate($gid, $questiontype = 'd58fa4752cf173a50411b19a0243a0c8')
        {
            $group = Groups::model()->findByAttributes(array('gid' => $gid))->attributes;
            
            if (App()->request->getIsPostRequest())
            {
                // Create new question.
                $question = new Questions();
                $question->sid = $group['sid'];
                $question->code = $_POST['code'];
                $question->questiontype = $_POST['questiontype'];
                $question->save();
                $questionObject = App()->getPluginManager()->constructQuestionFromGUID($questiontype, $question->qid);
                if ($questionObject->saveAttributes($_POST))
                {
                    App()->user->setFlash('updateQuestion', gT('All question attributes updated.'));
                }
                else
                {
                    App()->user->setFlash('updateQuestion', gT('Could not update question attributes.'));
                }

                // Always redirect to prevent reloading from resubmitting.
                $this->redirect(array('questions/update', 'id' => $question->qid));

            }
            else
            {
                $questionObject = App()->getPluginManager()->constructQuestionFromGUID($questiontype);
            }
            
            if (!isset($questionObject))
            {
                App()->user->setFlash('questions', 'Could not create question object.');
                $this->redirect(array('/groups/view', 'id' => $gid));
            }
            $attributes = $questionObject->getAttributes('*');
            $this->navData['surveyId'] = $group['sid'];
            $this->navData['groupId'] = $gid;
            $survey = Survey::model()->findByPk($group['sid']);
            $languages = $survey->getLanguages();
            $attributes['gid']['options'] = Groups::model()->findListByAttributes(array('sid' => $group['sid']), 'group_name');
            $questions = Questions::model()->findListByAttributes(array('sid' => $group['sid']), 'code', null, array('order'=> 'sortorder'));
            if (App()->request->getIsAjaxRequest())
            {
                $this->renderPartial('/questions/update', compact('question', 'languages', 'groups', 'questions', 'attributes'));
            }
            else
            {
                $this->render('/questions/update', compact('question', 'languages', 'groups', 'questions', 'attributes'));
            }

            
        }
        
        
        public function actionPreview($id, $language = 'en')
        {
            App()->setLang(new Limesurvey_lang($language));
            $question = Questions::model()->findByPk($id);
            $template = Survey::model()->findFieldByPk($question->sid, 'template');
            if (isset($question))
            {
                $questionObject = App()->getPluginManager()->constructQuestionFromGUID($question->questiontype, $id);
                $contents = $questionObject->render("preview$id", $language, true);
                $this->layout = false;
                $this->render('preview', compact('contents', 'template'));
            }
            
        }
        public function actionUpdate($id, $questiontype = null)
        {
            $question = Questions::model()->findByPk($id);
            if ($questiontype == null)
            {
                $questiontype = $question->questiontype;
            }
            if (isset($question))
            {
                $questionObject = App()->getPluginManager()->constructQuestionFromGUID($questiontype, $id);
                // If post handle submitted data.
                if (App()->request->getIsPostRequest())
                {
                    if ($questionObject->saveAttributes($_POST))
                    {
                        App()->user->setFlash('updateQuestion', gT('All question attributes updated.'));
                    }
                    else
                    {
                        App()->user->setFlash('updateQuestion', gT('Could not update question attributes.'));
                    }
                    
                    // Always redirect to prevent reloading from resubmitting.
                    $this->redirect(array($this->route, 'id' => $id));
                }
                /**
                 * @todo Add support for save & close button; in case of close redirect to overview page.
                 */
                $question = $question->attributes;
                $attributes = $questionObject->getAttributes('*');
                $this->navData['surveyId'] = $question['sid'];
                $this->navData['groupId'] = $question['gid'];
                $this->navData['questionId'] = $id;
                $survey = Survey::model()->findByPk($question['sid']);
                $languages = $survey->getLanguages();
                $groups = Groups::model()->findListByAttributes(array('sid' => $question['sid']), 'group_name');
                $questions = Questions::model()->findListByAttributes(array('sid' => $question['sid']), 'code', null, array('order'=> 'sortorder'));
                if (App()->request->getIsAjaxRequest())
                {
                    $this->renderPartial('/questions/update', compact('question', 'languages', 'groups', 'questions', 'attributes'));
                }
                else
                {
                    $this->render('/questions/update', compact('question', 'languages', 'groups', 'questions', 'attributes'));
                }
            }
        }
        
        
        
    }

?>