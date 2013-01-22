<?php


    class YesNoQuestion extends QuestionPluginBase
    {
        
        protected $properties = array(
            'Property defined in yesno' => array(
                
            )
        );
               
        protected $default = 0;
        
        public function registerEvents() {
            // No events handled yet
        }
        
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
