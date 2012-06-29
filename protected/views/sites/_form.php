<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sites-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
<div class="form-left-column">
	<div class="row">
		<?php echo $form->labelEx($model,'Name'); ?>
		<?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Address'); ?>
		<?php echo $form->textField($model,'Address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'Address'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'ZIPPostal'); ?>
		<?php echo $form->textField($model,'ZIPPostal'); ?>
		<?php echo $form->error($model,'ZIPPostal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Contact'); ?>
		<?php echo $form->textArea($model,'Contact',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Contact'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->