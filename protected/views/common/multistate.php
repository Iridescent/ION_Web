<?php Yii::app()->clientScript->registerScript('OpenDialog', "
    
    $(document).ready(function(){
        $('#saveButton').click(function(){
            //$('#stateEditDialog').dialog('close');
            console.log($('#cities').serializeArray());
            return true;
        }); 
        

   });     
");
        
?>

<?php 
//print $number;
            echo CHtml::label('State', 'states', array('class'=>'label'));
            echo "<span class=\"short-input-select\">";
            echo CHtml::checkBoxList('states', $defaultState, $statesList, 
                        array(
                        'ajax' => array(
                            'type'=>'POST', //request type
                            'url'=> CController::createUrl('site/CityByState'), //url to call.
                            'update'=>'#ss', //selector to update
                            'data'=>array('states[state_id]'=> 'js:$(\'#states\').val()'),
                            //'beforeSend' => 'js: alert($(\'#states_1\').val())'
                        ))                                    
                     ); //textField($model,'Household');
            echo "</span>";
                    echo '<div class="clear"></div>';
          //  echo $form->error($model,'states', array('class'=>'errorMessage right'));
                    
        echo '<div class="clear"></div>';  
        echo '<div id="ss">';
echo $this->renderPartial('//common/multicity', array('data'=>array()));        
        echo '</div>';
        echo "</span>";
        
        echo '<div class="wrapper-styled-buttons">';
        echo '<span class="styled-bttn">';
        echo CHtml::button('Cancel', array ('id'=>'cancelButton'));
        echo '</span>';
        echo '<span class="styled-bttn">';
        echo CHtml::button('OK', array ('id'=>'saveButton')); 
        echo '</span>';
        echo '</div>';
      //  echo $form->error($model,'cities', array('class'=>'errorMessage right'));
?>
        