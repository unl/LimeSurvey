<?php
interface iPlugin {

    /**
     * Should return the description for this plugin
     */
    public static function getDescription();
    
    /**
     * Get the id of this plugin (set by PluginManager on instantiation)
     * 
     * @return int
     */
    public function getId();
    
    
    /**
     * 
     */
    public function __construct(PluginManager $pluginManager, $id);
}