<?php
require_once dirname(__FILE__) . '/../../../framework/yiit.php';

require_once Yii::app()->basePath . 'core/LSYii_Application.php';

define('EXT', '.php');

// Fix for phpunit => 3.7.7
require_once('PHPUnit/Runner/Version.php');
if (version_compare('3.7.7', PHPUnit_Runner_Version::VERSION)<=0) {
    function phpunit_autoload($class)
    {        
    }
    spl_autoload_register('phpunit_autoload');
}

yii::createApplication('LSYii_Application', Yii::app()->basePath . 'config/config-sample.php');