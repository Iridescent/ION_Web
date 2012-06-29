<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'household-form',
	'enableAjaxValidation'=>false,
)); ?> 
 <?php echo $form->hiddenField($model, 'ID', array('name'=>'householdId')); ?>

<?php echo $form->errorSummary($model); ?>
<div class="form-left-column styled-form">
    <h2><?php echo $this->getAddEditText($model->ID) ?> Household</h2>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
	
    <div class="row household-row">
        <div class="left">
            <?php echo $form->labelEx($model,'Name', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>255)); ?></span>
            <div class="clear"></div>
            <?php echo $form->error($model,'Name', array('class'=>'errorMessage left')); ?>
        </div>
	
        <div class="left">
            <?php echo $form->labelEx($model,'Address', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model,'Address',array('size'=>60,'maxlength'=>255)); ?></span>
            <div class="clear"></div>
            <?php echo $form->error($model,'Address', array('class'=>'errorMessage left')); ?>
        </div>
        <div class="clear"></div>
	
    	<div class="left">
            <?php echo $form->labelEx($model,'ZIPPostal', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model,'ZIPPostal',array('size'=>9,'maxlength'=>9)); ?></span>
            <div class="clear"></div>
            <?php echo $form->error($model,'ZIPPostal', array('class'=>'errorMessage left')); ?>
        </div>
	
        <div class="left">
            <?php echo $form->labelEx($model,'Phone', array('class'=>'label')); ?>
            <span class="short-input"><?php echo $form->textField($model,'Phone',array('size'=>10,'maxlength'=>10)); ?></span>
            <div class="clear"></div>
            <?php echo $form->error($model,'Phone', array('class'=>'errorMessage left')); ?>
        </div>
        <div class="clear"></div>
    </div>    
    
    <div class="row household-row left-20">	
        <?php 
        //$userLocation = Locations::model()->getLocation(Yii::app()->user->location);

            if ($_POST['countries']) {
                $defaultCountry = $_POST['countries'];
            } else {
                $defaultCountry = $model->countries;                
            }

            if ($_POST['states']) {
                $defaultState = $_POST['states'];
            } else {
                $defaultState = $model->states;                
            }
            
            if ($_POST['cities']) {
                $defaultCity = $_POST['cities'];
            } else {
                $defaultCity = $model->cities;                
            }
        
        $countriesList = Locations::selectCountry(true); 
        $statesList = Locations::selectStates($defaultCountry);
        $citiesList = Locations::selectCities($defaultState);

        echo '<div class="left">';
        echo CHtml::label('Country', 'countries', array('class'=>'label'));
        echo "<span class=\"short-input-select\">";
        echo CHtml::dropDownList('countries', $defaultCountry , $countriesList,  
                    array(
                    'ajax' => array(
                        'type'=>'POST', //request type
                        'url'=> CController::createUrl('site/StatesByCountry&list=true'), //url to call.
                        'update'=>'#states', //selector to update
                        'data'=>array('countryid'=> 'js:$(\'#countries\').val()'), 
                    ))                                    
                 ); 
        echo "</span>";
        echo '<div class="clear"></div>';
        echo $form->error($model,'countries', array('class'=>'errorMessage right'));
        echo '</div>';

        echo '<div class="left">';
        echo CHtml::label('State', 'states', array('class'=>'label'));
        echo "<span class=\"short-input-select\">";
        echo CHtml::dropDownList('states', $defaultState, $statesList,  
                    array(
                    'ajax' => array(
                        'type'=>'POST', //request type
                        'url'=> CController::createUrl('site/CitiesByState&list=true'), //url to call.
                        'update'=>'#cities', //selector to update
                        'data'=>array('stateid'=> 'js:$(\'#states\').val()'), 
                    ))                                    
                 ); 
        echo "</span>";
        echo '<div class="clear"></div>';
        echo $form->error($model,'states', array('class'=>'errorMessage right'));
        echo '</div>';

            echo '<div class="left">';
        echo CHtml::label('City', 'cities', array('class'=>'label'));
        echo "<span class=\"short-input-select\">";
        echo CHtml::dropDownList('cities', $defaultCity, $citiesList);//, array('multiple' => 'multiple', 'key'=>'trainings', 'class'=>'multiselect')); 
        echo "</span>";
        echo '<div class="clear"></div>';
        echo $form->error($model,'cities', array('class'=>'errorMessage right'));
        echo '</div>';
        ?>
        <div class="left">
            <?php echo $form->labelEx($model,'PicasaLink', array('class'=>'label left')); ?>
            <span class="short-input left-0 right"><?php echo $form->textField($model,'PicasaLink',array('size'=>25,'maxlength'=>255)); ?></span>
            <div class="clear"></div>
            <?php echo $form->error($model,'PicasaLink', array('class'=>'errorMessage left')); ?>
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
    
    <div class="row household-row left-20">
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
    </div>
    
    <div class="wrapper-styled-buttons bottom-20-p">
      <span class="styled-bttn right-10"><?php echo CHtml::submitButton(Yii::t('Yii', 'Cancel'), array('name'=>'cancel')); ?></span>
      <span class="styled-bttn"><?php echo CHtml::submitButton('OK'); ?></span>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div> 
<?php $this->endWidget(); ?>

<?php
    echo '&nbsp;';
    if ($model->ID) {
        $_POST['householdId'] = $model->ID;     
        $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
            'id'=>'personGrid',
            'dataProvider'=> QueryPerson::model()->toHH($model->ID),
            'ajaxVar'=>true,
            'ajaxUpdate'=>true,
            'beforeAjaxUpdate'=>'beforePersonGridUpdate',
            'template'=>'{items}{pager}{summary}',
            'title'=> 'Participants',
            'addActionUrl'=> Yii::app()->createUrl('person/edit', array('householdId'=>$model->ID)),
            'editActionUrl'=> Yii::app()->createUrl('person/edit', array('householdId'=>$model->ID)),
            'deleteActionUrl'=> $this->createUrl('excludePerson'),
            'deleteConfirmation'=> 'Are you sure you want to delete this person from household?',
            'idParameterName'=>'personId',
            'columns'=>array(
                'BarcodeID',
                'FirstName',
                'LastName',
                array(
                   'name' => 'Household', 
                   'header'=>'Household',
                   'value'=>'$data->HouseholdRelation->Name'
                 ),
                'HomePhone',
                'EmailAddress',
                array(
                   'name' => 'Type', 
                   'header'=>'Role',
                   'value'=>'$data->Role->Name'
                 ),              
         )
        )); 
    } 
?>   

 </div><!-- form -->
 
 
 
  
   
       