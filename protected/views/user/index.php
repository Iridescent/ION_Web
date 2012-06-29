<?php

Yii::app()->clientScript->registerScript('SearchUser', "

$(document).ready(function(){
    $('#searchButton').click(function(){
        $.fn.yiiGridView.update('userGrid', {
            data: $(this).serialize()
        });
        return false;
    });
});

function beforeUserGridUpdate(id, options)
{
    options.url += '&filter=' + $('#filter').val();
}

");
?>

<div style="margin: 10px;">
    <div>
        <span class='long-input left left-5'><?php echo CHtml::textField('filter', ''); ?></span>
        <span class="styled-bttn right right-5 styled-bttn-long"><?php echo CHtml::button('GO', array ('id'=>'searchButton')); ?></span>
        <div class="clear"></div>
    </div>

    <?php $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'userGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforeUserGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Users',
        'addActionUrl'=>$this->createUrl('edit'),
        'editActionUrl'=>$this->createUrl('edit'),
        'deleteActionUrl'=>$this->createUrl('delete'),
        'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
        'idParameterName'=>'userId',
        'columns'=>array(
            'Login',
            'FirstName',
            'LastName',
            array(
               'name' => 'Role', 
               'header'=>'Role',
               'value'=>'$data->RoleRelation->Name'
             ),
            ),
    )); ?>

</div>
