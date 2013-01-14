<?php

    class ExamplePlugin extends PluginBase {
        
        public function __construct(PluginManager $pluginManager) 
        {
            parent::__construct($pluginManager);
            
            $this->subscribe('dummyEvent', 'helloWorld');
        }
        
        public function helloWorld() 
        {
            echo "Hello world";
        }
        
    }
?>
