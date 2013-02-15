<?php 

    class GroupsController extends LSYii_Controller
    {
        public function actionPreview($gid, $language = null)
        {
            $group = Groups::model()->findByAttributes(array(
                'gid' => $gid
            ));
            $renderedQuestions = array();
            if (isset($group))
            {
                
                if (!isset($language))
                {
                    $language = Survey::model()->findFieldByPk($group->sid, 'language');
                }
                
                $questions = Questions::model()->findAllByAttributes(array(
                    'gid' => $group->gid,
                    'parent_id' => null
                ));
                $renderedQuestions = array();
                foreach ($questions as $question)
                {
                    $questionObject = App()->getPluginManager()->constructQuestionFromGUID($question->questiontype, $question->qid);
                    $renderedQuestions[] = $questionObject->render("{$group->group_name}-{$question->qid}", $language, true);
                }
            }
            $this->layout = 'survey';
            $this->render('/groups/preview', compact('renderedQuestions'));
        }
    }
?>