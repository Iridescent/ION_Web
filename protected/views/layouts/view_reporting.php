<?php $this->beginContent('//layouts/master_view_reporting'); ?>
    <div id="content-reporting" class="styled-report" >
        
        <?php echo $content; ?>
        <span class="styled-bttn"><?php
            //echo CHtml::link('Export to Excel', array('Reports/excel', 'programId'=>$this->programId, 'sessionId'=>$this->sessionId), array('id'=>'btnExcelExport', 'style' => 'float: right', 'class' => 'button-link', 'target' => '_blank'));                     
           echo CHtml::linkButton('Export', array('submit' =>CController::createUrl('reports/excel'), 'params'=>array('data'=>"js:$('#reportsheet').html()"), 'id'=>'btnExcelExport', 'class'=>'button-link')); // link('Here', array('Reports/excel', 'innerContent'=> 'js:$(\'#reportsheet\').html()'));
        ?>
        </span>
    
<?php $this->endContent(); ?>