<?php

    class PluginSettingsHelper 
    {
        
        public function renderSetting($name, array $metaData, $form = null, $return = false)
        {
            if (isset($metaData['type']))
            {
                $function = "render{$metaData['type']}";
                if (isset($metaData['localized']) && $metaData['localized'] == true)
                {
                    $name = "{$name}_{$metaData['language']}";
                }
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
            ), array('id' => $id, 'form' => $form, 'container'=>'div', 'separator' => ''));
            
            
            return $out;
        }
        
        public function renderFloat($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::textField($id, $value, array(
                'id' => $id, 
                'form' => $form,
                'pattern' => '\d+(\.\d+)?'
            ));
            
            return $out;
        }
        
        public function renderHtml($name, array $metaData, $form = null)
        {
            return $this->renderString($name, $metaData, $form);
        }
        public function renderInt($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::textField($id, $value, array(
                'id' => $id, 
                'form' => $form,
                'pattern' => '\d+'
            ));
            
            return $out;
        }
        
        public function renderRelevance($name, array $metaData, $form = null)
        {
            $out = '';
            $metaData['class'] = 'relevance';
            $id = $name;
            $class = isset($metaData['class']) ? $metaData['class'] : '';
            
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            
            $out .= CHtml::textArea($name, $value, array('id' => $id, 'form' => $form, 'class' => $class));
            
            return $out;
        }
        
        public function renderSelect($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::dropDownList($name, $metaData['current'], $metaData['options']);
            
            return $out;
        }
        
        public function renderString($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            $class = isset($metaData['class']) ? $metaData['class'] : '';
            $readOnly = isset($metaData['readOnly']) ? $metaData['readOnly'] : false;
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::textField($id, $value, array('id' => $id, 'form' => $form, 'class' => $class, 'readonly' => $readOnly));
            
            return $out;
        }
        
        public function renderPassword($name, array $metaData, $form = null)
        {
            $out = '';
            $id = $name;
            $value = isset($metaData['current']) ? $metaData['current'] : '';
            if (isset($metaData['label']))
            {
                $out .= CHtml::label($metaData['label'], $id);
            }
            $out .= CHtml::passwordField($id, $value, array('id' => $id, 'form' => $form));
            
            return $out;
        }
        
                
    }
    
    

?>
