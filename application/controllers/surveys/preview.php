<?php 

    class Preview extends LSYii_Action
    {
        /**
         * Previews a survey.
         * @param type $id
         */
        public function run($id, $language = 'en')
        {
            
            switch (Survey::model()->findFieldByPk($id, 'format'))
            {
                case 'A': // All in one mode.
                    return $this->runAllInOne($id, $language);
                case 'G': // Group by group mode.
                    return $this->runGroupByGroup($id, $language);
                case 'Q': // Question by question mode.
                    return $this->runQuestionByQuestion($id, $language);
            }
        }
        
        
        /**
         * This function should render the whole survey in a single page.
         * @param type $id
         * @param type $language
         */
        protected function runAllInOne($id, $language)
        {
            
        }
        
        protected function runGroupByGroup($id, $language)
        {
            
            
        }
        
        protected function runQuestionByQuestion($id, $language)
        {
            
        }
        
    }

?>