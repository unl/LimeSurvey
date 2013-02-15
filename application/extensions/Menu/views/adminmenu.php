<?php 
    /* @var $this MenuWidget */

    App()->getClientScript()->registerCssFile(App()->getConfig('adminstyleurl') .  'nav.css');

    echo CHtml::tag('div', array(
        'class' => 'titlebar',
        'id' => 'title-' . $menu['role']
    ), $menu['title']);
?>
<nav class="menubar">
    <?php 
        if (isset($menu['items']))
        {
            echo $this->renderMenu($menu);
        }
    ?>
</nav>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
