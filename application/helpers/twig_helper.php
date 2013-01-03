<?php

    class Twig {
        private static $initialized = false;
        
        private static $environments = array();
        
        /**
         * @return Twig_Environment
         */
        public static function getTwigEnvironment($options = array(), $reference = 'default')
        {
            if (!isset($options['cache']))
            {
                $options['cache'] = App()->getConfig('twigdir');
            }
            $options['debug'] = App()->getConfig('debug') > 0;
            if (!self::$initialized)
            {
                Yii::import('application.libraries.Twig.Autoloader', true);
                spl_autoload_unregister(array('YiiBase','autoload'));
                Twig_Autoloader::register();
                spl_autoload_register(array('YiiBase','autoload'));
                self::$initialized = true;
            }
            if (!isset(self::$environments[$reference]))
            {
                $loader = new Twig_Loader_Filesystem(array(
                    App()->getConfig('standardtemplaterootdir'),
                    App()->getConfig('usertemplaterootdir')
                ));
                self::$environments[$reference] = new Twig_Environment($loader, $options);
            }
            return self::$environments[$reference];
        }
        
    }
?>