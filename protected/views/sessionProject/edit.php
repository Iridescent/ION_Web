<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey.js');
    
   
    
    Yii::app()->clientScript->registerScript('EditSessionProject', "
        var json_questions = '" . $json_questions . "';
        var survey;
        $(document).ready(function() {
            survey = new Survey(json_questions);
        });
    ");
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'surveyForm', 'enableAjaxValidation'=>false,)); ?>
        <?php echo $form->hiddenField($model, 'ID', array('name'=>'surveyId')); ?>
        <div class="form-left-column styled-form">
            <h2><?php echo $this->getAddEditText($model->ID) ?> Survey</h2>
            <div class="row">
                <?php echo $form->labelEx($model, 'Title', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model, 'Title', array('size'=>25,'maxlength'=>255)); ?></span>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'Description', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model, 'Description', array('size'=>25,'maxlength'=>255)); ?></span>
            </div>
            <div class="row">
                <?php $programList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description'); ?>
                <label class="label">Program</label>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo CHtml::dropDownList('ProgramId', $model->ProgramId, $programList, array('empty' => 'Select a Program:')); ?>
                </span>
            </div>
            <div class="row">
                <?php $sessionList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description'); ?>
                <label class="label">Session</label>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo CHtml::dropDownList('SessionId', $model->SessionId, $programList, array('empty' => 'Select a Program:')); ?>
                </span>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>


