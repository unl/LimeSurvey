<?php
interface iPlugin {

    /**
     * Shoudl return the description for this plugin
     */
    public static function getDescription();
    
    /**
     * Get the id of this plugin (set by PluginManager on instantiation)
     * 
     * @return int
     */
    public function getId();
}