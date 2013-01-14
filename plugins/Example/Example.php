<?php
class Example extends PluginBase {

    protected $storage = 'DbStorage';    
    static protected $description = 'Example plugin';

     public function __construct(PluginManager $pluginManager, $id) 
    {
        parent::__construct($pluginManager, $id);

        $this->subscribe('dummyEvent', 'helloWorld');
    }

    public function helloWorld() 
    {
        $count = (int) $this->get('count');
        if ($count === false) $count = 0;
        $count++;
        traceVar($count);
        $this->set('count', $count);
    }

}
