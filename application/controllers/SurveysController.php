<?php 

    class SurveysController extends LSYii_Controller
    {
        
        public function actionIndex()
        {
            $overview = array();
            $surveys = Survey::model()->with('languagesettings','owner')->findAll();
            foreach ($surveys as $survey)
            {
                // Get localized title.
                if (in_array(App()->getConfig('adminlang'), $survey->getLanguages()))
                {
                    $language = App()->getConfig('adminlang');
                }
                else 
                {
                    $language = $survey->language;;
                }
                
                foreach ($survey->languagesettings as $languagesetting)
                {
                    if ($language == $languagesetting->surveyls_language)
                    {
                        $title = $languagesetting->surveyls_title;
                    }
                }
                
                // Get total number of responses and completes.
                if ($survey->active == 'Y')
                {
                    $total = Survey_dynamic::model($survey->sid)->count();
                    $condition = new CDbCriteria();
                    $condition->addNotInCondition('submitdate', null);
                    $completed = Survey_dynamic::model($survey->sid)->count($condition);
                }
                $row = array(
                    'sid' => $survey->sid,
                    'title' => $title,
                    'active' => $survey->active == 'Y',
                    'owner' => array(
                        'name' => $survey->owner->full_name,
                        'id' => $survey->owner->uid,
                    ),
                    'created' => $survey->datecreated,
                    'open' => $survey->usetokens == 'N',
                    'anonymized' => $survey->anonymized == 'Y',
                    'completed' => $completed,
                    'partial' => $total - $completed,
                    'total' => $total,
                );
                
                $overview[] = $row;
                
            }
            
                    
            $this->render('/surveys/index', compact('overview'));
        }
        
        /**
         * Previews a survey.
         * @param type $id
         */
        public function actionPreview($id, $language = 'en')
        {
            $survey = Survey::model()->findByPk($id);
            $format = $survey->attributes['format'];
            var_dump($format);
            switch ($format)
            {
                case 'G':
                    
                    
                case 'Q':
                default:
            }
        }
        
        /**
         * Survey overview. 
         * @param int $id
         */
        public function actionView($id)
        {
            $survey = Survey::model()->findByPk($id);
            if ($survey != null)
            {
                $this->navData['surveyId'] = $id;
                $this->render('/surveys/view', compact('survey'));
            }
            else
            {
                App()->user->setFlash('surveys', gt('Could not find survey.'));
                $this->redirect(array('surveys/index'));
            }
            
        }
    }
?>