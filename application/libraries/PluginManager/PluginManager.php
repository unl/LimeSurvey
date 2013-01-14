<?php
    /**
     * Factory for limesurvey plugin objects.
     */
    class PluginManager {
        
        private $stores = array();
        
        private $subscriptions = array();
        
        /**
         * Returns the storage instance of type $storageClass.
         * If needed initializes the storage object.
         * @param string $storageClass
         */
        public function getStore($storageClass)
        {
            if (!isset($this->stores[$storageClass]))
            {
                $this->stores[$storageClass] = new $storageClass();
            }
            return $this->stores[$storageClass];
        }
        
        /**
         * Registers a plugin to be notified on some event.
         * @param Object $plugin Reference to the plugin.
         * @param string $event Name of the event.
         * @param string $function Optional function of the plugin to be called.
         */
        public function subscribe(Object $plugin, $event, $function = null)
        {
            if (!isset($this->subscriptions[$event]))
            {
                $this->subscriptions[$event] = array();
            }
            if (!$function)
            {
                $function = $event;
            }
            $subscription = array($plugin, $function);
            // Subscribe only if not yet subscribed.
            if (!in_array($subscription, $this->subscriptions[$event]))
            {
                $this->subscriptions[$event][] = $subscription;
            }
            
            
        }
        
        /**
         * Unsubscribes a plugin from an event.
         * @param Object $plugin Reference to the plugin being unsubscribed.
         * @param string $event Name of the event. Use '*', to unsubscribe all events for the plugin.
         * @param string $function Optional function of the plugin that was registered.
         */
        public function unsubscribe(Object $plugin, $event)
        {
            // Unsubscribe recursively.
            if ($event == '*')
            {
                foreach ($this->subscriptions as $event)
                {
                    $this->unsubscribe($plugin, $event);
                }
            }
            elseif (isset($this->subscriptions[$event]))
            {
                foreach ($this->subscriptions[$event] as $index => $subscription)
                {
                    if ($subscription[0] == $plugin)
                    {
                        unset($this->subscriptions[$event][$index]);
                    }
                }
            }
        }
        
        
        /**
         * This function dispatches an event to all registered plugins.
         * @param type $event Name of the event.
         * @param type $params Parameters to be passed to the event handlers.
         */
        public function dispatchEvent($event, $params = array())
        {
            $eventResults = array();
            if (isset($this->subscriptions[$event]))
            {
                foreach($this->subscriptions[$event] as $subscription)
                {
                    $eventResults[get_class($subscription[0])] = call_user_func_array($subscription, $param_arr);
                }
            }
            
        }
    }
?>
