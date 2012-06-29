<?php
$getEmergencyContactsUrl = $this->createUrl('GetEmergencyContacts');
$schoolAutocompleteUrl = $this->createUrl('School/Autocomplete');

Yii::app()->clientScript->registerScript('EnterHandler', "
    document.onkeypress = function (event) {
        event = event || window.event;
        if (event.keyCode === 13) {
             event.returnValue = false;
        } 
    }
");

Yii::app()->clientScript->registerScript("PersonEditScript", "
    
function onHouseholdChanged(householdId)
{
    if (householdId){
        $.ajax({
            type: 'GET',
            url: '" . $getEmergencyContactsUrl . "',
            datatype: 'json',
            traditional: true,
            data: { householdId: householdId },
            success: function(data) {
                if (data && data.length){
                    $('#Person_Emergency1FirstName').val(data[0].firstName);
                    $('#Person_Emergency1LastName').val(data[0].lastName);
                    $('#Person_Emergency1Relationship').val(data[0].relationship);
                    $('#Person_Emergency1MobilePhone').val(data[0].mobile);
                    $('#Person_Emergency2FirstName').val(data[1].firstName);
                    $('#Person_Emergency2LastName').val(data[1].lastName);
                    $('#Person_Emergency2Relationship').val(data[1].relationship);
                    $('#Person_Emergency2MobilePhone').val(data[1].mobile);
                }
            }
        });        
    }
    
}

", CClientScript::POS_HEAD);
?>        

<div class="form">
  
<?php $form=$this->beginWidget('CActiveForm', array('id'=>'person-form','enableAjaxValidation'=>false,)); ?>
    <?php echo $form->hiddenField($model, 'ID', array('name'=>'personId')); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="styled-form left-352">
        <h2><?php echo $this->getAddEditText($model->ID) ?> Participant</h2>
        <p class="note">Fields with <span class="required">*</span> are required.</p>
        
        <div class="form-left-column" style="margin-left: 0px;">
            <div class="row">
                <label class="label">First Name <span class="required">*</span></label>
            
                <span class="short-input"><?php echo $form->textField($model,'FirstName',array('size'=>25,'maxlength'=>50)); ?></span>
                <?php echo $form->error($model,'FirstName'); ?>
            </div>
            <div class="row">
            	<label class="label">Last Name <span class="required">*</span></label>
            	
                <span class="short-input"><?php echo $form->textField($model,'LastName',array('size'=>25,'maxlength'=>50)); ?></span>
                <?php echo $form->error($model,'LastName'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'DateOfBirth',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input-calendar short-input-person short-input-person-calendar">
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                            'model'=>$model,
                            'attribute'=>'DateOfBirth',                 
                            'options'=>array(
                                'showAnim'=>'fold',
                                'changeYear' => 'true',
                                'yearRange' => '1940:+0',
                                'changeMonth' => 'true',
                                'minDate'=>'1940-01-01',
                                'dateFormat'=>  Localization::CLIENT_DATE_FORMAT,
                                'buttonImage' =>  Yii::app()->request->baseUrl . '/images/logo.png',
                                'buttonImageOnly' => true,
                                    ),
                        ));
                    ?>
                </span>
                <?php echo $form->error($model,'DateOfBirth'); ?>
            </div>
            
            <div class="row">
                <label class="label" for="Person_Sex">Gender <span class="required">*</span></label>
            </div>
            <div>
                <span class="short-input-select short-input-person-left-select">
                    <?php echo $form->dropDownList($model,'Sex', array('M' => 'Male', 'F' => 'Female'), array('id'=>'sex-select'), array('multiple'=>'multiple')); ?>
                </span>
                <?php echo $form->error($model,'Sex'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'MobilePhone',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person">
                    <?php echo $form->textField($model,'MobilePhone',array('size'=>25,'maxlength'=>10)); ?>
                </span>
                <?php echo $form->error($model,'MobilePhone'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'HomePhone', array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person">
                    <?php echo $form->textField($model,'HomePhone',array('size'=>25,'maxlength'=>10)); ?>
                </span>
                <?php echo $form->error($model,'HomePhone'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'WorkPhone',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person">
                    <?php echo $form->textField($model,'WorkPhone',array('size'=>25,'maxlength'=>10)); ?>
                </span>
                <?php echo $form->error($model,'WorkPhone'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'EmailAddress',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person">
                    <?php echo $form->textField($model,'EmailAddress',array('size'=>50,'maxlength'=>50)); ?>
                </span>
                <?php echo $form->error($model,'EmailAddress'); ?>
            </div>

            <div class="row">
                <label class="label">Insurance Carrier</label>
            
                <span class="short-input left left-5"><?php echo $form->textField($model,'InsuranceCarrier',array('size'=>60,'maxlength'=>255)); ?></span>
                <?php echo $form->error($model,'InsuranceCarrier'); ?>  
            </div>
            <div class="row">
            	<label class="label">Insurance Number</label>
                <span class="short-input left left-5"><?php echo $form->textField($model,'InsuranceNumber',array('size'=>60,'maxlength'=>255)); ?></span>
                <?php echo $form->error($model,'InsuranceNumber'); ?>
            </div>
            
            <div class="row">
                <label class="label">Physician Name</label>
            
                <span class="short-input left left-5"><?php echo $form->textField($model,'PhysicianName',array('size'=>50,'maxlength'=>50)); ?></span>
                <?php echo $form->error($model,'PhysicianName'); ?>
            </div>
            <div class="row">
            	<label class="label">Physician Phone Number</label>
                <span class="short-input left left-5"><?php echo $form->textField($model,'PhysicianPhoneNumber',array('size'=>50,'maxlength'=>50)); ?></span>
                <?php echo $form->error($model,'PhysicianPhoneNumber'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'GDocSurvey',array('class'=>'label')); ?>
                <span class="short-input short-input-person"><?php echo $form->textField($model,'GDocSurvey',array('size'=>60,'maxlength'=>255)); ?></span>
                <?php echo $form->error($model,'GDocSurvey'); ?>
            </div>
        </div>
    
        <div class="form-left-column">
            <div class="row" style="padding: 0px; height: 3px;"></div>
            <div class="row">
                <?php echo $form->labelEx($model,'BarcodeID',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person-right">
                    <?php echo $form->textField($model, 'BarcodeID',array('size'=>6, 'maxlength'=>6, 'onkeypress' => 'if (event.keyCode === 32) {event.returnValue = false;};')); ?>
                </span>
                <?php echo $form->error($model,'BarcodeID'); ?>
            </div>
            
            <div class="row" style="padding: 0px; height: 4px;"></div>
            <div class="row">
                <?php echo $form->labelEx($model,'Household',array('class'=>'label')); ?>
            </div>
            <div>
                <?php $householdList = Locations::model()->selectHouseholdsByLocation(); // CHtml::listData(Household::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); ?>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo $form->dropDownList($model,'Household', $householdList,
                            array('empty' => 'Select a household:', 'onchange' => 'js: onHouseholdChanged($(this).val())')); ?>
                </span>
                <?php echo $form->error($model,'Household'); ?>
            </div>
            
            <div class="row" style="padding: 0px; height: 2px;"></div>
            <div class="row">
                <?php echo $form->labelEx($model,'Type',array('class'=>'label')); ?>
            </div>
            <div>
                <?php $personTypeList = CHtml::listData(PersonType::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); ?>
                <?php $personTypeList = ReportsController::unionArrays(array("_all" => "Select a role:") , $personTypeList);?>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo CHtml::dropDownList('Type', $model->Type, $personTypeList,  
                        array(
                        'ajax' => array(
                            'type'=>'POST',
                            'url'=> CController::createUrl('person/SubtypeByType'),
                            'update'=>'#Subtype',
                            'data'=>array('type_id'=> 'js:$(\'#Type\').val()'), 
                        ))                                    
                     ); ?>
                </span>
                <?php echo $form->error($model,'Type'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'Subtype',array('class'=>'label')); ?>
            </div>
            <div>
                <?php $subtypesList = CHtml::listData(Personsubtype::model()->findAllByAttributes(array('PersonType'=>$model->Type), array('order' => 'Name')), 'ID', 'Name'); ?>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo CHtml::dropDownList('Subtype', $model->Subtype, $subtypesList, array('empty' => 'Select a relation:')); ?>
                </span>
                <?php echo $form->error($model,'Subtype'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'GradeLevel', array('class'=>'label')); ?>
            </div>
            <div>
                <?php $gradeLevelList = CHtml::listData(GradeLevel::model()->findAll(array('order' => 'Grade')), 'ID', 'Grade'); ?>
                <span class="short-input-select short-input-person-right-select">
                    <?php echo $form->dropDownList($model,'GradeLevel', $gradeLevelList,  array('empty' => 'Select a grade level:'));//$form->textField($model,'Type'); ?>
                </span>
                <?php echo $form->error($model,'GradeLevel'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'School',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input-select short-input-person-right-select short-autocomplete-person-right-select">
                    <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                            'name'=>'SchoolName',
                            'value'=>$model->SchoolName,
                            'sourceUrl'=>$schoolAutocompleteUrl,
                            'options'=>array(
                                'minLength'=>'2',
                                'select'=>'js: function(event, ui){ $("#Person_School").val(ui.item.id); }',
                                'change'=>'js: function(event, ui) { debugger; if (!ui.item) { $("#Person_School").val(""); } }',
                            ),
                        ));
                        echo $form->hiddenField($model, 'School');
                    ?>
                </span>
                <?php echo $form->error($model,'School'); ?>
            </div>
            
            <div class="row">
                <?php echo $form->labelEx($model,'Allergies',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person-right"><?php echo $form->textField($model,'Allergies',array('size'=>60,'maxlength'=>100)); ?></span>
                <?php echo $form->error($model,'Allergies'); ?>
            </div>
            
            <div class="row" style="padding: 0px; height: 4px;"></div>
            <div class="row">
                <?php echo $form->labelEx($model,'Medications',array('class'=>'label')); ?>
            </div>
            <div>
                <span class="short-input short-input-person-right"><?php echo $form->textField($model,'Medications',array('size'=>60,'maxlength'=>100)); ?></span>
                <?php echo $form->error($model,'Medications'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'PicasaLink',array('class'=>'label bottom-5 top-8')); ?>
                <span class="short-input short-input-person-right"><?php echo $form->textField($model,'PicasaLink',array('size'=>60,'maxlength'=>255)); ?></span>
                <?php echo $form->error($model,'PicasaLink'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'GDocApplication',array('class'=>'label')); ?>
                <span class="short-input short-input-person-right"><?php echo $form->textField($model,'GDocApplication',array('size'=>60,'maxlength'=>255)); ?></span>
                <?php echo $form->error($model,'GDocApplication'); ?>
            </div>
        </div>
        
        <div class="row">
            <div class="row-345">
                <?php echo $form->labelEx($model,'Notes',array('class'=>'label')); ?>
                <div class="clear"></div>
                <span class="short-textarea"><?php echo $form->textArea($model,'Notes',array('rows'=>6, 'cols'=>50)); ?></span>
                <?php echo $form->error($model,'Notes'); ?>
            </div>

            <div class="row-345 left-20">
                <?php echo $form->labelEx($model,'SpecialCircumstances',array('class'=>'label')); ?>
                <div class="clear"></div>
                <span class="short-textarea"><?php echo $form->textArea($model,'SpecialCircumstances',array('rows'=>6, 'cols'=>50)); ?></span>
                <?php echo $form->error($model,'SpecialCircumstances'); ?>
            </div>
        </div>

        <div class="clear"></div>
        
        <div class="row household-row">
            <?php echo CHtml::openTag('fieldset', array('class'=>'inlineLabels', 'style'=>'width: auto;')); ?>
            <?php echo CHtml::openTag('legend', array('class'=>'inlineLabels')); ?>
            <?php echo CHtml::Label(Yii::t('application','Emergency contact 1'),''); ?>
            <?php echo CHtml::closeTag('legend'); ?>
            
            <div class="left">
                <?php echo $form->labelEx($model,'Emergency1FirstName', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency1FirstName',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency1FirstName', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency1LastName', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency1LastName',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency1LastName', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency1Relationship', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency1Relationship',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency1Relationship', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency1MobilePhone', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency1MobilePhone',array('size'=>25,'maxlength'=>25)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency1MobilePhone', array('class'=>'errorMessage left')); ?>
            </div>
            
            <?php echo CHtml::closeTag('fieldset'); ?>
        </div>
    
        <div class="row household-row left-20"><!--START [household-row]-->
            <?php echo CHtml::openTag('fieldset', array('class'=>'inlineLabels', 'style'=>'width: auto;')); ?>
            <?php echo CHtml::openTag('legend', array('class'=>'inlineLabels')); ?>
            <?php echo CHtml::Label(Yii::t('application','Emergency contact 2'),''); ?>
            <?php echo CHtml::closeTag('legend'); ?>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency2FirstName', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency2FirstName',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency2FirstName', array('class'=>'errorMessage right')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency2LastName', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency2LastName',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency2LastName', array('class'=>'errorMessage right')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency2Relationship', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency2Relationship',array('size'=>25,'maxlength'=>50)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency2Relationship', array('class'=>'errorMessage right')); ?>
            </div>
            <div class="clear"></div>

            <div class="left">
                <?php echo $form->labelEx($model,'Emergency2MobilePhone', array('class'=>'label width-140 left-10')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Emergency2MobilePhone',array('size'=>25,'maxlength'=>25)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Emergency2MobilePhone', array('class'=>'errorMessage right')); ?>
            </div>
            
            <?php echo CHtml::closeTag('fieldset'); ?>
            
        </div><!--END [household-row]-->
        
        <div class="clear"></div>
        <div class="participantsLinksInBottom">
            
            <div class="allParticipantsLinks right-10"><!--START [allParticipantsLinksFromDB]-->
            <?php 
                foreach($model->Links as $link){
                   ?>
                   <div class='elem-row'>
                       <?php
                      echo CHtml::link($link["Url"], $link["Url"], array(
                                'class'=>'cid text-link',
                                'title'=>'Permalink to this comment',
                                'target'=>'_blank',
                        ));       
                       ?>
                       <a href="#" class="delete_link removeLink" id="delete_link_<?php echo $link["ID"]?>"></a>
                       <input type="hidden" name="Links[]" value="<?php echo $link["Url"];?>" />
                   </div>
                   <div class="clear"></div>
            <?php
                }
            ?>
            </div>    
                
            <div id="link_template">
                <div class="wrapper-styled-buttons bottom-20-p" style="padding-top: 0px;">
                    <span class="styled-bttn right-10">
                        <input type="button" class="addParticipantLink" value="Add" />
                    </span>
                </div>
                <span class="short-input right">
                    <input type="text" class="inputParticipantLink" />
                </span>
                <div class="invalidParticipantUrl"></div>
                <div class="clear"></div>
            </div>
            
            <script type="text/javascript">
                $(document).ready(function(){
                    
                    function validateURL(textval) {
                        var urlregex = new RegExp( "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
                        return urlregex.test(textval);
                    }
                    
                    $('.removeLink').click(function(){
                        $(this).parent().remove();
                        return false;
                    });
                    
                    $('.addParticipantLink').click(function(){
                        var url = $('.inputParticipantLink').val();
                        if(url.slice(0,3) == 'www'){
                            url = 'http://' + url;
                        }
                        if( url != null && url.length > 1 && validateURL(url) == true){
                            var elem = "<div class='elem-row'><a class='text-link' href=" + url
                                + " target='_blank' >" + url
                                + "</a><a href='/' class='removeLink'></a>"
                                + "<input type='hidden' name='Links[]' value="
                                + $('.inputParticipantLink').val() + " />"
                                + "</div><div class='clear'></div>";
                            $('.allParticipantsLinks').append(elem);
                            $('.inputParticipantLink').val('');
                            if($('.invalidParticipantUrl').text().length > 1){
                                $('.invalidParticipantUrl').text('');
                            }
                        } else if(url == ''){
                            return false;
                        } else {
                           $('.inputParticipantLink').val('');
                           $('.invalidParticipantUrl').html('Invalid URL. Please enter valid URL with "www" or "http/https"');
                        }
                        $('.removeLink').click(function(){
                            $(this).parent().remove();
                            return false;
                        });
                        return false;
                    });
                    
                });
            </script>
            
        </div><!--END [participantsLinksInBottom]-->
        
        <div class="wrapper-styled-buttons bottom-20-p">
            <span class="styled-bttn right-10"><?php echo CHtml::submitButton(Yii::t('Yii', 'Cancel'), array('name'=>'cancel', 'onclick'=> "js: history.back();")); ?></span>
            <span class="styled-bttn"><?php echo CHtml::submitButton('OK', array('return' => true)); ?></span>
        </div>
        <div class="clear"></div>
    </div>
<?php $this->endWidget(); ?>
</div>
