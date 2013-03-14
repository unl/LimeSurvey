<?php 

    class SurveysController extends LSYii_Controller
    {

        public function __construct($id, $module = null) {
            parent::__construct($id, $module);
            if (App()->user->isGuest)
            {
                $this->defaultAction = 'publiclist';
            }
        }
        
        public function actions() 
        {
            $externalActions = array(
                'preview' => 'application.controllers.surveys.Preview',
                'start' => 'application.controllers.surveys.Start',
                // All in one survey
                'survey' => 'application.controllers.surveys.SurveyAllInOne',
                'publiclist' => 'application.controllers.surveys.PublicList'
            );  
            
            return array_merge(parent::actions(), $externalActions);
        }

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
                else
                {
                    $completed = 0;
                    $total = 0;
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
        
        
        public function actionWelcome($id)
        {
            App()->loadHelper('surveytranslator');
            App()->loadHelper('Twig');
            // Check if session exists.
            if (App()->getSurveySession()->exists($id))
            {
                
                $survey = Survey::model()->findByPk($id);
                
                // Show welcome screen unless it is disabled or the format is all in one.
                
                if (isset($survey) && $survey->showwelcome == 'Y' && $survey->format != 'A')
                {
                    // Get the welcome message.
                    $survey_languagesettings = Surveys_languagesettings::model()->findByAttributes(array(
                        'surveyls_survey_id' => $id,
                        'surveyls_language' => App()->getSurveySession()->read($id, 'language')
                    ));
                    $context = array(
                        'survey' => array(
                            'title' => $survey_languagesettings->surveyls_title,
                            'welcome' => $survey_languagesettings->surveyls_welcometext,
                            'questionCount' => 15,
                            'language' => App()->getSurveySession()->read($id, 'language'),
                            'description' => $survey_languagesettings->surveyls_description
                        ),
                        'navigator' => array(
                            'next' => true,
                            'previous' => false,
                            'clearall' => true,
                            'load' => true,
                            'save' => false
                        )
                    );
                    $template = $survey->template;
                    $this->layout = 'true';
                    $this->render('welcome', compact('template', 'context'));
                    //debug('WELCOME!!!');
                    //debug($survey_languagesettings->attributes);
                }
                elseif (isset($survey))
                {
                    // Breaks are just for readability / best practice.
                    switch ($survey->format) {
                        case 'Q':
                            $this->redirect(array('surveys/question', 'id' => $id));
                            break;
                        case 'A':
                            $this->redirect(array('surveys/survey', 'id' => $id));
                            break;
                        case 'G': 
                            $this->redirect(array('surveys/group', 'id' => $id));
                            break;
                    }
                }
                else
                {
                    debug('survey could not be found; should probably remove surveysessions..');
                }
            }
            else
            {
                debug('survey session could not be found; do some redirect here.');
            }
        }
    }
?>