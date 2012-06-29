<?php               

        echo CHtml::label('City', 'cities', array('class'=>'label'));
        echo "<span class=\"short-input-select\">";

?>
<span class="select-reports multi-select">
      
            <?php 
            $this->widget('ext.multiselect.JMultiSelect',array(
                  'name' => 'cities[]',  
                  'data'=> $data,
                  'options'=>array(
                    'header'=>true,
                    'height'=>175,
                    'maxWidth'=>225,
                    'uncheckAllText'=>Yii::t('application','Uncheck all'),
                    'noneSelectedText'=>Yii::t('application','any'),
                    'selectedText'=>Yii::t('application','# selected'),
                    'selectedList'=>true,
                    'show'=>'',
                    'hide'=>'',
                    'autoOpen'=>false,
                    'multiple'=>true,       

                  )
            ));  
            ?>
  
</span>    

<div class="clear"></div>

