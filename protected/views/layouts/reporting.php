<?php $this->beginContent('//layouts/layout_reporting'); ?>

<div id="programs" class="single-left-tab" style="display: block;">
    <div class="reports-programs-page" >
        <div class="data-inside-reports">
            <?php echo CHtml::beginForm($this->createUrl('ProgramSessionsPersons'), 'post', array('target' => '_blank')) ?>
                                <span class="bttn-get-repord">
                                        <?php //echo CHtml::linkButton('Generate report', array('id'=>'btnGenerateReport', 'style' => 'float: right', 'class' => 'button-link', 'target' => '_blank')); ?>                            

                                            <?php  
                                            echo CHtml::openTag('button', array('id'=>'bttn-get-report', 'onclick'=>'js: if (!$("#program").val()) {alert ("You should select a Program first"); return false;};' ));
                                            echo Yii::t('Yii', 'GET<br>REPORT');
                                            echo CHtml::closeTag('button');
                                            //echo CHtml::submitButton('Generate report', array('id'=>'some')); ?>                                    

                                    </span>

                                    <?php echo $content; ?>

            <?php echo CHtml::endForm() ?>
        </div> <!-- data-inside-reports -->                
    </div><!-- reports-program-page -->
</div>

<?php $this->endContent(); ?>