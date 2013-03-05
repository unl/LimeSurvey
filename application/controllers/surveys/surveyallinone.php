<?php 

    class SurveyAllInOne extends LSYii_Action
    {
        /**
         * Runs a survey.
         * @param int $id Survey id.
         */
        public function run($id)
        {
            //debug('this page should show the all in one survey');
            // Check if survey session exists.
            if (App()->getSurveySession()->exists($id))
            {
                $survey = Survey::model()->findByPk($id);
                $this->getController()->layout = false;
                
                
                $templatePath = getTemplatePath($survey->template);
                $renderedQuestions = $this->renderQuestions($id);
                $this->getController()->render('allinone', compact('templatePath', 'id', 'renderedQuestions'));
            }
            
           
        }
        
        /**
         * Renders all questions for the survey.
         * @param int $id
         */
        protected function renderQuestions($id)
        {
            // Get the order of the groups.
            $groupOrder = (App()->getSurveySession()->getGroupOrder($id));
            $result = array();
            foreach ($groupOrder as $groupId)
            {
                $group = array(
                    'id' => $groupId,
                    'questions' => array()
                    
                );
                $questions = Questions::model()->findAllByAttributes(array(
                    'gid' => $groupId,
                    'parent_id' => null
                ));
                foreach ($questions as $question)
                {
                    $questionObject = App()->getPluginManager()->constructQuestionFromGUID($question->questiontype, $question->qid);
                    $group['questions'][] = $questionObject->render("questions[{$question->code}]", App()->getSurveySession()->read($id, 'language'), true);
                }
                $result[] = $group;
            }
            return $result;
        }
    }

?>