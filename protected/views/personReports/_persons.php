<?php

Yii::app()->clientScript->registerScript('SearchPersonReports', "

$(document).ready(function(){
    $('#searchButton').click(function(){
        $.fn.yiiGridView.update('personsGrid', {
            data: $(this).serialize()
        });
        return false;
    });
});

function beforePersonsGridUpdate(id, options)
{
    options.url += '&filter=' + $('#filter').val();
}

");
?>

<div class="person_search">
    <div>
        <span class='long-input left left-5'><?php echo CHtml::textField('filter', ''); ?></span>
        <span class="styled-bttn right right-5 styled-bttn-long"><?php echo CHtml::button('GO', array ('id'=>'searchButton')); ?></span>
        <div class="clear"></div>
    </div>
    
    <?php $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'personsGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforePersonsGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Participants',
        'editButtonText' => 'Add to report',
        'addActionUrl'=>$this->createUrl('add'),
        'editActionUrl'=>$this->createUrl('add'),
        'editFormId' => 'searchEditForm',
        'addFormId' => 'searchAddForm',
        'deleteFormId' => 'searchDeleteForm',
        'editHiddenFormId' => 'editEntityIdHiddenSearch',
        'deleteHiddenFormId' => 'deleteEntityIdHiddenSearch',
        //'editActionHandler' => 'return false;',
        //'addActionHandler' => 'return false;',
        'addButtonVisible' => false,
        'editButtonVisible' => true,
        'deleteButtonVisible'=>false,
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
          
    )); ?>
    
</div>
