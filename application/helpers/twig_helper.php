<?php

    class Twig {
        private static $initialized = false;
        
        
        /**
         *
         * @var Twig_Environment
         */
        private static $environment;
        
        /**
         *
         * @var Limesurvey_lang
         */
        private static $translator = null;
        /**
         * @return Twig_Environment
         */
        public static function getTwigEnvironment($options = array())
        {
            // If debugging dont use cache and enable twig debug.
            if (App()->getConfig('debug') > 0)
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
                Yii::import('application.libraries.Limesurvey_lang');
                spl_autoload_unregister(array('YiiBase','autoload'));
                Twig_Autoloader::register();
                spl_autoload_register(array('YiiBase','autoload'));
                
                self::$translator = new Limesurvey_lang($options['language']);
                self::$initialized = true;
            }
            if (!isset(self::$environment))
            {
                $loader = new Twig_Loader_Filesystem(array(
                    App()->getConfig('standardtemplaterootdir'),
                    App()->getConfig('usertemplaterootdir')
                ));
                $twig = new Twig_Environment($loader, $options);
                $twig->addFunction(new Twig_SimpleFunction('trans', 'Twig::gT'));
                $twig->addFunction(new Twig_SimpleFunction('em', 'Twig::em'));
                
                //$twig->addExtension(new Twig_Extensions_Extension_I18n());
                self::$environment = $twig;
                
            }
            return self::$environment;
        }
        
        public static function gT($txt)
        {
            return self::$translator->gT($txt);
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