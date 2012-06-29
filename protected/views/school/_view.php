<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Type')); ?>:</b>
	<?php echo CHtml::encode($data->Type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSDAddress')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSDAddress); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Zip')); ?>:</b>
	<?php echo CHtml::encode($data->Zip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Phone')); ?>:</b>
	<?php echo CHtml::encode($data->Phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fax')); ?>:</b>
	<?php echo CHtml::encode($data->Fax); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSDBoardDistrict')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSDBoardDistrict); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSDLocalDistrict')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSDLocalDistrict); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSDSchoolType')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSDSchoolType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSDSchoolCalendar')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSDSchoolCalendar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LAUSCSchoolCode')); ?>:</b>
	<?php echo CHtml::encode($data->LAUSCSchoolCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('GradeLevel')); ?>:</b>
	<?php echo CHtml::encode($data->GradeLevel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LACCharterSchoolNum')); ?>:</b>
	<?php echo CHtml::encode($data->LACCharterSchoolNum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Website')); ?>:</b>
	<?php echo CHtml::encode($data->Website); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Address')); ?>:</b>
	<?php echo CHtml::encode($data->Address); ?>
	<br />

	?>

</div>