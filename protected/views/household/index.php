<?php

Yii::app()->clientScript->registerScript('SearchHousehold', "

$(document).ready(function(){
    $('#searchButton').click(function(){
        $.fn.yiiGridView.update('householdGrid', {
            data: $(this).serialize()
        });
        return false;
    });
});

function beforeHouseholdGridUpdate(id, options)
{
    options.url += '&filter=' + $('#filter').val();
}

");

Yii::app()->clientScript->registerScript("UploadFileScript", "
    
function UploadBatchFile()
{
    $('#UploadDialog').dialog('open');
    $('#uploadResult').empty();
}

", CClientScript::POS_HEAD);

?>

<div style="margin: 10px;">
    <div>
        <span class='long-input left left-5'><?php echo CHtml::textField('filter', ''); ?></span>
        <span class="styled-bttn right right-5 styled-bttn-long"><?php echo CHtml::button('GO', array ('id'=>'searchButton')); ?></span>
        <div class="clear"></div>
    </div>

<?php $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'householdGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforeHouseholdGridUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Households',
        'addActionUrl'=>$this->createUrl('edit'),
        'editActionUrl'=>$this->createUrl('edit'),
        'deleteActionUrl'=>$this->createUrl('delete'),
        'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
        'importButtonVisisble'=>true,
        'importActionHandler'=>'UploadBatchFile()',
        'idParameterName'=>'householdId',
        'columns'=>array(
		'Name',
		'Address',
		//'Location',
		'ZIPPostal',
                array(
                    'name'=>'PicasaLink',
                    'type'=>'raw',
                    'value'=>'!empty($data->PicasaLink)? CHtml::link(CHtml::image("images/logo_picasa.png", "Picasa", array("class"=>"header-image")), $data->PicasaLink, array(\'target\'=>"_blank")):""',
                ),
            ),
    )); ?>

</div>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'UploadDialog',
    'options'=>array(
        'title'=>'Upload',
        'modal'=>true,
        'width'=>640,
        'height'=>480,
        'resizable' => false,
        'autoOpen'=>false,
    ),
));
?>

<div class="upload-content-wrapper"><!--START [upload-content-wrapper]-->
    <h2>Batch upload</h2>
    
    <div class="button-upload-wrapper"><!--START [button-upload-wrapper]-->
    <?php $this->widget('application.extensions.EAjaxUpload.EAjaxUpload',
        array(
            'id'=>'uploadFile',
            'config'=>array(
                'action'=>Yii::app()->createUrl('batch/UploadHouseholdsParticipants'),
                'allowedExtensions'=>UploadDocumentConfig::$AllowedExtensions,
                'multiple'=>false,
                'sizeLimit'=>UploadDocumentConfig::$MaximumSizeLimit,
                'onComplete'=>"js:function(id, fileName, responseJSON){ 
                    $('#uploadResult').empty();
                    $('#uploadResult').html(responseJSON.processResult);
                    }",
                )
    )); ?>

    </div><!--END [button-upload-wrapper]-->

    <div class="upload-result-wrapper"><!--START [upload-result-wrapper]-->
        <h3>Result uploading</h3>
        <div id="uploadResult">
        </div>
        <div class="download-template">
        <?php echo '* Click ', CHtml::link('here',array('batch/DownloadHouseholdsParticipantsTemplate')), ' to download a template'; ?>
        </div>
    </div><!--END [upload-result-wrapper]-->
    
</div><!--END [upload-content-wrapper]-->

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>