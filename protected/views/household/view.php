<?php
$this->breadcrumbs=array(
	'Households'=>array('index'),
	$model->Name,
);

$this->menu=array(
	array('label'=>'List Households', 'url'=>array('admin')),
	array('label'=>'Create Household', 'url'=>array('create')),
	array('label'=>'Update Household', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Household', 'url'=>'#',  'visible'=>Yii::app()->user->checkAccess("Super Admin"), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this household?')),
);
?>

<h1>View Household #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'Name',
		'Address',
		'Location',
		'ZIPPostal',
		'FullAddress',
		'Phone',
		//'LastUpdated',
	),
)); ?>
<?php echo $this->renderPartial('_tenants', array('householdId'=>$model->ID, 'personFilter'=>$personFilter)); ?>