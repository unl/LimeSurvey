<?php 
    class YesNoQuestionObject extends QuestionBase implements iQuestion 
    {
        protected $attributes = array(
            'question' => array(
                'type' => 'html',
                'localized' => true,
                'label' => 'Question text:'
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
         * @param Twig_Environment $twig
         * @param boolean $return
         * @param string $name Unique string prefix to be used for all elements with a name and or id attribute.
         * @return null|html
         */
        
        public function render($twig, $name, $return = false) 
        {
            $context = array(
                'default' => $this->default,
                'name' => $name                
            );
            if (!$return)
            {
                $twig->display('default.twig', $context);
            }
            else
            {
                return $twig->render('default.twig', $context);
            }
        }
    }
?>