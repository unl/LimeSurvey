<?php

    class MenuWidget extends CWidget
    {
        /**
         * @var Limesurvey_lang
         */
        public $clang = null;
        
        public $menu = array();
        
        public function __construct($owner = null) {
            parent::__construct($owner);
            Yii::import('application.helpers.surveytranslator_helper', true);
            $this->clang = App()->lang;
        }
        public $defaults = array(
            'title' => '',
            'alt' => '',
            'type' => 'link'
        );
        
        public $surveyId = null;
        public $groupId = null;
        
        public function run()
        {
            $this->render('adminmenu', array('menu' => $this->menuMain()));
            if (isset($this->surveyId))
            {
                $this->render('adminmenu', array('menu' => $this->menuSurvey($this->surveyId)));
            }
        }

        
        
        protected function menuMain()
        {
            $menu['title'] = App()->getConfig('sitename');
            $menu['role'] = 'main';
            $menu['imageUrl'] = App()->getConfig('adminimageurl');
            $menu['items']['left'][] = array(
                'href' => array('admin/survey'),
                'image' => 'home.png',
            );
            $menu['items']['left'][] = 'separator';
            $menu['items']['left'][] = array(
                'href' => array('admin/user'),
                'alt' => $this->gT('Manage survey administrators'),
                'image' => 'security.png',
            );
            $menu['items']['left'][] = array(
                'href' => array('admin/usergroups/sa/index'),
                'alt' => $this->gT('Create/edit user groups'),
                'image' => 'usergroup.png'
            );

            $menu['items']['left'][] = $this->globalSettings();
            $menu['items']['left'][] = 'separator';
            $menu['items']['left'][] = $this->checkIntegrity();
            $menu['items']['left'][] = $this->dumpDatabase();
            $menu['items']['left'][] = $this->editLabels();
            $menu['items']['left'][] = 'separator';
            $menu['items']['left'][] = $this->editTemplates();
            $menu['items']['left'][] = 'separator';
            $menu['items']['left'][] = $this->participantDatabase();
            $menu['items']['left'][] = array(
                'href' => array('/plugins'),
                'alt' => $this->gT('Plugin manager'),
                'image' => 'share.png'
            );

            $surveys = getSurveyList(true);
            $surveyList = array();
            foreach ($surveys as $survey)
            {
                $surveyList[] = array(
                    'sid' => $survey['sid'],
                    'title' => $survey['surveyls_title'],
                    'active' => $survey['active'] == 'Y'
                );
            }
            $menu['items']['right'][] = array(
                'title' => 'Surveys:',
                'type' => 'select',
                'values' => $surveyList
            );
            $menu['items']['right'][] = array(
                'href' => array('admin/survey/sa/index'),
                'alt' => $this->gT('Detailed list of surveys'),
                'image' => 'surveylist.png'
            );
            
            $menu['items']['right'][] = $this->createSurvey();
            $menu['items']['right'][] = 'separator';

            
            $menu['items']['right'][] = array(
                'href' => array('admin/user/sa/personalsettings'),
                'alt' => $this->gT('Edit your personal preferences'),
                'image' => 'edit.png'
            );
            $menu['items']['right'][] = array(
                'href' => array('admin/authentication/sa/logout'),
                'alt' => $this->gT('Logout'),
                'image' => 'logout.png'
            );
            
            $menu['items']['right'][] = array(
                'href' => "http://docs.limesurvey.org",
                'alt' => $this->gT('LimeSurvey online manual'),
                'image' => 'showhelp.png'
            );

            $event = new PluginEvent('afterAdminMenuLoaded', $this);
            $event->set('menu', $menu);
            
            $result = App()->getPluginManager()->dispatchEvent($event);
            
            $menu = $result->get('menu');
            return $menu;
        }

        protected function menuSurvey($surveyId)
        {
            /**
             * @todo Remove direct session access.
             * @todo Remove admin specific setting; language is a property of any session.
             */
            $surveyInfo = getSurveyInfo($surveyId, Yii::app()->session['adminlang']);
            $menu['title'] = "Survey {$surveyInfo['surveyls_title']} (id: {$surveyId})";
            $menu['role'] = 'survey';
            $menu['imageUrl'] = App()->getConfig('adminimageurl');
            
            if ($surveyInfo['active'] == 'Y')
            {
                $menu['items']['left'][] = array(
                    'type' => 'image',
                    'image' => 'active.png',
                );
                /**
                 * @todo Get request changes state.
                 */
                $menu['items']['left'][] = array(
                    'type' => 'image',
                    'image' => 'deactivate.png',
                );
            }
            else
            {
                $menu['items']['left'][] = array(
                    'type' => 'image',
                    'image' => 'inactive.png',
                );
                $menu['items']['left'][] = array(
                    'href' => array('admin/survey', 'sa' => 'activate', 'surveyid' => $surveyId),
                    'image' => 'activate.png',
                );
                
            }
            $menu['items']['left'][] = 'separator';
            $languages = array($surveyInfo['language']);
            if (isset($surveyInfo['additional_languages']))
            {
                $languages = array_merge($languages, array_filter(explode(' ', $surveyInfo['additional_languages'])));
            }
            foreach ($languages as $language)
            {
                $subitems[] = array(
                    'type' => 'link',
                    'title' => getLanguageNameFromCode($language, false),
                    'image' => 'do_30.png',
                    'href' => array('survey/index', 'sid' => $surveyId, 'newtest' => 'Y', 'lang' => $language)
                );
            }
            $menu['items']['left'][] = array(
                'type' => 'sub',
                'href' => array('survey/index', 'sid' => $surveyId, 'newtest' => 'Y'),
                'image' => 'do.png',
                'items' => array(
                    array(
                        'type' => 'sub',
                        'items' => $subitems,
                        'href' => array('survey/index', 'sid' => $surveyId, 'newtest' => 'Y'),
                        'title' => $this->gt('Test this survey'),
                        'image' => 'do_30.png'
                    )
                )
            );
            
            
                
            
            
            return $menu;
        }
        
        
        
        protected function renderItem($item, &$allowSeparator, $imageUrl, $level = 0)
        {
            $result = '';
            if (is_array($item))
            {
                $allowSeparator = true;
                if (isset($item['image']))
                {
                    $result .= CHtml::image($imageUrl . $item['image'], isset($item['alt']) ? $item['alt'] : '');
                }
                if (isset($item['title']))
                {
                    $result .= $item['title'];
                }
                
                if(isset($item['values']))
                {
                    
                    $result = $this->renderSelect($item);
                }
                
                if(isset($item['items']))
                {
                    $result = $this->renderSub($item, $imageUrl, $level + 1);
                }
                
                
                if (isset($item['href']))
                {
                    $result = CHtml::link($result, $item['href']);
                }
            }
            elseif (is_string($item) && $item == 'separator' && $allowSeparator)
            {
                $result = CHtml::image($imageUrl . 'separator.gif');
                $allowSeparator = false;
            }

            
            return CHtml::tag('li', array(), $result);
        }
        
        protected function renderMenu($menu)
        {
            foreach ($menu['items'] as $class => $menuItems)
            {
                echo CHtml::openTag('ol', array('class' => "menubar-$class level0"));
                $allowSeparator = false;
                foreach($menuItems as $item)
                {
                    echo $this->renderItem($item, $allowSeparator, $menu['imageUrl']);
                }
                echo CHtml::closeTag('ol');

            }
        }
        
        protected function renderSelect($item)
        {
            $result = CHtml::label($item['title'],  'surveylist');
            $result .= CHtml::dropDownList('surveylist', null, CHtml::listData($item['values'], 'sid', 'title'), array(
                'id' => 'surveylist'
            ));
            
            return $result;
        }
        
        protected function renderSub($item, $imageUrl, $level)
        {
            $result = '';
            if (isset($item['image']))
            {
                $result .= CHtml::image($imageUrl . $item['image']);
            }
            if (isset($item['title']))
            {
                $result .= $item['title'];
            }
            if (isset($item['href']))    
            {
                $result = CHtml::link($result, $item['href']);
            }
            
            $result .= CHtml::openTag('ol', array('class' => "level$level"));
            
            foreach ($item['items'] as $subItem)
            {
                $allowSeparator = false;
                $result .= $this->renderItem($subItem, $allowSeparator, $imageUrl, $level);
            }
            $result .= CHtml::closeTag('ol');
            return $result;
        }
        
        protected function globalSettings()
        {
            if ($this->hasRight('USER_RIGHT_CONFIGURATOR'))
            {
                return array(
                    'href' => array('admin/globalsettings'),
                    'image' => 'global.png',
                    'alt' => $this->gT('Global Settings')
                );
            }
        }

        protected function checkIntegrity()
        {
            if ($this->hasRight('USER_RIGHT_CONFIGURATOR'))
            {
                return array(
                    'href' => array('admin/checkintegrity'),
                    'image' => 'checkdb.png',
                    'alt' => $this->gT('Check Data Integrity')
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
                    'alt' => $this->gT('Create, import, or copy a survey')
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
                        'alt' => $this->gT('Backup Entire Database')
                    );
                }
                else
                {
                    return array(
                        'image' => 'backup_disabled.png',
                        'alt' => $this->gT('The database export is only available for MySQL databases. For other database types please use the according backup mechanism to create a database dump.'),
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
                    'alt' => $this->gT('Edit label sets')
                );
            }
        }

        protected function editTemplates()
        {
            if ($this->hasRight('USER_RIGHT_MANAGE_TEMPLATE'))
            {
                return array(
                    'href' => array('admin/templates/'),
                    'alt' => $this->gT('Template Editor'),
                    'image' => 'templates.png'
                );
            }
        }

        protected function participantDatabase()
        {
            if ($this->hasRight('USER_RIGHT_PARTICIPANT_PANEL'))
            {
                return array(
                    'alt' => $this->gT('Central participant database/panel'),
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
