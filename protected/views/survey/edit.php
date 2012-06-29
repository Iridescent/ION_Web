<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/BaseQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/DropdownQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/GridQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/MultipleChoiceQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/ScaleQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/SingleChoiceQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/TextAreaQuestion.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/survey/TextInputQuestion.js');
    
    $json_questions = $model->QuestionsToJSON();
    
    Yii::app()->clientScript->registerScript('EditSurvey', "
        var json_questions = '" . $json_questions . "';
            
        $(document).ready(function() {
            survey.init(json_questions);
            survey.draw();   
            
            $('#saveButton').click(function() {
                $('#surveyForm').submit();
            });
            
            $('#bottom_add_button').click(function() {
                survey.addQuestion();
            });
        });
    ");
?>

<div id="syrveyCustomForm"><!--START [syrveyCustomForm]-->

    <?php $form=$this->beginWidget('CActiveForm', array( 'id'=>'surveyForm', 'enableAjaxValidation'=>false,)); ?>
    <?php echo $form->hiddenField($model, 'ID', array('name'=>'surveyId')); ?>

    <div class="surveyHeader"><!--START [surveyHeader]-->
        <h2><?php echo $this->getAddEditText($model->ID) ?> Survey</h2>
        <div class="row surveyHeaderRow">
            <?php echo $form->labelEx($model, 'Title', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model, 'Title', array('size'=>25,'maxlength'=>255)); ?></span>
            <?php echo $form->error($model, 'Title'); ?>
        </div><!--END [surveyHeaderRow]-->
        <div class="clear"></div>
        <div class="row surveyHeaderRow">
            <?php echo $form->labelEx($model, 'Description', array('class'=>'label')); ?>
            <span class="short-textarea">
                <?php echo $form->textArea($model, 'Description'); ?>
            </span>
            <?php echo $form->error($model, 'Description'); ?>
        </div><!--END [surveyHeaderRow]-->
        <div class="clear"></div>
        <div class="row surveyHeaderRow">
            <?php $programList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description'); ?>
            <label class="label">Program</label>
            <span class="surveyHeaderSelect">                
                <?php echo CHtml::dropDownList('Survey[ProgramId]', $model->ProgramId, $programList,
                    array(
                        'empty' => 'Select a Program:', 
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('misc/SessionSelect'),
                            'update'=>'#session_select',
                            'data'=>array('program_id'=> 'js:$(\'#Survey_ProgramId\').val()', 'input_name'=>'Survey[SessionId]'), 
                        )
                    )
                 ); ?>
            </span>
            <?php echo $form->error($model, 'ProgramId'); ?>
        </div><!--END [surveyHeaderRow]-->
        <div class="clear"></div>
        <div class="row surveyHeaderRow">
            <?php $sessionList = array();
                  if ($model->ProgramId) {
                      $sessionList = CHtml::listData(QuerySession::model()->SelectListByProgramId($model->ProgramId), 'ID', 'Description');
                  }
            ?>
            <label class="label">Session</label>
            <span id="session_select" class="surveyHeaderSelect">
                <?php echo $form->dropDownList($model, 'SessionId', $sessionList, array('empty' => 'Select a Session:')); ?>
            </span>
            <?php echo $form->error($model, 'SessionId'); ?>
        </div><!--END [surveyHeaderRow]-->
    </div><!--END [surveyHeader]-->

    <?php $this->endWidget(); ?>

    <div class="surveyBodyQuestions"><!--START [surveyBodyQuestions]-->
        <h2>List of Questions</h2>
    </div><!--END [surveyBodyQuestions]-->
    <input id="bottom_add_button" type="button" value="Add" />
    
    <div class="wrapper-styled-buttons">
        <span class="styled-bttn">
            <?php echo CHtml::button('Cancel', array ('id'=>'cancelButton', 'onclick'=> "js: history.back();")); ?>
        </span>
        <span class="styled-bttn">
            <?php echo CHtml::button('OK', array ('id'=>'saveButton')); ?>
        </span>
    </div>

</div><!--END [syrveyCustomForm]-->

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/surveyQuestions.js"></script>


