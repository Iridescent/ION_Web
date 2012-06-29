<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sites', 'url'=>array('admin')),
);
?>

<h1>Create Sites</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>