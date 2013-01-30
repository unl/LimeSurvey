<?php


    class DummyStorage implements iPluginStorage
    {
        /**
         * Always fail to get.
         */
        public function get($plugin, $key = null, $model = null, $id = null) {
            return false;
        }
        
        /**
         * Always fail to save.
         */
        public function set($plugin, $key, $data, $model = null, $id = null) {
            return false;
        }
        
        
        public function __construct() {
            
        }
    }
?>
