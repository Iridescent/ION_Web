<?php

Yii::app()->clientScript->registerScript('SearchProgram', "

$(document).ready(function(){
    $('#searchButton').click(function(){
        $.fn.yiiGridView.update('programGrid', {
            data: $(this).serialize()
        });
        return false;
    });
});

function beforeProgramGridUpdate(id, options)
{
    options.url += '&DescriptionOrType=' + $('#DescriptionOrType').val();
}

");
?>

<div style="margin: 10px;">
    
    <div>
        <span class='long-input left left-5'><?php echo CHtml::textField('DescriptionOrType', ''); ?></span>
        <span class="styled-bttn right right-5 styled-bttn-long"><?php echo CHtml::button('GO', array ('id'=>'searchButton')); ?></span>
        <div class="clear"></div>
    </div>

    <?php 
    $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'programGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforeProgramGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Programs',
        'addActionUrl'=>$this->createUrl('edit'),
        'editActionUrl'=>$this->createUrl('edit'),
        'deleteActionUrl'=>$this->createUrl('delete'),
        'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
        'idParameterName'=>'programId',
        'columns'=>array(
            'Description',
            array(
                'name'=>'StartDate',
                'header'=>'Start',
                'value'=>'Localization::ToClientDate($data->StartDate)',
            ),
            array(
                'name'=>'EndDate',
                'header'=>'End',
                'value'=>'Localization::ToClientDate($data->EndDate)',
                ),
            array(
               'name' => 'ProgramType',
               'header'=>'Type',
               'value'=>'$data->ProgramTypeRelation->Name'
             ),
            array(
                'name'=>'PicasaLink',
                'type'=>'raw',
                'value'=>'!empty($data->PicasaLink)? CHtml::link(CHtml::image("images/logo_picasa.png", "Picasa", array("class"=>"header-image")), $data->PicasaLink, array(\'target\'=>"_blank")):""',
            ),
            array(
                'name'=>'GDocLink',
                'type'=>'raw',
                'value'=>'!empty($data->GDocLink)? CHtml::link(CHtml::image("images/logo_gdoc.png", "GoogleDoc Link", array("class"=>"header-image")), $data->GDocLink, array(\'target\'=>"_blank")):""',
            ),
            array(
                'name'=>'GDocExtra',
                'type'=>'raw',
                'value'=>'!empty($data->GDocExtra)? CHtml::link(CHtml::image("images/logo_gdoc.png", "GoogleDoc Link", array("class"=>"header-image")), $data->GDocExtra, array(\'target\'=>"_blank")):""',
            ),        
            array(
                'name'=>'GDocLog',
                'type'=>'raw',
                'value'=>'!empty($data->GDocLog)? CHtml::link(CHtml::image("images/logo_gdoc.png", "GoogleDoc Link", array("class"=>"header-image")), $data->GDocLog, array(\'target\'=>"_blank")):""',
            ),            
            array(
                'name'=>'BasecampLink',
                'type'=>'raw',
                'value'=>'!empty($data->BasecampLink)? CHtml::link(CHtml::image("images/logo_basecamp.png", "Basecamp link", array("class"=>"header-image")), $data->BasecampLink, array(\'target\'=>"_blank")):""',
            )
        ),
    )); 
    
    ?>

</div>

