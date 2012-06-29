<?php
$this->breadcrumbs=array(
	'Households'=>array('index'),
	$model->Name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Households', 'url'=>array('admin')),
	array('label'=>'Create Household', 'url'=>array('create')),
	array('label'=>'View Household', 'url'=>array('view', 'id'=>$model->ID)),
);
?>

<h1>Update Household <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); //, 'person1'=>$person1, 'person2'=>$person2)); ?>