<?php
class Example extends PluginBase {

    protected $storage = 'DbStorage';    
    static protected $description = 'Example plugin';
    
    protected $settings = array(
        'message' => array(
            'type' => 'string',
            'label' => 'Message'
        )
    );

     public function __construct(PluginManager $pluginManager, $id) 
    {
        parent::__construct($pluginManager, $id);

        $this->subscribe('dummyEvent', 'helloWorld');
        $this->subscribe('afterAdminMenuLoaded');
    }

    public function helloWorld(PluginEvent $event) 
    {
        $count = (int) $this->get('count');
        if ($count === false) $count = 0;
        $count++;
        traceVar($count);
        $this->set('count', $count);
    }
    
    public function afterAdminMenuLoaded(PluginEvent $event)
    {
        $menu = $event->get('menu', array());
        $menu['left'][]=array(
                'href' => "http://docs.limesurvey.org",
                'alt' => $event->getSender()->gT('LimeSurvey online manual'),
                'image' => 'showhelp.png'
            );
        
        $event->set('menu', $menu);
    }

}
