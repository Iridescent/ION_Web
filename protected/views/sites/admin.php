<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Sites', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sites-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sites</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sites-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ID',
		'Name',
		'Address',
		'Location',
		'ZIPPostal',
		/*
		'Contact',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
