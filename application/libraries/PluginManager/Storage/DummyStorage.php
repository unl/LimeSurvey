<?php


    class DummyStorage implements iPluginStorage
    {
        /**
         * Always fail to get.
         */
        public function get($plugin, $key = null, $model = null, $id = null, $default = null) {
            return false;
        }
        
        /**
         * Always fail to save.
         */
        public function set($plugin, $key, $data, $model = null, $id = null) {
            echo "DummyStorage::set('" . get_class($plugin) . "', '$key', " . serialize($data) . ", '$model', '$id')<br>";
            return false;
        }
        
        
        public function __construct() {
            
        }
    }
?>
