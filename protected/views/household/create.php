<?php
$this->breadcrumbs=array(
	'Households'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Households', 'url'=>array('admin')),
);
?>

<h1>Create Household</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>