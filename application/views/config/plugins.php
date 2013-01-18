<?php
/* @var $this ConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>$gridColumns
));
?>