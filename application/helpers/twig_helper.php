<?php

    class Twig {
        private static $initialized = false;
        
        
        /**
         *
         * @var Twig_Environment
         */
        private static $environment;
        
        /**
         * @return Twig_Environment
         */
        public static function getTwigEnvironment($options = array())
        {
            // If debugging dont use cache and enable twig debug.
            if (true || App()->getConfig('debug') > 0)
            {
                $options['debug'] = true;
                $options['cache'] = false;
            }
            else
            {
                $options['cache'] = App()->getConfig('twigdir');
            }
            
            
            if (!self::$initialized)
            {
                Yii::import('application.libraries.Twig.Autoloader', true);
                spl_autoload_unregister(array('YiiBase','autoload'));
                Twig_Autoloader::register();
                spl_autoload_register(array('YiiBase','autoload'));
                self::$initialized = true;
            }
            
            if (!isset(self::$environment))
            {
                $loader = new Twig_Loader_Filesystem(array(
                    App()->getConfig('standardtemplaterootdir'),
                    App()->getConfig('usertemplaterootdir')
                ));
                $twig = new Twig_Environment($loader, $options);
                
                // Make language data available.
                foreach (getLanguageData() as $code => $data)
                {
                    $direction[$code] = $data['rtl'] ? 'rtl' : 'ltr';
                }
                $twig->addGlobal('direction', $direction);
                
                // Register custom functions.
                $twig->addFunction(new Twig_SimpleFunction('trans', 'Twig::gT'));
                $twig->addFunction(new Twig_SimpleFunction('ntrans', 'Twig::ngT'));
                $twig->addFunction(new Twig_SimpleFunction('em', 'Twig::em'));
                
                
                self::$environment = $twig;
            }
            return self::$environment;
        }
        
        public static function ngt($singular, $plural, $count)
        {
            
            // First translate using proper translation string.
            $translated = ngT($singular, $plural, $count);
            // Then apply printf.
            $args = func_get_args();
            array_shift($args);
            array_shift($args);
            return vsprintf($translated, $args);
        }
        
        public static function gT($txt)
        {
            // First translate text.
            $translated = gT($txt);
            
            // Then apply printf.
            if (func_num_args() > 1)
            {
                $args = func_get_args();
                array_shift($args);
                return vsprintf($translated, $args);
            }
            else
            {
                return $translated;
            }
        }
        
        public static function em($txt)
        {
            
            return LimeExpressionManager::ProcessString($txt);
        }
        
        /**
         * - Adds the path of a template to the auto loader.
         * - Sets the global variable template.url to the url of the template.
         * @param string $template Name of the template to activate.
         */
        public static function activateTemplate($template)
        {
            self::getTwigEnvironment()->getLoader()->addPath(getTemplatePath($template));
            self::getTwigEnvironment()->addGlobal('template', array('url' => getTemplateURL($template), 'name' => $template));
            
        }
    }
?>