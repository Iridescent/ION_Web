<?php Yii::app()->clientScript->registerScript('OpenDialog', "
    
    $(document).ready(function(){
        $('#PersonEditDialog').dialog('open');
        return false;   
   });     
");
        
?>


<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'PersonEditDialog',
        'options'=>array(
            'title'=>'Participant',
            'modal'=>true,
            'width'=>'auto',
            'height'=>'auto',
            'resizable' => false,
            'autoOpen'=>false,
            'open'=> 'js:function(event, ui) { $(".ui-dialog-titlebar-close").click(function() 
                                                                { location.href = "'.Yii::app()->createUrl("/household/edit", array('personHouseholdId'=>$householdId)).'"; }); }'
        ),
    ));
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
 
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
