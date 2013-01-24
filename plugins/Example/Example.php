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
    
    /**
     * Here you should handle subscribing to the events your plugin will handle
     */
    public function registerEvents() {
        $this->subscribe('dummyEvent', 'helloWorld');
        $this->subscribe('afterAdminMenuLoaded');
        $this->subscribe('beforeSurveySettings');
        $this->subscribe('newSurveySettings');
    }
    
    /*
     * Below are the actual methods that handle events
     */
    
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

    public function helloWorld(PluginEvent $event) 
    {
        $count = (int) $this->get('count');
        if ($count === false) $count = 0;
        $count++;
        Yii::app()->session['flashmessage'] = $this->get('message') . $count;
        $this->set('count', $count);
    }
    
    
    /**
     * This event is fired by the administration panel to gather extra settings
     * available for a survey.
     * The plugin should return setting meta data.
     * @param PluginEvent $event
     */
    public function beforeSurveySettings(PluginEvent $event)
    {
        $event->set("surveysettings.{$this->id}", array(
            'name' => get_class($this),
            'settings' => array(
                'message' => array(
                    'type' => 'string',
                    'label' => 'Message to show to users:',
                    'current' => $this->get('message', 'Survey', $event->get('survey'))
                )
            )
         ));
    }
    
    public function newSurveySettings(PluginEvent $event)
    {
        foreach ($event->get('settings') as $name => $value)
        {
            
            $this->set($name, $value, 'Survey', $event->get('survey'));
        }
    }

}
