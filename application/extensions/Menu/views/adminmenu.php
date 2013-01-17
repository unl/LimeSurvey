<?php 
/* @var $this MenuWidget */

App()->getClientScript()->registerCssFile(App()->getConfig('adminstyleurl') .  'nav.css');

function renderSelect($item)
{
    $result = CHtml::label($item['title'],  'surveylist');
    $result .= CHtml::dropDownList('surveylist', null, CHtml::listData($item['values'], 'sid', 'surveyls_title'), array(
        'id' => 'surveylist'
    ));
    
    return $result;
}
function renderItem($item, &$allowSeparator, MenuWidget $widget, $imageUrl)
{
    $result = false;
    if (is_array($item))
    {
        $allowSeparator = true;
        $item = array_merge($widget->defaults, $item);
        if ($item['type'] == 'link')
        {
            $title = $item['title'];
            if (isset($item['image']))
            {
                $title .= $item['title'] . CHtml::image($imageUrl . $item['image'], $item['alt']);
            }
            $result = CHtml::link($title, $item['href']);
        }
        elseif ($item['type'] == 'image')
        {
            if (isset($item['image']))
            {
                $result = CHtml::image($imageUrl . $item['image'], $item['alt']);
            }
        }
        elseif($item['type'] == 'select')
        {
            $result = renderSelect($item);
            
            
        }
    }
    elseif (is_string($item) && $item == 'separator' && $allowSeparator)
    {
        $result = CHtml::image($imageUrl . 'separator.gif');
        $allowSeparator = false;
    }
    
    return $result;
}

?>
<nav class="menubar">
    <div class='menubar-main'>
        <?php 
            foreach ($menu as $class => $menuItems)
            {
                echo CHtml::openTag('ol', array('class' => "menubar-$class"));
                $allowSeparator = false;
                foreach($menuItems as $item)
                {
                    if ($content = renderItem($item, $allowSeparator, $this, $imageUrl))
                    {
                        echo CHtml::tag('li', array(), $content);
                    }
                }
                echo CHtml::closeTag('ol');
                
            }
        ?>
        
    </div>

</nav>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
