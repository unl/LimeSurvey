<nav class="menubar">
    <div class='menubar-main'>
        <div class='menubar-left'>
        <?php
            echo CHtml::openTag('ol');
            $defaults = array(
                'title' => '',
                'alt' => '',
                'type' => 'link'
            );
            
            $allowSeparator = false;
            foreach($menu['left'] as $item)
            {
                if (is_array($item))
                {
                    $allowSeparator = true;
                    $item = array_merge($defaults, $item);
                    if ($item['type'] == 'link')
                    {
                        $title = $item['title'];
                        if (isset($item['image']))
                        {
                            $title .= $item['title'] . CHtml::image($imageUrl . $item['image'], $item['alt']);
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
                }
                elseif (is_string($item) && $item == 'separator' && $allowSeparator)
                {
                    echo CHtml::image($imageUrl . 'separator.gif');
                    $allowSeparator = false;
                }
            }
            echo CHtml::closeTag('ol')
        ?>
        </div>
        <div class='menubar-right'>
        </div>
    </div>

</nav>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
