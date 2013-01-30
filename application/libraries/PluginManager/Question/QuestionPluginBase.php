<?php


    abstract class QuestionPluginBase extends PluginBase{
        
        
        
        /**
         * Lists the question objects supported by the plugin.
         * Use dot notation for indicating subdirectories.
         * Example 1: 'subdirectory.questionobject'
         * Example 2: 'questionobject'
         * @var array of string
         */
        protected $questionTypes = array(
        );
        
        /**
         * 
         * @param PluginManager $pluginManager
         * @param string $id
         * @param int $responseId Pass a response id to load results.
         */
        
        public function __construct(PluginManager $manager, $id) {
            parent::__construct($manager, $id);
            $this->subscribe('listQuestionPlugins');
        }
        
        /**
         * @param PluginEvent $event
         */
        public function listQuestionPlugins(PluginEvent $event)
        {
            if (!empty($this->questionTypes))
            {
                $event->set('questionplugins.' . get_class($this), $this->questionTypes);
            }
        }
        
        /** 
         * Publishes plugin assets.
         */
        private function publish($fileName)
        {
            // Check if filename is relative.
            if (strpos('//', $fileName) === false)
            {
                // This is a limesurvey relative path.
                if (strpos('/', $fileName) === 0)
                {
                    $url = Yii::getPathOfAlias('webroot') . $fileName;
                    
                }
                else // This is a plugin relative path.
                {
                    $path = Yii::getPathOfAlias('webroot.plugins.' . get_class()) . $fileName;
                    /*
                     * By using the asset manager the assets are moved to a publicly accessible path.
                     * This approach allows a locked down plugin directory that is not publicly accessible.
                     */
                    $url = App()->assetManager->publish($path);
                }
            }
            else
            {
                $url = $fileName;
            }
            return $url;
        }
        
        /**
         * This function registers a javascript file to be included in the page.
         * $fileName can be either:
         * - Fully qualified url, will be used as is. (containing //)
         * - Limesurvey relative path, relative to limesurvey root. (starting with a single /)
         * - Local relative path, will be used as path relative inside the plugins' path.
         * - 
         * @param string $fileName
         */
        protected function registerJs($fileName)
        {
            App()->getClientScript()->registerScriptFile($this->publish($fileName));
            
        }
                
        protected function registerCss($fileName)
        {
            App()->getClientScript()->registerCssFile($this->publish($fileName));
        }
        
       
        
        
    }
?>
