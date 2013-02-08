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
         * Contains the subquestion objects for this question.
         * @var iQuestion[]
         */
        protected $subQuestions;
        
        /**
         * The signature array is used for deriving a unique identifier for
         * a question type.
         * After initial release the contents of this array may NEVER be changed.
         * Changing the contents of the array will identify the question object
         * as a new question type and will break many if not all existing surves.
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
         * @param string $key
         * @param int $qid Question id.
         * @return boolean
         */
        protected function get($qid, $key = null, $default = null)
        {
            $model = 'Question';
            $keyPrefix = get_class($this);
            
                
            return $this->plugin->getStore()->get($this->plugin, $key, $model, $qid, $default);
        }
        
        public function getAttributes() 
        {
            // Merge with defaults.
            $defaults = array(
                'localized' => false, // Indicates a setting should be localized.
                'advanced' => false // Indicates a localized setting is advanced.
            );
            foreach ($this->attributes as $name => &$settings)
            {
                $settings = array_merge($defaults, $settings);
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
            $subQuestions = Questions::model()->findByAttributes(array(
                'parent_qid' => $questionId
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
                            $this->set($qid, $key, $language, $localizedValue);
                        }
                    }
                    else
                    {
                        $this->set($qid, $key, null, $value);
                    }
                        
                    
                }
            }
        }
        
        /**
         * This function saves question data. 
         * @param int $qid Question id.
         * @param string $key
         * @param string $language
         * @param mixed $value
         * @return boolean
         */
        protected function set($qid, $key = null, $language = null, $value)
        {
            $model = 'Question';
            $keyPrefix = get_class($this);
            $key = "$keyPrefix-$key";
            if (isset($language))
            {
                $key .= ".$language";
            }
            return $this->plugin->getStore()->set($this->plugin, $key, $value, $model, $qid);
        }
                
        
        
    }
?>
