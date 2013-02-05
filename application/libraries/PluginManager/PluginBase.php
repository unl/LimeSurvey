<?php

    /**
     * Base class for plugins.
     */
    abstract class PluginBase implements iPlugin {

        protected $id = null;
        protected $storage = 'DummyStorage';
        
        static protected $description = 'Base plugin object';
        private $store = null;
        protected $settings = array();
        
        /**
         * This holds the pluginmanager that instantiated the plugin
         * 
         * @var PluginManager
         */
        protected $pluginManager;

        /**
         * Constructor for the plugin
         * 
         * @param PluginManager $manager    The plugin manager instantiating the object
         * @param int           $id         The id for storage
         */
        public function __construct(PluginManager $manager, $id)
        {
            $this->pluginManager = $manager;
            $this->id = $id;
        }
        
        /**
         * This function retrieves plugin data. Do not cache this data; the plugin storage
         * engine will handling caching. After the first call to this function, subsequent 
         * calls will only consist of a few function calls and array lookups. 
         * 
         * @param string $key
         * @param string $model
         * @param int $id
         * @return boolean
         */
        protected function get($key = null, $model = null, $id = null, $default = null)
        {
            return $this->getStore()->get($this, $key, $model, $id, $default);
        }
        
        /**
         * Return the description for this plugin
         */
        public static function getDescription()
        {
            return static::$description;
        }
        
        /**
         * Returns the id of the plugin
         * 
         * Used by storage model to find settings specific to this plugin
         * 
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }
        
        /**
         * Provides meta data on the plugin settings that are available for this plugin.
         * This does not include enable / disable; a disabled plugin is never loaded.
         * 
         */
        public function getPluginSettings($getValues = true)
        {
            
            $settings = $this->settings;
            if ($getValues)
            {
                foreach ($settings as $name => &$setting)
                {
                    $setting['current'] = $this->get($name);
                }
            }
            return $settings;
        }
        /**
         * Returns the plugin storage and takes care of
         * instantiating it
         * 
         * @return iPluginStorage
         */
        protected function getStore()
        {
            if (is_null($this->store)) {
                $this->store = $this->pluginManager->getStore($this->storage);
            }
            
            return $this->store;
        }
        
        
          /**
         * 
         * @param string $name Name of the setting.         
         * The type of the setting is either a basic type or choice.
         * The choice type is either a single or a multiple choice setting.
         * @param array $options
         * Contains parameters for the setting. The 'type' key contains the parameter type.
         * The type is one of: string, int, float, choice.
         * Supported keys per type:
         * String: max-length(int), min-length(int), regex(string).
         * Int: max(int), min(int).
         * Float: max(float), min(float).
         * Choice: choices(array containing values as keys and names as values), multiple(bool)
         * Note that the values for choice will be translated.
         */
        protected function registerSetting($name, $options = array('type' => 'string'))
        {
            $this->settings[$name] = $options;
        }
        
        /**
         * 
         * @param type $settings
         */
        public function saveSettings($settings)
        {
            foreach ($settings as $name => $setting)
            {
                $this->set($name, $setting);
            }
        }
        
        
        /**
         * This function stores plugin data.
         * 
         * @param string $key
         * @param mixed $data
         * @param string $model
         * @param int $id
         * @return boolean
         */
        protected function set($key, $data, $model = null, $id = null)
        {
            return $this->getStore()->set($this, $key, $data, $model, $id);
        }
    
        /**
         * Here you should handle subscribing to the events your plugin will handle
         */
        //abstract public function registerEvents();
        
        /**
         * This function subscribes the plugin to receive an event.
         * 
         * @param string $event
         */
        protected function subscribe($event, $function = null)
        {
            return $this->pluginManager->subscribe($this, $event, $function);
        }
        
        /**
         * This function unsubscribes the plugin from an event.
         * @param string $event
         */        
        protected function unsubscribe($event)
        {
            return $this->pluginManager->unsubscribe($this, $event);
        }

    }