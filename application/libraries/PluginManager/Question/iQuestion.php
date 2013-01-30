<?php

    interface iQuestion {      
        
        
        public function __construct(iPlugin $plugin, $questionId = null, $responseId = null);
        
        /**
         * Function that returns meta data for the available attributes
         * for the question type.
         * 
         */
        public function getAttributes();
        
        /**
         * This function derives a unique identifier for identifying a question type.
         */
        public static function getGUID();
        
        /**
         * @param Twig_Environment $twig A reference to configured Twig Environment.
         * This Twig environment will have a correctly configured translation environment.
         * This Twig environment will have the plugin view path configured in its loader.
         * @param bool $return If true, return the content instead of outputting it.
         */
        public function render($twig, $name, $return = false);
        
        
        
        
    }
?>
