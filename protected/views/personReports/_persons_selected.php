<?php

Yii::app()->clientScript->registerScript('SelectedPerson', "

$(document).ready(function(){
    
});

");

$this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'personsSelectedGrid',
        'dataProvider'=>$dataProvider,
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforePersonsGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Selected Participants',
        'deleteActionUrl'=>$this->createUrl('delete'),
        'deleteFormId' => 'deleteForm',
        'addFormId' => 'addForm',
        'editFormId' => 'editForm',
        'editHiddenFormId' => 'editEntityIdHidden',
        'deleteHiddenFormId' => 'deleteEntityIdHidden',        
        'addButtonVisible' => false,
        'editButtonVisible' => false,
        'deleteButtonVisible'=>true,
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
            array(
               'name' => 'HomePhone',
               'value' => 'Localization::FormatPhone($data->HomePhone)'
            ),
            'EmailAddress',
            array(
               'name' => 'Type', 
               'header'=>'Role',
               'value'=>'$data->Role->Name'
             ),
            )
          
    ));
?>
