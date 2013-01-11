<?php

    /**
     * Base class for plugins.
     */
    class PluginBase {
        
        protected $storage = 'DummyStorage';
        
        private $store = null;
        private $settings = array();
        
        public function __construct()
        {
            $this->store = Plugin::getStore($this->storage);
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
            return $this->store->set($this, $key, $model, $id);
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
            return $this->store->get($this, $key, $model, $id);
        }
        
        /**
         * This function subscribes the plugin to receive an event.
         * 
         * @param string $event
         */
        protected function subscribe($event, $function = null)
        {
            return Plugin::subscribe($this, $event, $function);
        }
        
        /**
         * This function unsubscribes the plugin from an event.
         * @param string $event
         */
        
        protected function unsubscribe($event)
        {
            return Plugin::unsubscribe($this, $event);
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
    }
?>
