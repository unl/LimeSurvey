<?php
/* @var $this ConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>$gridColumns
));
?>
<pre>
<?php
var_dump($discoveredPlugins);
var_dump($registeredPlugins);
?>
</pre>