<?php


    abstract class QuestionPluginBase extends PluginBase implements iQuestionPlugin {
        
        
        /**
         * 
         * @param PluginManager $pluginManager
         * @param string $id
         * @param bool $live True if we plan to display the question, false during administration.
         */
        
        public function __construct(PluginManager $pluginManager, $id, $live = false) {
            parent::__construct($pluginManager, $id);
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
                
        protected function registerCss($filenName)
        {
            App()->getClientScript()->registerCssFile($this->publish($fileName));
        }
        
    }
?>
