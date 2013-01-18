<?php

    interface iQuestionPlugin extends iPlugin {
        
        /**
         * Constructor for a question object.
         * @param bool $live True if we plan to display the question, false during administration.
         * 
         */
        public function __construct(PluginManager $pluginManager, $id);
        
        
        /**
         * @param Twig_Environment $twig A reference to configured Twig Environment.
         * This Twig environment will have a correctly configured translation environment.
         * This Twig environment will have the plugin view path configured in its loader.
         * @param bool $return If true, return the content instead of outputting it.
         */
        public function render($twig, $return = false);
        
    }
?>
