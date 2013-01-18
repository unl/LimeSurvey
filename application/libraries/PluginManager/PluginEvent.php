<?php
class PluginEvent
{
    protected $_event = '';
    
    protected $_sender;
    
    protected $_stop = false;
    
    protected $_parameters = array();
    
    /**
     * 
     * @param string $event
     * @param objeect $sender
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
    
    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->_parameters))
        {
            return $default;
        } else {
            return $this->_parameters[$key];
        }
    }
    
    public function getEventName()
    {
        return $this->_event;
    }
    
    public function getSender()
    {
        if (!is_null($this->_sender)) {
            return $this->_sender;
        } else {
            return false;
        }
    }
    
    public function set($key, $value)
    {
        $this->_parameters[$key] = $value;
        
        return $this;
    }
    
    public function stop($bool = null)
    {
        if (!is_null($bool)) {
            $this->_stop = (bool) $bool;
        }
        
        return (bool) $this->_stop;
    }
}