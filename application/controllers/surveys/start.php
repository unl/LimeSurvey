<?php 

    class Start extends LSYii_Action
    {
        /**
         * Starts a survey.
         * @param type $id
         */
        public function run($id, $language = null, $token = null)
        {
            // Find the survey.
            $survey = Survey::model()->findByPk($id);
            if (isset($survey))
            {
                if (true || $survey->active == 'Y')
                {
                    // Check if tokens are required.
                    if ($survey->usetokens == 'Y' && isset($token))
                    {
                        // Check if token is valid.
                        $token = Tokens_dynamic::model($id)->findByAttributes(array(
                            'token' => $token
                        ));
                        debug($token);
                        debug('token checks not yet implemented.');
                    }
                    
                    /*
                     * If we get here we can start the survey.
                     * Note that since this is the start entry point, we ALWAYS
                     * reset any existing session for this survey.
                     */
                    if (!in_array($language, explode(' ', $survey->additional_languages)))
                    {
                        $language = null;
                    }
                    if (App()->getSurveySession()->create($id, true, $language))
                    {
                        $this->getController()->redirect(array('surveys/welcome', 'id' => $id));
                    }
                    else
                    {
                        debug('Could not create survey session.');
                    }
                }
                else
                {
                    debug('Survey is not active.');
                }
                    
            }
            else 
            {
                debug("Survey not found.");
            }
        }
    }

?>