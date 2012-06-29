<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo $form->hiddenField($model, 'ID', array('name'=>'schoolId')); ?>

<?php echo $form->errorSummary($model); ?>
<div class="form-left-column styled-form">

        <h2><?php echo $this->getAddEditText($model->ID) ?> School</h2>
        <p class="note">Fields with <span class="required">*</span> are required.</p>
        
        <div class="row school-row">
            <div class="left">
                <?php echo $form->labelEx($model,'Name', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Name', array('class'=>'errorMessage left')); ?>
            </div>
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
            //echo $form->labelEx($model,'Country', array('class'=>'label'));
            echo CHtml::label('Country', 'countries', array('class'=>'label', "required"=>true));
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
            echo CHtml::label('State', 'states', array('class'=>'label', "required"=>true));
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
            echo CHtml::label('City', 'cities', array('class'=>'label', "required"=>true));
            echo "<span class=\"short-input-select\">";
            echo CHtml::dropDownList('cities', $defaultCity, $citiesList);//, array('multiple' => 'multiple', 'key'=>'trainings', 'class'=>'multiselect')); 
            echo "</span>";
            echo '<div class="clear"></div>';
            echo $form->error($model,'cities', array('class'=>'errorMessage right'));
            echo '</div>';
            ?>
           
            <div class="left">
		<?php echo $form->labelEx($model,'Address', array('class'=>'label')); ?>
		<span class="short-input"><?php echo $form->textField($model,'Address',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Address', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="left">
		<?php echo $form->labelEx($model,'Zip', array('class'=>'label')); ?>
		<span class="short-input"><?php echo $form->textField($model,'Zip'); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Zip', array('class'=>'errorMessage left')); ?>
            </div>
            
        </div>
        <div class="row school-row left-20">
             <div class="left">
		<?php echo $form->labelEx($model,'Type', array('class'=>'label')); ?>
		 <span class="short-input"><?php echo $form->textField($model,'Type',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Type', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="left">
		<?php echo $form->labelEx($model,'Phone', array('class'=>'label')); ?>
		<span class="short-input"><?php echo $form->textField($model,'Phone',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Phone', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="left">
		<?php echo $form->labelEx($model,'Fax', array('class'=>'label')); ?>
		<span class="short-input"><?php echo $form->textField($model,'Fax',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Fax', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="left">
		<?php echo $form->labelEx($model,'Website', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Website',array('rows'=>2, 'maxlength'=>255)); ?></span>
                <div class="clear"></div>
		<?php echo $form->error($model,'Website', array('class'=>'errorMessage left')); ?>
            </div>
            
            <div class="left">
                <?php echo $form->labelEx($model,'GradeLevel', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'GradeLevel',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'GradeLevel', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model,'LAUSDSchoolCalendar', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'LAUSDSchoolCalendar',array('size'=>60,'maxlength'=>255)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'LAUSDSchoolCalendar', array('class'=>'errorMessage left')); ?>
            </div>
        </div>
        
        <div class="wrapper-styled-buttons bottom-20-p">
            <span class="styled-bttn right-10"><?php echo CHtml::submitButton(Yii::t('Yii', 'Cancel'), array('name'=>'cancel')); ?></span>
            <span class="styled-bttn"><?php echo CHtml::submitButton('OK'); ?></span>
            <div class="clear"></div>
        </div>
        

</div>
<?php $this->endWidget(); ?>

</div><!-- form -->