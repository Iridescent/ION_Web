

<script src="js/jquery.pack.js"></script> 
<script src="js/jquery.appendo.js"></script> 
<?php
$this->pageTitle='Upload';//Yii::app()->name . ' - Upload';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Upload Video|Image</h1>

<div class="form">

<?php $form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'upload-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )
); ?>  
   <?php echo CHtml::hiddenField('MAX_FILE_SIZE', Yii::app()->params['commonMedia']['maxFileSize']); ?>
   <?php //echo $errorSummary($model); ?>
    <?php
        foreach(Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
    ?>
    <div class="row">     
        <?php echo CHtml::fileField('uploadvideo', 'Select video file..', array('types'=>'mov, flv, mpeg')); ?> 
    </div>

    
    <div class="row">         
        <?php echo CHtml::fileField('uploadimage', 'Select image file..'); ?> 
    </div>    
   <?php
  /* $this->widget('application.extensions.jwplayer.JWplayer', array(
                    'options' => array(
                        'file' => Yii::app()->getBasePath().Yii::app()->params['videoUploadOriginalPath'].'Kids.mov'
                    ),
                )); 
    */
   ?> 
   <?php echo CHtml::submitButton('Upload'); ?> 
    
<?php $this->endWidget(); ?>
        
</div><!-- form -->
