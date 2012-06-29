<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	$model->Name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sites', 'url'=>array('admin')),
	array('label'=>'Create Sites', 'url'=>array('create')),
	array('label'=>'View Sites', 'url'=>array('view', 'id'=>$model->ID)),
);
?>

<h1>Update Sites <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>