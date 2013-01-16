<?php 

/** 
 * @var MenuWidget;
 */
$this;


App()->getClientScript()->registerCssFile(App()->getConfig('adminstyleurl') .  'nav.css');

function renderSelect($item)
{
    echo CHtml::label($item['title'],  'surveylist');
    echo CHtml::dropDownList('surveylist', null, CHtml::listData($item['values'], 'sid', 'surveyls_title'), array(
        'id' => 'surveylist'
    ));
}
function renderItem($item, &$allowSeparator, MenuWidget $widget, $imageUrl)
{
    
    if (is_array($item))
    {
        $allowSeparator = true;
        echo CHtml::openTag('li');
        $item = array_merge($widget->defaults, $item);
        if ($item['type'] == 'link')
        {
            $title = $item['title'];
            if (isset($item['image']))
            {
                $title .= $widget->gT($item['title']) . CHtml::image($imageUrl . $item['image'], $widget->gT($item['alt']));
            }
            echo CHtml::link($title, $item['href']);
        }
        elseif ($item['type'] == 'image')
        {
            if (isset($item['image']))
            {
                echo CHtml::image($imageUrl . $item['image'], $item['alt']);
            }
        }
        elseif($item['type'] == 'select')
        {
            renderSelect($item);
            
            
        }
        echo CHtml::closeTag('li');
    }
    elseif (is_string($item) && $item == 'separator' && $allowSeparator)
    {
        echo CHtml::openTag('li');
        echo CHtml::image($imageUrl . 'separator.gif');
        $allowSeparator = false;
        echo CHtml::closeTag('li');
    }
    
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
                    renderItem($item, $allowSeparator, $this, $imageUrl);
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
