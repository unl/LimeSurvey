<?php

    class PluginSettingsHelper 
    {
        
        public function renderSetting($name, array $metaData, $form = null, $return = false)
        {
            if (isset($metaData['type']))
            {
                $function = "render{$metaData['type']}";
                $result = $this->$function($name, $metaData, $form);
                if ($return)
                {
                    return $result;
                }
                else
                {
                    echo $result;
                }
            }
        }
        
        
        public function renderString($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::textField($id, $value, array('id' => $id, 'form' => $form));
            
            return $out;
        }
        
        
        public function renderBoolean($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::radioButtonList($id, $value, array(
                0 => 'False',
                1 => 'True'
            ), array('id' => $id, 'form' => $form));
            
            
            return $out;
        }
    }

?>
