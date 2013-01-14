<?php
class Example extends PluginBase {

    static protected $description = 'Example plugin';

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
