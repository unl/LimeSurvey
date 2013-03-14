<?php 
    class PublicList extends LSYii_Action
    {
        public function run()
        {
            $surveys = Survey::model()->findAllByAttributes(array(
           //     'active' => 'Y',
                'listpublic' => 'Y'
            ));
            debug('Public survey list here.');
        }
    }
?>