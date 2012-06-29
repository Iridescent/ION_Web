<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Address')); ?>:</b>
	<?php echo CHtml::encode($data->Address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cities')); ?>:</b>
	<?php echo CHtml::encode($data->cities); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('StateProvince')); ?>:</b>
	<?php echo CHtml::encode($data->states); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ZIPPostal')); ?>:</b>
	<?php echo CHtml::encode($data->ZIPPostal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FullAddress')); ?>:</b>
	<?php echo CHtml::encode($data->FullAddress); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Phone')); ?>:</b>
	<?php echo CHtml::encode($data->Phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LastUpdated')); ?>:</b>
	<?php echo CHtml::encode($data->LastUpdated); ?>
	<br />

	*/ ?>

</div>