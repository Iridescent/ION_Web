<?php $this->beginContent('//layouts/layout_reporting'); ?>
<div class="reports-persons-page-wrapper"><!--START [reports-persons-page-wrapper]-->
<div id="programs" class="single-left-tab" style="display: block;">
    <div class="reports-persons-page" >
        <div class="data-inside-reports">


            <?php echo CHtml::beginForm($this->createUrl('GetReport'), 'post', array('target' => '_blank', 'class' => 'reports-persons-page-form') ) ?>

                                <span class="bttn-get-repord">
                                        <?php //echo CHtml::linkButton('Generate report', array('id'=>'btnGenerateReport', 'style' => 'float: right', 'class' => 'button-link', 'target' => '_blank')); ?>                            

                                            <?php  
                                            echo CHtml::openTag('button', array('id'=>'bttn-get-report'));
                                            echo Yii::t('Yii', 'GET<br>REPORT');
                                            echo CHtml::closeTag('button');
                                            //echo CHtml::submitButton('Generate report', array('id'=>'some')); ?>                                    

                                    </span>

                                    <?php echo $content; ?>

        </div> <!-- data-inside-reports -->                
    </div><!-- reports-program-page -->
</div>
<!--END [reports-persons-page-wrapper]-->

<?php $this->endContent(); ?>