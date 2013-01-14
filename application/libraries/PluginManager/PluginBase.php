<?php

    /**
     * Base class for plugins.
     */
    abstract class PluginBase implements iPlugin {

        protected $id = null;
        protected $storage = 'DummyStorage';
        
        static private $description = 'Base plugin object';
        private $store = null;
        private $settings = array();
        
        /**
         * This holds the pluginmanager that instantiated the plugin
         * 
         * @var PluginManager
         */
        protected $pluginManager;

        public function __construct(PluginManager $pluginManager, $id)
        {
            $this->pluginManager = $pluginManager;
            $this->id = $id;
        }
        
        /**
         * Returns the plugin storage and takes care of
         * instantiating it
         * 
         * @return iPluginStorage
         */
        public function getStore()
        {
            if (is_null($this->store)) {
                $this->store = $this->pluginManager->getStore($this->storage);
            }
            
            return $this->store;
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
         * This function retrieves plugin data. Do not cache this data; the plugin storage
         * engine will handling caching. After the first call to this function, subsequent 
         * calls will only consist of a few function calls and array lookups. 
         * 
         * @param string $key
         * @param string $model
         * @param int $id
         * @return boolean
         */
        protected function get($key = null, $model = null, $id = null)
        {
            return $this->getStore()->get($this, $key, $model, $id);
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
        
        /**
         * Provides meta data on the plugin settings that are available for this plugin.
         * This does not include enable / disable; a disabled plugin is never loaded.
         */
        public function getPluginSettings()
        {
            return $this->settings;
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
        
        
        
        public static function getDescription()
        {
            return static::$description;
        }
    }
?>
