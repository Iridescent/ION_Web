<?php

Yii::app()->clientScript->registerScript('SearchSites', "

$(document).ready(function(){
    $('#searchButton').click(function(){
        $.fn.yiiGridView.update('schoolGrid', {
            data: $(this).serialize()
        });
        return false;
    });
});

function beforeSitesGridUpdate(id, options)
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
        'id'=>'sitesGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforeSitesGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Households',
        'addActionUrl'=>$this->createUrl('edit'),
        'editActionUrl'=>$this->createUrl('edit'),
        'deleteActionUrl'=>$this->createUrl('delete'),
        'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
        'idParameterName'=>'schoolId',
        'columns'=>array(
		'Name',
		'Type',
		//'Location',
		'Address',
            ),
    )); 
?>
 

</div>