<h1>Surveys:</h1>
<?php 
    
    $dataProvider = new CArrayDataProvider($overview, array(
        'keyField' => 'sid'
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProvider,
        'columns' => array(
            'active:boolean',
            'sid',
            array(
                'labelExpression' => function($row, $data) { return $row['title']; },
                'urlExpression' => function($row, $data) { return App()->createUrl('surveys/view', array('id' => $row['sid'])); },
                'class' => 'CLinkColumn'
            ),
            'created',
            'owner.name',
            'open:boolean',
            'anonymized:boolean',
            'completed',
            'partial',
            'total'
        )
        
    ));

?>
