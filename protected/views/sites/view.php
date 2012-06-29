<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	$model->Name,
);

$this->menu=array(
	array('label'=>'List Sites', 'url'=>array('admin')),
	array('label'=>'Create Sites', 'url'=>array('create')),
	array('label'=>'Update Sites', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Sites', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Sites #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'Name',
		'Address',
		'City',
		'State',
		'ZIPPostal',
		'Contact',
	),
)); ?>
