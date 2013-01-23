<?php
class PluginEvent
{
    /**
     * The name of this event
     * 
     * @var string 
     */
    protected $_event = '';
    
    /**
     * The class who fired the event, or null when not set
     * 
     * @var object 
     */
    protected $_sender = null;
    
    /**
     * When true it prevents delegating the event to other plugins.
     * 
     * @var boolean 
     */
    protected $_stop = false;
    
    /**
     * Internal storage for event data. Can be used to communicate between sender
     * and plugin or between different plugins handling the event.
     * 
     * @var array 
     */
    protected $_parameters = array();
    
    /**
     * Constructor for the PluginEvent
     * 
     * @param string $event    Name of the event fired 
     * @param object $sender   The object sending the event
     * @return \PluginEvent
     */
    public function __construct($event, $sender = null)
    {
        if (!is_null($sender) && is_object($sender))
        {
            $this->_sender = $sender;
        }
        
        $this->_event = $event;
        
        return $this;
    }
    
    /**
     * Get a value for the given key. 
     * 
     * When the value is not set, it will return the given default or null when
     * no default was given.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->_parameters))
        {
            return $default;
        } else {
            return $this->_parameters[$key];
        }
    }
    
    /**
     * Return the name of the event
     * 
     * @return string
     */
    public function getEventName()
    {
        return $this->_event;
    }
    
    /**
     * Return the sender of the event
     * 
     * Normally the class that fired the event, but can return false when not set.
     * 
     * @return object The object sending the event, or false when unknown
     */
    public function getSender()
    {
        if (!is_null($this->_sender)) {
            return $this->_sender;
        } else {
            return false;
        }
    }
    
    /**
     * Set a key/value pair to be used by plugins hanlding this event.
     * 
     * @param string $key
     * @param mixed $value
     * @return \PluginEvent Fluent interface
     */
    public function set($key, $value)
    {
        $this->_parameters[$key] = $value;
        
        return $this;
    }
    
    /**
     * Returns true when event is stopped by one of the plugins
     * 
     * When a plugin needs to stop execution of the event by other plguins listening
     * to the same event, the plugin can call this method with value true. The 
     * PluginManager will no longer hand this event to plugins when this returns 
     * true.
     * 
     * @param boolean $bool
     * @return boolean 
     */
    public function stop()
    {
        $this->_stop = true;
    }
    
    public function isStopped()
    {
        return $this->_stop;
    }
}