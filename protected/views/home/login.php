<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>

<div id="login-content">
<h2>Login to ION</h2>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
        
   	<div class="row-login">
		<?php echo $form->labelEx($model,'username',array('class'=>'label')); ?>
		<span class="input">
		<?php echo $form->textField($model,'username'); ?>
		</span>
		<div class="clear"></div>
	</div>
	<?php echo $form->error($model,'username'); ?>

	<div class="row-login">
		<?php echo $form->labelEx($model,'password',array('class'=>'label')); ?>
		<span class="input">
		<?php echo $form->passwordField($model,'password'); ?>
		</span>
		<div class="clear"></div>
	</div>
	<?php echo $form->error($model,'password'); ?>

	<div class="row rememberMe row-remember ">
		<span class="niceCheck">
			<?php echo $form->checkBox($model,'rememberMe',array('onclick'=>'changeCheck(this)')); ?>
		</span>
		<?php echo $form->label($model,'rememberMe'); ?>
		<div class="clear"></div>
	</div>
	<?php echo $form->error($model,'rememberMe'); ?>

	<div class="login-bttn">
		<?php echo CHtml::submitButton('Sign in',array('class'=>'sign-in')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
</div>