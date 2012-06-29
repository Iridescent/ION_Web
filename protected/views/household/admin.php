<?php
$this->breadcrumbs=array(
	'Households'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Household', 'url'=>array('create')),
);

/*$this->widget('ext.EExcelView', array(
     'dataProvider'=> $model,
     'title'=>'Title',
     'autoWidth'=>false,
     
)); */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('household-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Households</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'household-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ID',
		'Name',
		'Address',
		'Location',
		'ZIPPostal',
		/*
		'FullAddress',
		'Phone',
		'LastUpdated',
		*/
		array(
			'class'=>'CButtonColumn',
                            'buttons'=>array(
                                'delete' => array(
                                    'visible'=>'Yii::app()->user->checkAccess("Super Admin")',   // a PHP expression for determining whether the button is visible
                                )
                            )
		),
	),
    
)); ?>
