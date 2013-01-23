<?php
    /**
     * Factory for limesurvey plugin objects.
     */
    class PluginManager {

        private $stores = array();

        private $subscriptions = array();
        
        protected $_plugins = array();

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
         * @param iPlugin $plugin Reference to the plugin.
         * @param string $event Name of the event.
         * @param string $function Optional function of the plugin to be called.
         */
        public function subscribe(iPlugin $plugin, $event, $function = null)
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
         * @param iPlugin $plugin Reference to the plugin being unsubscribed.
         * @param string $event Name of the event. Use '*', to unsubscribe all events for the plugin.
         * @param string $function Optional function of the plugin that was registered.
         */
        public function unsubscribe(iPlugin $plugin, $event)
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
         * @param PluginEvent $event Object holding all event properties
         * @param string|array $target Optional name of plugin to fire the event on
         * 
         * @return PluginEvent
         */
        public function dispatchEvent(PluginEvent $event, $target = array())
        {
            $eventName = $event->getEventName();
            if (is_string($target)) {
                $target = array($target);
            }
            if (isset($this->subscriptions[$eventName]))
            {
                foreach($this->subscriptions[$eventName] as $subscription)
                {
                    if (!$event->isStopped() 
                     && (empty($target) || in_array($subscription[0], $target))) 
                    {
                        call_user_func($subscription, $event);
                    }
                }
            }
            
            return $event;
        }

        /**
         * Scans the plugin directory for plugins.
         * This function is not efficient so should only be used in the admin interface
         * that specifically deals with enabling / disabling plugins.
         */
        public function scanPlugins()
        {
            $result = array();
            foreach (new DirectoryIterator(Yii::getPathOfAlias('webroot.plugins')) as $fileInfo)
            {
                if (!$fileInfo->isDot() && $fileInfo->isDir())
                {
                    // Check if the base plugin file exists.
                    // Directory name Example most contain file ExamplePlugin.php.
                    $pluginName = $fileInfo->getFilename();
                    $file = Yii::getPathOfAlias("webroot.plugins.$pluginName.{$pluginName}") . ".php";
                    if (file_exists($file))
                    {
                        $result[$pluginName] = $this->getPluginInfo($pluginName);
                    }
                }

            }
            return $result;
        }

        /**
         * Gets the description of a plugin. The description is accessed via a
         * static function inside the plugin file.
         *
         * @param string $pluginName
         */
        public function getPluginInfo($pluginName)
        {
            $result = array();
            Yii::import(App()->getConfig("plugindir") . ".$pluginName.*");
            $class = "{$pluginName}";
            $result['description'] = $class::getDescription();
            $result['name'] = $pluginName;
            return $result;
        }

        /**
         * Returns the instantiated plugin
         *
         * @param string $pluginName
         * @return iPlugin
         */
        public function loadPlugin($pluginName, $id)
        {
            if (!isset($this->_plugins[$id]) || get_class($this->_plugins[$id]) !== $pluginName) {
                Yii::import("webroot.plugins.{$pluginName}.{$pluginName}");
                $this->_plugins[$id] = new $pluginName($this, $id);
            }
            return $this->_plugins[$id];
        }

        /**
         * Handles loading all active plugins
         *
         * Possible improvement would be to load them for a specific context.
         * For instance 'survey' for runtime or 'admin' for backend. This needs
         * some thinking before implementing.
         */
        public function loadPlugins()
        {
            $plugins = array();
            try {
                $pluginModel = Plugin::model();    
                $records = $pluginModel->findAllByAttributes(array('active'=>1));
            
                foreach ($records as $record) {
                    $plugins[$record->id] = $record->name;
                }
            } catch (Exception $exc) {
                // Something went wrong, maybe no database was present so we load no plugins
            }
            foreach ($plugins as $id => $pluginName)
            {
                $this->loadPlugin($pluginName, $id);
            }
            
            $this->dispatchEvent(new PluginEvent('afterPluginLoad', $this));    // Alow plugins to do stuff after all plugins are loaded
        }
    }
?>
