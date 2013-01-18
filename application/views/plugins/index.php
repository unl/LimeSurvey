<?php
/* @var $this ConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('application.extensions.GridViewWidget', array(
    'dataProvider'=>$dataProvider,
    'columns'=>$gridColumns
));
?>