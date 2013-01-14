<?php

class DbStorage implements iPluginStorage {

    protected $model = null;

    public function __construct() {
        $this->model = PluginSettings::model();
    }

    /**
     * 
     * @param iPlugin $plugin
     * @param type $key
     * @param type $model
     * @param type $id
     */
    function get($plugin, $key = null, $model = null, $id = null) {
        $attributes = array(
            'plugin_id' => $plugin->getId(),
            'model'     => $model,
            'model_id'  => $id,
            'key'       => $key);
        $record = $this->model->findByAttributes($attributes);
        
        if (!is_null($record)) {
            return unserialize($record->value);
        } else {
            return false;
        }        
    }

    /**
     * 
     * @param iPlugin $plugin
     * @param type $key
     * @param type $data
     * @param type $model
     * @param type $id
     * 
     * @return boolean
     */
    public function set($plugin, $key, $data, $model = null, $id = null) {
        $attributes = array(
            'plugin_id' => $plugin->getId(),
            'model'     => $model,
            'model_id'  => $id,
            'key'       => $key);
        $record = $this->model->findByAttributes($attributes);
        if (is_null($record)) {
            // New setting
            $record = $this->model->populateRecord($attributes);
            $record->setIsNewRecord(true);
        } 
        
        $record->value = serialize($data);              
        $result = $record->save();
               
        return $result;
    }

}