<?php
    $cs=Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
?>


    
<input id="id1" type="hidden" value="<?php echo Yii::app()->createUrl("GetReport");?>">

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
        echo '<div class="one-left-part-programs"><!--START [one-left-part-programs]-->';
        echo '<div class="reports-programs-row">';

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
                'data'=>array('program_id'=> 'js:$(\'#program\').val()'), 
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
            $this->renderPartial('/misc/sessionmultiselect', array('data'=>array()));
        ?>
    </div>

    <div class="reports-programs-row">
        <?php echo CHtml::Label('Period', 'Period', array('class'=>'label')); ?>    
        <span class="short-input-calendar-reports">
            <?php          
                echo CHtml::textField('fromDate', '', array('id'=>'datepicker-left-reports-period'));
            ?>
        </span>

        <span class="short-input-calendar-reports"> 
            <?php 
                echo CHtml::textField('toDate', '', array('id'=>'datepicker-right-reports-period'));
            ?>    
        </span>
    </div>

    </div><!--END [one-left-part-programs]-->

    <div class="one-right-part-programs"><!--END [one-right-part-programs]-->
    <div class="reports-programs-row">
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
    </div>  
    
  <div class="reports-programs-row">
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
  </div>
    </div><!--END [one-right-part-programs]-->
<div class="clear"></div>
<?php echo CHtml::endForm() ?>
<?php 
    echo $this->renderPartial('_persons_selected', array('dataProvider'=>$dataProvider)); 
    
    echo $this->renderPartial('_persons', array('model'=>$person_model)); 
?>
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
                
                if('#editEntityButton:contains("Add to report")'){
                   $('#editEntityButton').addClass('add-edit-delete-bttn-160'); 
                }
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



    

