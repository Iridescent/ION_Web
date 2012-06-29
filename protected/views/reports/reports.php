<?php
    $cs=Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
?>


    
<input id="id1" type="hidden" value="<?php echo Yii::app()->createUrl("reports/ProgramSessionsPersons");?>">

        <?php

            function unionArrays($arrFirst, $arrSecond)
            {
                $tempArray = array();
                foreach($arrFirst as $key=>$value)
                {
                    $tempArray[$key] = $value;
                }
                foreach($arrSecond as $key=>$value)
                {
                    $tempArray[$key] = $value;
                }
                return $tempArray;
            }

            /* Programs section */
            echo '<div class="reports-programs-row under-toggle">
                <span class="space-for-checkbox"></span>';

            echo CHtml::Label('Programs', 'Programs', array('class'=>'label')); 

            $programList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description');
            $programList = unionArrays(array(""=>"any") , $programList);

            echo '<span class="short-input-select select-reports multi-select left-0">';
            echo CHtml::dropDownList('program', 'any', $programList,
                array(
                'ajax' => array(
                    'type'=>'POST', //request type
                    'url'=>CController::createUrl('misc/SessionMultiSelect'), //url to call.
                    'update'=>'#ss', //selector to update
                    'data'=>array('program_id'=> 'js:$(\'#program\').val()', 'flag' => TRUE), 
                    'beforeSend' => 'function(){
                                      $("#ss").addClass("loading");}',
                    'complete' => 'function(){
                                      $("#ss").removeClass("loading");}',
                ))
               // 'onchange' => 'js: alert($("#program").val());')                                    
             );

            echo'</span>';
            echo '</div>';

        ?>
        
       
        <div id="ss">
            <?php /* Sessions section - dependant */
                $this->renderPartial('/misc/sessionmultiselect', array('data'=>array(), 'flag'=>TRUE));
            ?>
        </div>    
        
<div class="toggle-reports-advanced">       

    <div class="open-close-block">
        <span class="advanced-open"></span>
        <span class="advanced-close"></span>
        <h4>Advanced</h4>
        <div class="clear"></div>
    </div>    
    
<div class="toggle-reports-advanced-content" style="display: block;">
    
    <div class="reports-programs-row">
    <span class="space-for-checkbox"></span>
    <?php echo CHtml::Label('Schools', 'Schools', array('class'=>'label')); ?>
    <span class="select-reports multi-select reports-programs-schools">
            <?php $schoolList = CHtml::listData(School::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); 
            $this->widget('ext.multiselect.JMultiSelect',array(
                  'name' => 'schools[]',  
                  'data'=> $schoolList,
                  // additional javascript options for the MultiSelect plugin
                  'options'=>array(
                    'header'=>true,
                    'height'=>175,
                    'maxWidth'=>225,
                   // 'checkAllText'=>Yii::t('application','Check all'),
                    'uncheckAllText'=>Yii::t('application','Uncheck all'),
                    'noneSelectedText'=>Yii::t('application','any'),
                    'selectedText'=>Yii::t('application','# selected'),
                    'selectedList'=>true,
                    'show'=>'',
                    'hide'=>'',
                    'autoOpen'=>false,
                    'multiple'=>true,
                    'classes'=>'',
                    'position'=>array(),
                    // set this to true, if you want to use the Filter Widget
                    'filter'=>false,          

                  )
            ));  
            ?>
    </span>    
    <span class="space-for-checkbox">
        <span class="niceCheck">
            <?php echo CHtml::checkBox('viewSummary', false, array('onclick'=>'changeCheck(this)')); //echo CHtml::checkBox('showHours', false, array('onclick'=>'changeCheck(this)')); ?>
        </span>
    </span>
    <?php echo CHtml::Label('Summary view', 'viewSummary', array('class'=>'label')); //echo CHtml::Label('Show hours', 'showHours', array('class'=>'label')); ?>    
    </div>    

    <div class="reports-programs-row">
    <span class="space-for-checkbox"></span>    
    <?php echo CHtml::Label('Sites', 'Sites', array('class'=>'label')); ?>    
    <span class="select-reports multi-select reports-programs-schools">
            <?php $sitesList = CHtml::listData(Sites::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); 

            $this->widget('ext.multiselect.JMultiSelect',array(
                  'name' => 'sites[]',  
                  'data'=> $sitesList,
                  // additional javascript options for the MultiSelect plugin
                  'options'=>array(
                    'header'=>true,
                    'height'=>175,
                    'minWidth'=>225,
                   // 'checkAllText'=>Yii::t('application','Check all'),
                    'uncheckAllText'=>Yii::t('application','Uncheck all'),
                    'noneSelectedText'=>Yii::t('application','any'),
                    'selectedText'=>Yii::t('application','# selected'),
                    'selectedList'=>true,
                    'show'=>'',
                    'hide'=>'',
                    'autoOpen'=>false,
                    'multiple'=>true,
                    'classes'=>'attendance',
                    'position'=>array(),
                    // set this to true, if you want to use the Filter Widget
                    'filter'=>false,          

                  )
            ));  
            ?>        
    </span>  
  
    <span class="space-for-checkbox">
        <span class="niceCheck">
            <?php echo CHtml::checkBox('showHours', false, array('onclick'=>'changeCheck(this)')); ?>
        </span>
    </span>
 
    <?php echo CHtml::Label('Show hours', 'showHours', array('class'=>'label')); ?>    
    </div>  

    <div class="reports-programs-row">
        <span class="space-for-checkbox"></span>    
        <?php echo CHtml::Label('Period', 'Period', array('class'=>'label')); ?>    

        <span class="short-input-calendar-reports">
            <?php          
                           /*   $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                               // 'model'=>$model, //Model object
                              //  'language' => 'de',
                                'id'=>'datepicker-left-reports-period',  
                                'name' => 'fromDate',
                               // 'attribute'=>'DateOfBirth', //attribute name
                                //'mode'=>'date', //use "time","date" or "datetime" (default)                   
                                'options'=>array(
                                            'showAnim'=>'show',
                                            'showOn'=>'button',
                                            'changeYear' => 'true',
                                            'yearRange' => '1940:+0',
                                            'changeMonth' => 'false',
                                            'minDate'=>'2008-01-01', // HARDCODED
                                            'maxDate'=>'new Date()',
                                            'dateFormat'=>'yy-mm-dd',
                                           // 'buttonImage' =>  Yii::app()->request->baseUrl . '/images/calendar.gif',
                                           // 'buttonImageOnly' => true,
                                            //'themeUrl'=>'/js/jquery-ui-1.8/themes',
                                            //'theme'=>'ui-lightness',
                                        ),
                                 'htmlOptions'=>array('readonly'=>'readonly', 'size'=>'15'),
                            )); */

                echo CHtml::textField('fromDate', '', array('id'=>'datepicker-left-reports-period'));
            ?>

        </span>

        <span class="short-input-calendar-reports"> 
          <?php 
             echo CHtml::textField('toDate', '', array('id'=>'datepicker-right-reports-period'));
          ?>    

        </span>


    </div> 
    
       <?php //echo CHtml::button('Reset dates', array('onclick'=>"js: $('#fromDate').val(''); $('#toDate').val('');")) ?>

</div>
      
</div>  
    <script type="text/javascript">
    
    	$(document).ready(function(){
    		if ($('.select-reports #program option:selected').text() == 'any'){
    			$('.space-for-perfectAttendance').css('display','none');
    			$('.perfectAttendance-label').css('display','none');
    		}
    		$('.select-reports #program').change(function(){
    			if ($('.select-reports #program option:selected').text() == 'any'){
	    			$('.space-for-perfectAttendance').css('display','none');
	    			$('.perfectAttendance-label').css('display','none');
	    		}else{
	    			$('.space-for-perfectAttendance').css('display','block');
	    			$('.perfectAttendance-label').css('display','block');
	    		}
    		});
    	});

      // $("#showAdvanced").toggle(function(){$(this).attr("src", window.location.pathname+"/images/down.jpg");}, function(){$(this).attr("src", window.location.pathname+"/images/up.jpg");});
        $("#showAdvanced").toggle(function(){$(this).attr("src", $(this).attr("tag")+"/images/downarrow.png");}, function(){$(this).attr("src", $(this).attr("tag")+"/images/uparrow.png");});
        function changeUrl()
        {
            var url =$("#id1").val();
            url=url+"&programId="+$("#programs").val();
            url=url+"&sessionId="+$("#sessions").val();
            //window.location.href=url;
            window.open(url, 'Programs Report');
        }
        $(document).ready(function(){
            $(".advanced").hide();

        });
    </script>

 <?php //echo CHtml::listBox('sessions2', '<any>', array()); ?>    
<?php


//$data= CHtml::listData(Color::model()->findAll(), 'ID', 'Name');
/* $this->widget('ext.EchMultiselect.EchMultiselect', array(
    // the data model associated with this widget:
   // 'model' => $model,
     // the attribute associated with drop down list of this widget:
  //  'dropDownAttribute' => 'color',     
    // data for generating the options of the drop down list:
    'data' => array(),    
    // the name of the drop down list (this must be set if 'model' and 'dropDownAttribute' are not set):
    'name' => 'sessions11',
    // the selected input value(s) (used only if 'model' and 'dropDownAttribute' are not set)
    'value' => array(), 
    // additional HTML attributes for the drop down list: 
    'dropDownHtmlOptions'=> array(
        'style'=>'width:508px;',
    ),
    // options for the jQuery UI MultiSelect Widget
    // (see the project page for available options):
    'options' => array( 
        'header'=>true,
        'height'=>175,
        'minWidth'=>225,
        'checkAllText'=>Yii::t('application','Check all'),
        'uncheckAllText'=>Yii::t('application','Uncheck all'),
        'noneSelectedText'=>Yii::t('application','Select an Option'),
        'selectedText'=>Yii::t('application','# selected'),
        'selectedList'=>false,
        'show'=>'',
        'hide'=>'',
        'autoOpen'=>false,
        'multiple'=>true,
        'classes'=>'',
        'position'=>array(),
        // set this to true, if you want to use the Filter Widget
        'filter'=>false,
 
    ),
    // options for the jQuery UI MultiSelect Filter Widget 
    'filterOptions'=> array(
        'label' => Yii::t('application','Filter:'),
        'width'=>100,
        'placeholder'=>Yii::t('application','Enter keywords'),
        'autoReset'=>false,
    ),
));
*/




    

