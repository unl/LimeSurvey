<?php
/* @var $this ConfigController */
/* @var $dataProvider CActiveDataProvider */

var_dump($discoveredPlugins);
$this->widget('application.extensions.GridViewWidget', array(
    'dataProvider'=>$dataProvider,
    'columns'=>$gridColumns
));
?>