<?php 
    /**
     * This class manages survey sessions and stores necessary data
     */
    class SurveySession
    {
        
        protected function check($surveyId, $key = '')
        {
            if ($this->exists($surveyId))
            {
                $parts = explode('.', $key);
                $array = $_SESSION[__CLASS__]['sessions'][$surveyId];
                foreach ($parts as $part)
                {
                    if (is_array($array) && isset($array[$part]))
                    {
                        $array = $array[$part];
                    }
                    else
                    {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }
        
        /**
         * This function initializes a new session for a survey.
         * If reset = true any existing session for the survey is first removed.
         * @param int $surveyId
         * @param boolean $reset
         * @return boolean True if a new session was started.
         */
        public function create($surveyId, $reset = false, $language = null)
        {
            if ($reset || !$this->exists($surveyId))
            {
                $this->destroy($surveyId);
                $_SESSION[__CLASS__]['sessions'][$surveyId] = array();
                
                return $this->init($surveyId, $language);
            }
            else
            {
                return false;
            }
            
        }
           
        /**
         * Destroy a survey session.
         * @param int $surveyId
         */
        public function destroy($surveyId)
        {
            if ($this->exists($surveyId))
            {
                unset($_SESSION[__CLASS__]['sessions'][$surveyId]);
            }
        }
        /**
         * This function checks if a survey session exists.
         * @param int $surveyId
         * @return boolean True if a session for the survey exists in the user session.
         */
        public function exists($surveyId)
        {
            return (isset($_SESSION[__CLASS__]['sessions'][$surveyId]));
        }
        
        /**
         * This functions returns the list of groups in the order for the current session.
         * Group randomization is implemented here.
         */
        public function getGroupOrder($surveyId)
        {
            // Get the randomization groups in a survey.
            $criteria = new CDbCriteria();
            $criteria->order = 'group_order';
            
            $groups = Groups::model()->findAllByAttributes(array(
                'sid' => $surveyId,
                'language' => $this->read($surveyId, 'language')
            ), $criteria);
            
            $randomizationGroups = array();
            $mapGroupOrder = array();
            foreach ($groups as $group)
            {
                $order = (int) $group->group_order;
                $gid = (int) $group->gid;
                if ($group->randomization_group != null && $group->randomization_group !='')
                {
                    $randomizationGroups[$group->randomization_group]['group_order'][] = $order;
                    $randomizationGroups[$group->randomization_group]['gid'][] = $gid;
                }
                else
                {
                    $mapGroupOrder[$gid] = $order;
                }
            }
            // Add the groups to the map by randomization group.
            
            // This seed guarantees we get the same result for the same session.
            
            //srand($this->read($surveyId, 'randomSeed'));
            foreach ($randomizationGroups as $randomizationGroup)
            {
                shuffle($randomizationGroup['group_order']);
                foreach($randomizationGroup['gid'] as $index => $gid)
                {
                    $mapGroupOrder[$gid] = $randomizationGroup['group_order'][$index];
                }
            }
            // Sort map by group order.
            asort($mapGroupOrder);
            return array_flip($mapGroupOrder);
        }
        
        /**
         * This function initializes an empty survey session.
         * @param int $surveyId
         * @return boolean True if successfull, false otherwise.
         */
        protected function init($surveyId, $language = null)
        {
            if ($this->exists($surveyId) && count($this->read($surveyId)) == 0)
            {
                // Initialize all the survey session variables here.
                
                // Seed used for random generator(s); for example group randomization.
                $this->write($surveyId, 'randomSeed', mt_rand());
                
                // Creation date of the session.
                $this->write($surveyId, 'created', date(DATE_ATOM));
                
                // If language is not specified, use default / base language.
                if (!isset($language))
                {
                    $language = Survey::model()->findFieldByPk($surveyId, 'language');
                }
                $this->write($surveyId, 'language', $language);
                
                // Set the question count.
                $this->write($surveyId, 'questionCount', Questions::model()->countByAttributes(array(
                    'sid' => $surveyId,
                    'parent_id' => null
                )));
               
                return true;
            }
            else 
            {
                return false;
            }
        }
        
        
        public function read($surveyId, $key = null)
        {
            if ($this->exists($surveyId))
            {
                $array = $_SESSION[__CLASS__]['sessions'][$surveyId];
                if (is_string($key))
                {
                    $parts = explode('.', $key);
                    foreach ($parts as $part)
                    {
                        if (is_array($array) && isset($array[$part]))
                        {
                            $array = $array[$part];
                        }
                        else
                        {
                            return;
                        }
                    }
                }
                return $array;
            }
        }
        
        protected function write($surveyId, $key, $value)
        {
            if ($this->exists($surveyId))
            {
                // Split by . to allow for arrays using dotnotation.
                $keys = explode('.', $key);
                while (count($keys) > 0)
                {
                    $key = array_pop($keys);
                    if ($key == '')
                    {
                        $value = array($value);
                    }
                    else
                    {
                        $value = array($key => $value);
                    }

                }
                $_SESSION[__CLASS__]['sessions'][$surveyId] = array_merge($this->read($surveyId), $value);
        
                
                
                
            }
        }
        
        
        
        
        
    }

?>