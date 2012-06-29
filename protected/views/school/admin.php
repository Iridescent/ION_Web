<?php
$this->breadcrumbs=array(
	'Schools'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create School', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('school-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Schools</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'school-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ID',
		'Type',
		'Name',
		'LAUSDAddress',
		//'Zip',
		'Phone',
		/*
		'Fax',
		'LAUSDBoardDistrict',
		'LAUSDLocalDistrict',
		'LAUSDSchoolType',
		'LAUSDSchoolCalendar',
		'LAUSCSchoolCode',
		'GradeLevel',
		'LACCharterSchoolNum',
		'Website',
		'Address',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
