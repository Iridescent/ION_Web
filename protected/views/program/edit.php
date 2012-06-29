<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.dataTables.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/TableTools.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/ProgramSession.js');

$getSessionListUrl = $this->createUrl('GetSessionList');
$sessionLocationAutocompleteUrl = $this->createUrl('SessionLocationAutocomplete');
$personAutocompleteUrl = $this->createUrl('PersonAutocomplete');
$programId = $model->ID;

Yii::app()->clientScript->registerScript('EditProgram', "
    var sessionGridView;
    var programId = " . ($programId ? $programId : 0) . ";
    var sessionList = [];
    var selectedRow = null;
    var selectedSession = null;
    
    $(document).ready(function(){

        sessionGridView = $('#sessionGridView').dataTable({
            'sDom': 'T<\"clear\">lfrtip',
            'oTableTools': {
                    'sRowSelect': 'single',
                    'aButtons': [],
                    'fnRowSelected': function (node) { selectedRow = sessionGridView.fnSettings().aoData[node._DT_RowIndex]; },
                    'fnRowDeselected': function (node) { selectedRow = null; },
                    'sSelectedClass': 'gridSelectedRow',
                },
            'aoColumns': [
                    { 'bVisible': false },
                    { 'sTitle': 'Description', 'bSortable': false, 'sWidth': '48%' },
                    { 'sTitle': 'Location', 'bSortable': false, 'sWidth': '28%' },
                    { 'sTitle': 'Time', 'bSortable': false, 'sWidth': '12%' },
                    { 'sTitle': 'Date', 'bSortable': false, 'sWidth': '12%' },
                ],
            'bFilter': false,
            'bLengthChange': false,
            'bInfo': false,
            'bPaginate': false,
        });
        
        $('#addSessionButton').click(function() {
            var valid = ValidatePeriod();
            if (valid){
                selectedSession = new ProgramSession(-1);
                FillEditSessionForm();
                $('#sessionTitle').text('Add Session');
                $('#SessionEditDialog').dialog('open');
                $('.errorSummary').hide();
                return false;
            }
        });
        
        $('#editSessionButton').click(function() {
            if (selectedRow){
                selectedSession = null;
                var idx = IndexOfById(sessionList, selectedRow._aData[0]);
                if (idx > -1){
                    selectedSession = sessionList[idx];
                    FillEditSessionForm();
                    $('#sessionTitle').text('Edit Session');
                    $('#SessionEditDialog').dialog('open');
                    $('.errorSummary').hide();
                    return false;
                }
            }
        });
        
        $('#deleteSessionButton').click(function() {
            if (selectedRow){
                var idx = IndexOfById(sessionList, selectedRow._aData[0]);
                if (idx > -1){
                    sessionList.splice(idx, 1);
                    ResetSessionIds();
                }
                sessionGridView.fnDeleteRow(selectedRow.nTr._DT_RowIndex);
                selectedSession = null;
                selectedRow = null;
            }
        });
        
        $('#sessionOkButton').click(function () {
            var valid = true;
            $('#sessionErrorLabel').text('');
            $('#sessionErrorLabel').parent().hide();

            var startDate = $('#StartDate').val();
            
            if (!$('#Description').val()){
                valid = false;
                $('#sessionErrorLabel').text('Please enter Description');
            }
            else if(!startDate) {
                valid = false;
                $('#sessionErrorLabel').text('Please select Date');
            }
            else if (!startDate.isValidDate()){
                valid = false;
                $('#sessionErrorLabel').text('Incorrect date format, use mm/dd/yyyy');
            }
            else if(!ValidateSessionDate()) {
                valid = false;
                $('#sessionErrorLabel').text('Date must be between Start and End Date of Program');
            }

            if(valid){
                FillSelectedSession();
                if (selectedSession.Id < 0){
                    selectedSession.Id = sessionList.length;
                    sessionList.push(selectedSession);
                    SessionGridAddRow(selectedSession);
                }
                else{
                    var idx = IndexOfById(sessionList, selectedSession.Id);
                    if (idx > -1){
                        sessionList[idx] = selectedSession;
                        SessionGridUpdateRow(selectedSession, selectedRow.nTr._DT_RowIndex);
                    }
                }

                $('#SessionEditDialog').dialog('close');
                return false;
            }
            else{
                $('#sessionErrorLabel').parent().show();
            }
        });
        
        $('#sessionCancelButton').click(function () {
            $('#SessionEditDialog').dialog('close');
            return false;
        });
        
        if (programId)
        {
            $.ajax({
                type: 'GET',
                url: '" . $getSessionListUrl . "',
                datatype: 'json',
                traditional: true,
                data: { programId: programId },
                success: function(data) {
                    if (data){
                        for (var i=0; i<data.length; i++){
                            var item = data[i];
                            var session = new ProgramSession(i, item.Id, item.Program, item.StartDate, item.StartTime, item.Description, item.Instructors,
                                                              item.NonSchoolSite, item.SchoolSite, item.CourseManager, item.Notes, item.UserManager);
                            sessionList.push(session);
                            SessionGridAddRow(session);
                        }
                    }
                }
            });
        }
        
        $('#saveButton').click(function(){
            var valid = true;
            $('#errorProgramLabel').text('');
            $('#errorProgramLabel').parent().hide();
            
            valid = ValidatePeriod();
            if (valid){
                if(!$('#DomainProgram_Description').val()){
                    valid = false;
                    $('#errorProgramLabel').text('Please enter Name');
                }
                else if (sessionList.length < 1){
                    valid = false;
                    $('#errorProgramLabel').text('Please add at least one Session');
                }
            }

            if (valid){
                var sessionListDiv = $('#sessionListDiv');
                sessionListDiv.empty();
                for(var i=0; i<sessionList.length; i++){
                    var hiddens = sessionList[i].GetHiddenInput();
                    for (var j=0; j<hiddens.length; j++){
                        sessionListDiv.append(hiddens[j]);
                    }
                }
                $('#programForm').submit();
            }
            else{
                $('#errorProgramLabel').parent().show();
            }
        });
        
        $('#cancelButton').click(function(){
            history.back();
        });

    });
    
    function SessionGridAddRow(session){
        sessionGridView.fnAddData([session.Id, session.Description, session.GetLocation(), session.StartTime, session.StartDate]);
    }
    
    function SessionGridUpdateRow(session, idx){
        sessionGridView.fnUpdate([session.Id, session.Description, session.GetLocation(), session.StartTime, session.StartDate], idx);
    }
    
    function FillEditSessionForm(){
        $('#Description').val(selectedSession.Description);
        $('#StartDate').val(selectedSession.StartDate);
        $('#StartTime').val(selectedSession.StartTime);
        $('#Location').val(selectedSession.GetLocation()); 
        $('#CourseManager').val(selectedSession.GetCourseManagerName());
        $('#Notes').val(selectedSession.Notes);
        $('#Instructors').val(selectedSession.Instructors);
    }
    
    function FillSelectedSession(){
        selectedSession.Description = $('#Description').val();
        selectedSession.StartDate = $('#StartDate').val();
        selectedSession.StartTime= $('#StartTime').val();
        selectedSession.Notes = $('#Notes').val();
        selectedSession.Instructors = $('#Instructors').val();
    }
    
    function ResetSessionIds(){
        for (var i=0; i<sessionList.length; i++){
            sessionList[i].Id = i;
        }
    }
    
    function ValidatePeriod(){
        var valid = true;
        if (!$('#DomainProgram_StartDate').val() || !$('#DomainProgram_EndDate').val()){
            valid = false;
            $('#errorProgramLabel').text('Please select Start and End Date');
			$('#errorProgramLabel').parent().css('display','block');
        }
        else{
            var startDate = new Date($('#DomainProgram_StartDate').val());
            var endDate = new Date($('#DomainProgram_EndDate').val());
            if (endDate <= startDate){
                valid = false;
                $('#errorProgramLabel').text('End Date must be greater then Start Date');
				$('#errorProgramLabel').parent().css('display','block');
            }
        }
        return valid;
    }
    
    function ValidateSessionDate(){
        var startDate = new Date($('#DomainProgram_StartDate').val());
        var endDate = new Date($('#DomainProgram_EndDate').val());
        var sessionDate =  new Date($('#StartDate').val());
        return sessionDate <= endDate && sessionDate >= startDate;
    }
");
?>

<div class="form">
    <div class="errorSummary" style="display: none;">
        <label id="errorProgramLabel" />
    </div>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'programForm',
        'enableAjaxValidation'=>false,
    )); ?>
    <?php echo $form->hiddenField($model, 'ID', array('name'=>'programId')); ?>
    <div class="form-left-column styled-form">
        <h2><?php echo $this->getAddEditText($model->ID) ?> Program</h2>
        <div class="row">
            <?php echo $form->labelEx($model, 'StartDate', array('class'=>'label')); ?>
            <span class="short-input-calendar">
            	<?php 
                $this->widget('zii.widgets.jui.CJuiDatePicker',
                        array(
                               'language'=>'',
                               'model'=>$model,
                               'attribute'=>'StartDate', 
                               'options'=>array(
                                            'showAnim'=>'fold',
                                            'changeYear' => 'true',
                                            'yearRange' => '2000:+0',
                                            'changeMonth' => 'true',
                                            'minDate'=>'2000-01-01', // HARDCODED
                                            'dateFormat'=> Localization::CLIENT_DATE_FORMAT,
                                'buttonImage' =>  Yii::app()->request->baseUrl . '/images/logo.png',
                                'buttonImageOnly' => true,
                            ),
                    ));
                ?>
            </span>
            
            <?php echo $form->labelEx($model, 'EndDate', array('class'=>'label left-20')); ?>
            <span class="short-input-calendar">
            	<?php 
                $this->widget('zii.widgets.jui.CJuiDatePicker',
                        array(
                               'language'=>'',
                               'model'=>$model,
                               'attribute'=>'EndDate', 
                               'options'=>array(
                                            'showAnim'=>'fold',
                                            'changeYear' => 'true',
                                            'yearRange' => '2000:+0',
                                            'changeMonth' => 'true',
                                            'minDate'=>'2000-01-01', // HARDCODED
                                            'dateFormat'=>Localization::CLIENT_DATE_FORMAT,
                                'buttonImage' =>  Yii::app()->request->baseUrl . '/images/logo.png',
                                'buttonImageOnly' => true,
                            ),
                    ));
                ?> 
            </span>
            </div>
            
        <div class="row">
            <?php echo $form->labelEx($model, 'Description', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model, 'Description',array('size'=>25,'maxlength'=>50)); ?></span>

            <?php echo $form->labelEx($model, 'ProgramType', array('class'=>'label left-20 label-115')); ?>
            <?php $programTypeList = CHtml::listData(ProgramType::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); ?>
            <span class="short-input-select short-input-select-225 right right-20"><?php echo $form->dropDownList($model, 'ProgramType', $programTypeList); ?></span>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'PicasaLink', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model, 'PicasaLink',array('size'=>25,'maxlength'=>250)); ?></span>
            <?php echo $form->labelEx($model, 'GDocLink', array('class'=>'label left-20 label-115')); ?>
            <span class="short-input right right-20"><?php echo $form->textField($model, 'GDocLink',array('size'=>25,'maxlength'=>250)); ?></span>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'GDocExtra', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model, 'GDocExtra',array('size'=>25,'maxlength'=>250)); ?></span>
            <?php echo $form->labelEx($model, 'GDocLog', array('class'=>'label left-20 label-115')); ?>
            <span class="short-input right right-20"><?php echo $form->textField($model, 'GDocLog',array('size'=>25,'maxlength'=>250)); ?></span>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'BasecampLink', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model, 'BasecampLink',array('size'=>25,'maxlength'=>250)); ?></span>
        </div>
        <div style="display: none;" id="sessionListDiv">
        </div>
    </div>
    <div style="clear: both;"></div>
    
    <?php $this->endWidget(); ?>
</div>

<div class="grid-styled-wrapper edit-grid-wrapper">

    <div class="gridTitle">
    	<h2>Sessions</h2>
    	<div class="controlls">
	        <?php echo "<button class='add-edit-delete-bttn' id='addSessionButton'>"."<img src='images/add-icon.png' alt='' />"."<span>".'Add'."</span>"."</button>"; ?>
	        <?php echo "<button class='add-edit-delete-bttn' id='editSessionButton'>"."<img src='images/edit-icon.png' alt='' />"."<span>".'Edit'."</span>"."</button>"; ?>
	        <?php echo "<button class='add-edit-delete-bttn' id='deleteSessionButton'>"."<img src='images/delete-icon.png' alt='' />"."<span>".'Delete'."</span>"."</button>"; ?>
	    </div>
    </div>
    
    <table id="sessionGridView"></table>
    <div class="wrapper-styled-buttons">
        <span class="styled-bttn"><?php echo CHtml::button('Cancel', array ('id'=>'cancelButton')); ?></span>
        <span class="styled-bttn"><?php echo CHtml::button('OK', array ('id'=>'saveButton')); ?></span>
    </div>
    
</div>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'SessionEditDialog',
    'options'=>array(
        'title'=>'Session',
        'modal'=>true,
        'width'=>755,
        'height'=>'auto',
        'resizable' => false,
        'autoOpen'=>false,
    ),
));
?>

<div class="form-left-column styled-form pop-up-styled">
    <div class="errorSummary bottom-20 left-0 top-0 right-0" style="display: none">
        <label id="sessionErrorLabel" />
    </div>
	
    <h2><label id="sessionTitle" /></h2>
	
    <div class="row">
    	<?php echo CHtml::label('Description', '', array('class'=>'label')) ?>
       	<span class="long-input-545"><?php echo CHtml::textField('Description', '') ?></span>
    </div>
    <div class="clear"></div>
    
    <div class="row">
            <?php echo CHtml::label('Date', '', array('class'=>'label')) ?>
            <span class="short-input-calendar">
            <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker',
                    array(
                           'language'=>'',
                           'name'=>'StartDate', 
                           'options'=>array(
                                        'showAnim'=>'fold',
                                        'changeYear' => 'true',
                                        'yearRange' => '2000:+0',
                                        'changeMonth' => 'true',
                                        'minDate'=>'2000-01-01', // HARDCODED
                                        'dateFormat'=>Localization::CLIENT_DATE_FORMAT,
                                        'buttonImage' =>  Yii::app()->request->baseUrl . '/images/logo.png',
                                        'buttonImageOnly' => true,
                               ),
                            'htmlOptions' => array('style' => 'width: 182px;'),
                ));
            ?>
            </span>

            <?php echo CHtml::label('Time', '', array('class'=>'label left-25')) ?>
            <span class="short-input-calendar">
            <input type="text" id="StartTime" />
            </span>
    </div>
    <div class="clear"></div>
    
    <div class="row">
            <?php echo CHtml::label('Location', '', array('class'=>'label')) ?>
            <span class="long-input-545 autocomlete-input-545">
            	<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                           'name'=>'Location',
                           'sourceUrl'=>$sessionLocationAutocompleteUrl,
                           'options'=>array(
                               'minLength'=>'2',
                               'select'=>'js: function(event, ui){ 
                                   if (ui.item["type"] == "1") {//School
                                      selectedSession.SchoolSite = {};
                                      selectedSession.SchoolSite.Id = ui.item["id"];
                                      selectedSession.SchoolSite.Name = ui.item["value"];
                                   }
                                   else if (ui.item["type"] == "2"){//Site
                                      selectedSession.NonSchoolSite = {};
                                      selectedSession.NonSchoolSite.Id = ui.item["id"];
                                      selectedSession.NonSchoolSite.Name = ui.item["value"];
                                   }
                               }'
                           )
                        ));
            ?>
    	</span>
    </div>
    <div class="clear"></div>
    
    
    <div class="row">
        <?php echo CHtml::label('Course Manager', '', array('class'=>'label')) ?>
    	<span class="long-input-545 autocomlete-input-545">
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                           'name'=>'CourseManager',
                           'sourceUrl'=>$personAutocompleteUrl,
                           'options'=>array(
                               'minLength'=>'2',
                               'select'=>'js: function(event, ui){
                                   if (ui.item["type"] == "1") {//User
                                      selectedSession.UserManager = {};
                                      selectedSession.UserManager.Id = ui.item["id"];
                                      selectedSession.UserManager.Name = ui.item["value"];
                                   }
                                   else if (ui.item["type"] == "2"){//Person
                                      selectedSession.CourseManager = {};
                                      selectedSession.CourseManager.Id = ui.item["id"];
                                      selectedSession.CourseManager.Name = ui.item["value"];
                                   }
                               }'                               
                           )
                        ));
            ?>
    	</span>
    </div>
    <div class="clear"></div>
    
    <div class="row">
        <div class="row-345">
            <?php echo CHtml::label('Notes', '',array('class'=>'label')) ?>
            <div class="clear"></div>
            <span class="short-textarea"><?php echo CHtml::textArea('Notes', '') ?></span>
        </div>
        
        <div class="row-345 left-15">
        	<?php echo CHtml::label('Instructors', '',array('class'=>'label')) ?>
            <div class="clear"></div>
            <span class="short-textarea"><?php echo CHtml::textArea('Instructors', '') ?></span>
        </div>
    </div>
    <div class="clear"></div>
    
    <div class="wrapper-styled-buttons right-0-p">
        <span class="styled-bttn"><?php echo CHtml::button('Cancel', array ('id'=>'sessionCancelButton')); ?></span>
        <span class="styled-bttn"><?php echo CHtml::button('OK', array ('id'=>'sessionOkButton')); ?></span>
    </div>
    
</div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>