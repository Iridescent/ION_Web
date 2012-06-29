<?php
$this->breadcrumbs=array(
	'Schools'=>array('index'),
	$model->Name,
);

$this->menu=array(
	array('label'=>'List Schools', 'url'=>array('admin')),
	array('label'=>'Create School', 'url'=>array('create')),
	array('label'=>'Update School', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete School', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View School #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'Type',
		'Name',
		'LAUSDAddress',
		'Zip',
		'Phone',
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
	),
)); ?>
