<?php


    abstract class QuestionBase implements iQuestion {
        /**
         * Array containing meta data for supported question attributes.
         * @var array
         */
        
        protected $attributes;
        
        /**
         * Array containing an array for each column.
         * The supported keys for column meta data are:
         * - type
         * - name
         * - dbname
         * 
         * @var array
         */
        protected $columns;
        
        
        public static $info = array();
        
        /**
         *
         * @var iPlugin
         */
        protected $plugin;
        
        /**
         *
         * @var int The question id for this question object instance.
         */
        protected $questionId = null;
        
        /**
         * @var int The response id for this question object instance.
         */
        protected $responseId = null;
        /**
         * Contains the subquestion objects for this question.
         * @var iQuestion[]
         */
        protected $subQuestions;
        
        /**
         * The signature array is used for deriving a unique identifier for
         * a question type.
         * After initial release the contents of this array may NEVER be changed.
         * Changing the contents of the array will identify the question object
         * as a new question type and will break many if not all existing surveys.
         * 
         * 
         * - Add more keys & values to make it more unique.
         * @var array
         */
        protected static $signature = array();
        
        /**
         * @param iPlugin $plugin The plugin to which this question belongs.
         * @param int $questionId
         * @param int $responseId Pass a response id to load results.
         */
        
        public function __construct(iPlugin $plugin, $questionId = null, $responseId = null) {
            $this->plugin = $plugin;
            $this->responseId = $responseId;
            $this->questionId = $questionId;
            if (isset($questionId))
            {
                $this->loadSubQuestions($questionId);
            }
            
        }
        
        /**
         * This function retrieves question data. Do not cache this data; the plugin storage
         * engine will handling caching. After the first call to this function, subsequent 
         * calls will only consist of a few function calls and array lookups. 
         * 
         * @param string $key String identifier for data.
         * @param mixed $default Default value.
         * @param string $language Language to retrieve.
         * @param int $questionId By default uses the question id for the current instance. Override this to read from another question.
         * @return boolean
         */
        protected function get($key = null, $default = null, $language = null, $questionId = null)
        {
            if (!isset($qid) && isset($this->questionId))
            {
                $questionId = $this->questionId;
                return $this->plugin->getStore()->get($this->plugin, $key, 'Question', $questionId, $default, $language);
            }
            else
            {
                return false;
            }
        }
        
        /**
         * Gets the meta data for question attributes.
         * Optionally pass one or more languages to also get current values.
         * Pass * to get all stored languages.
         * @param type $language
         * @return type
         */
        public function getAttributes($languages = null) 
        {
            // Merge with defaults.
            $defaults = array(
                'localized' => false, // Indicates a setting should be localized.
                'advanced' => false // Indicates a localized setting is advanced.
            );
            foreach ($this->attributes as $name => &$metaData)
            {
                $metaData = array_merge($defaults, $metaData);
                if (isset($this->questionId))
                {
                    if (is_array($languages))
                    {
                        foreach ($languages as $language)
                        {
                            $metaData['current'][$language] = $this->get($name, null, $language);
                        }
                    }
                    else
                    {
                        $metaData['current'] = $this->get($name, null, $languages);
                    }
                }
            }
            
            return $this->attributes;
        }
        
        public function getColumns()
        {
            return $this->columns;
        }
        
        /**
         * This function derives a unique identifier for identifying a question type.
         */
        public static function getGUID()
        {
            // We use json_encode because it is faster than serialize.
            return md5(json_encode(static::$signature));
        }
        
        /**
         * Load the question data from the questions model.
         * @param type $questionId
         */
        public function loadSubQuestions($questionId)
        {
            $subQuestions = Questions::model()->findAllByAttributes(array(
                'parent_id' => $questionId
            ));
            foreach ($subQuestions as $subQuestion)
            {
                /**
                 * @todo Alter this so that subquestion can be of another type.
                 */
                $this->subQuestions[] = new self($subQuestion->qid, $this->responseId);
            }
        }
        
        public function saveAttributes($qid, array $attributeValues) 
        {
            $attributes = $this->getAttributes();
            $result = true;
            foreach ($attributeValues as $key => $value)
            {
                // Check if the attribute is valid for the question.
                if (isset($attributes[$key]))
                {
                    // If the attribute is localized, save each language.
                    if ($attributes[$key]['localized'])
                    {
                        foreach ($value as $language => $localizedValue)
                        {
                            if (!$this->set($qid, $key, $localizedValue, $language))
                            {
                                $result = false;
                            }
                        }
                    }
                    else
                    {
                        if (!$this->set($qid, $key, $value))
                        {
                            $result = false;
                        }
                    }
                        
                    
                }
            }
            
            return $result;
        }
        
        /**
         * This function saves question data. 
         * @param int $qid Question id.
         * @param string $key
         * @param string $language
         * @param mixed $value
         * @return boolean
         */
        protected function set($qid, $key, $value, $language = null)
        {
            return $this->plugin->getStore()->set($this->plugin, $key, $value, 'Question', $qid, $language);
        }
                
        
        
    }
?>
