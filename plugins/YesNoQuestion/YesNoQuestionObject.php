<?php 
    class YesNoQuestionObject extends QuestionBase implements iQuestion 
    {
        protected $attributes = array(
            'question' => array(
                'type' => 'html',
                'localized' => true,
                'label' => 'Question text:'
            ),
            'help' => array(
                'type' => 'html',
                'localized' => true,
                'label' => 'Help text:'
            ),
            'mandatory' => array(
                'type' => 'boolean',
                'label' => 'Mandatory:'
            ),
            'display' => array(
                'label' => 'Display using:',
                'type' =>  'select',
                'options' => array(
                    'radio' => 'Radio buttons',
                    'dropdown' => 'Dropdown list'
                    
                ),
                'localized' => false,
                'advanced' => false,
                'default' => 'dropdown'
            )
        );
        
        public static $info = array(
            'name' => 'Yes/No question'
        );
        /**
         * The signature array is used for deriving a unique identifier for
         * a question type.
         * After initial release the contents of this array may NEVER be changed.
         * Changing the contents of the array will identify the question object
         * as a new question type and will break many if not all existing surves.
         * 
         * 
         * - Add more keys to make it more unique.
         * @var array
         */
        protected static $signature = array(
            'orignalAuthor' => 'Sam Mousa',
            'originalName' => 'Yes / No',
            'startDev' => '2013-30-1'
        );
        
        /**
         * 
         * @param boolean $return
         * @param string $name Unique string prefix to be used for all elements with a name and or id attribute.
         * @return null|html
         */
        
        public function render($name, $language, $return = false) 
        {
            $questionText = $this->get('question', '', $language);
            
            $value = $this->getResponse();
            
            $out = CHtml::label($questionText, $name);
            
            $data = array(
                1 => 'Yes',
                0 => 'No'
            );
            if ($this->get('display') == 'dropdown')
            {
                $out .= CHtml::dropDownList($name, $value, $data);
                
            }
            else
            {
                $out .= CHtml::radioButtonList($name, $value, $data);
            }
            
            if ($return)
            {
                return $out;
            }
            else
            {
                echo $out;
            }
        }
    }
?>