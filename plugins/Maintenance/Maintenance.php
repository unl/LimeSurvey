<?php 

    class Maintenance extends PluginBase
    {
        
        protected $storage = 'DbStorage';    
        static protected $description = 'Maintenance mode plugin';
    
        protected $settings = array(
            'maintenance' => array(
                'type' => 'boolean',
                'label' => 'Enable maintence mode:'
            ),
            'message' => array(
                'type' => 'string',
                'label' => 'Message to show to users:'
            )
        );
    
        /**
         * Here you should handle subscribing to the events your plugin will handle
         */
        public function registerEvents() {
            // Only describe to events if in maintenance mode.
            if ($this->get('maintenance') == 1)
            {
                $this->subscribe('beforeLogin');
                $this->subscribe('beforeSurveyController');
            }
        }
    
        public function __construct(PluginManager $pluginManager, $id) 
        {
            parent::__construct($pluginManager, $id);
        }
        
        
        public function beforeLogin(PluginEvent $event)
        {
            if ($this->get('maintenance') == 1)
            {
                $user = $event->get('user');
                if ($user == null || $user->superadmin != 1)
                {
                    Yii::app()->session['flashmessage'] = 'Login denied for non-super-users. Maintenance mode is active.';
                    $event->stop();
                }
            }
        }
        
        public function beforeSurveyController(PluginEvent $event)
        {
            $event->stop();
            // Render an alternative view.
            echo $this->get('message');
        }
                
        
        
    }












?>