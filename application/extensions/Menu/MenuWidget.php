<?php

    class MenuWidget extends CWidget
    {
        public $defaults = array(
            'title' => '',
            'alt' => '',
            'type' => 'link'
        );
        
        public function run()
        {
            $imageUrl = Yii::app()->getConfig('adminimageurl');
            $menu['left'][] = array(
                'href' => array('admin/survey'),
                'image' => 'home.png',
            );
            $menu['left'][] = 'separator';
            $menu['left'][] = array(
                'href' => array('admin/user'),
                'alt' => "Manage survey administrators",
                'image' => 'security.png',
            );
            $menu['left'][] = array(
                'href' => array('admin/usergroups/sa/index'),
                'alt' => 'Create/edit user groups',
                'image' => 'usergroup.png'
            );

            $menu['left'][] = $this->globalSettings();
            $menu['left'][] = 'separator';
            $menu['left'][] = $this->checkIntegrity();
            $menu['left'][] = $this->dumpDatabase();
            $menu['left'][] = $this->editLabels();
            $menu['left'][] = 'separator';
            $menu['left'][] = $this->editTemplates();
            $menu['left'][] = 'separator';
            $menu['left'][] = $this->participantDatabase();

            $menu['right'][] = array(
                'title' => 'Surveys:',
                'type' => 'select',
                'values' => getSurveyList(true)
            );
            $menu['right'][] = array(
                'href' => array('admin/surveys'),
                'alt' => 'Detailed list of surveys',
                'image' => 'surveylist.png'
            );
            
            $menu['right'][] = $this->createSurvey();
            $menu['right'][] = 'separator';

            $menu['right'][] = array(
                'href' => array('admin/authentication/sa/logout'),
                'alt' => 'Logout',
                'image' => 'logout.png'
            );
            $menu['right'][] = array(
                'href' => array('admin/user/sa/personalsettings'),
                'alt' => 'Edit your personal preferences',
                'image' => 'edit.png'
            );
            
            
            $menu['right'][] = array(
                'href' => "http://docs.limesurvey.org",
                'alt' => 'LimeSurvey online manual',
                'image' => 'showhelp.png'
            );
            
            $this->render('adminmenu', compact('menu', 'imageUrl'));
        }

        protected function globalSettings()
        {
            if ($this->hasRight('USER_RIGHT_CONFIGURATOR'))
            {
                return array(
                    'href' => 'admin/globalsettings',
                    'image' => 'global.png',
                    'alt' => 'Global Settings'
                );
            }
        }

        protected function checkIntegrity()
        {
            if ($this->hasRight('USER_RIGHT_CONFIGURATOR'))
            {
                return array(
                    'href' => 'admin/checkintegrity',
                    'image' => 'checkdb.png',
                    'alt' => 'Check Data Integrity'
                );
            }
        }

        
        protected function createSurvey()
        {
            if ($this->hasRight('USER_RIGHT_CREATE_SURVEY'))
            {
                return array(
                    'href' => array("admin/survey/sa/newsurvey"),
                    'image' => 'add.png',
                    'alt' => "Create, import, or copy a survey"
                );
            }
        }
        protected function dumpDatabase()
        {
            if ($this->hasRight('USER_RIGHT_SUPERADMIN'))
            {
                if (in_array(Yii::app()->db->getDriverName(), array('mysql', 'mysqli')) || Yii::app()->getConfig('demo_mode') == true)
                {
                    return array(
                        'image' => 'backup.png',
                        'href' => array("admin/dumpdb"),
                        'alt' => 'Backup Entire Database'
                    );
                }
                else
                {
                    return array(
                        'image' => 'backup_disabled.png',
                        'alt' => 'The database export is only available for MySQL databases. For other database types please use the according backup mechanism to create a database dump.',
                        'type' => 'image'
                    );
                }
            }
        }

        protected function editLabels()
        {
            if ($this->hasRight("USER_RIGHT_MANAGE_LABEL"))
            {
                return array(
                    'href' => array('admin/labels'),
                    'image' => 'labels.png',
                    'alt' => 'Edit label sets'
                );
            }
        }

        protected function editTemplates()
        {
            if ($this->hasRight('USER_RIGHT_MANAGE_TEMPLATE'))
            {
                return array(
                    'href' => array('admin/templates/'),
                    'alt' => 'Template Editor',
                    'image' => 'templates.png'
                );
            }
        }

        protected function participantDatabase()
        {
            if ($this->hasRight('USER_RIGHT_PARTICIPANT_PANEL'))
            {
                return array(
                    'alt' => 'Central participant database/panel',
                    'href' => array('admin/participants'),
                    'image' => 'cpdb.png'
                 );
            }
        }
        protected function eT($msg)
        {
            echo $this->gT($msg);
        }

        /**
         * Wrapper function for localization.
         * This way we dont need the language object in the view and
         * can run the widget even without a language object.
         * @param string $msg
         */
        public function gT($msg)
        {
            if (isset($this->clang) && method_exists($this->clang, 'gT'))
            {
                return $this->clang->gT($msg);
            }
            else
            {
                return $msg;
            }
        }

        /**
         * Function to check for rights for the current user.
         * Currently these rights are stored in the session directly. Since
         * this is bad practice this function is created to easily refactor
         * changing in the way rights are checked.
         * 
         * @param type $right
         */
        protected function hasRight($right)
        {
            return (Yii::app()->session[$right] == 1);
        }
    }

?>
