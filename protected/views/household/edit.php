<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.dataTables.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/TableTools.js');

Yii::app()->clientScript->registerScript('SearchPerson', "


function beforePersonGridUpdate(id, options)
{
    options.url += '&filter=' + $('#filter').val();
}");


Yii::app()->clientScript->registerScript('EditPerson', "
    function openDialog_() {
    
        $('#PersonEditDialog').dialog('open');
    }
    
    $(document).ready(function(){
        $('#addEntityButton_').click(function() {
            $('#PersonEditDialog').dialog('open');
            
            
            return false;
        });
        
        $('#addPersonButton').click(function() { 
           
        });
        
        $('#editEntityButton_').click(function() {
            if (selectedRow){
               
                selectedSession = null;
                var idx = IndexOfById(sessionList, selectedRow._aData[0]);
                if (idx > -1){
                    selectedSession = sessionList[idx];
                    FillEditSessionForm();
                    $('#PersonEditDialog').dialog('open');
                    return false;
                }
            }
        });
        
        $('#deleteEntityButton_').click(function() {

        });
    });    

");
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
   
