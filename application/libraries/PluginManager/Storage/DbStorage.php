<?php

class DbStorage implements iPluginStorage {

    protected $model = null;

    public function __construct() {
        $this->model = PluginSetting::model();
    }

    /**
     * 
     * @param iPlugin $plugin
     * @param string $key
     * @param string $model
     * @param int $id
     * @return mixed Returns the value from the database or null if not set.
     */
    function get($plugin, $key = null, $model = null, $id = null, $default = null) {
        $attributes = array(
            'plugin_id' => $plugin->getId(),
            'model'     => $model,
            'model_id'  => $id,
            'key'       => $key);
        $record = $this->model->findByAttributes($attributes);
        if (!is_null($record)) {
            return unserialize($record->value);
        } else {
            return $default;
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
            'key'       => $key
        );
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