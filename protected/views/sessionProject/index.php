<?php

    Yii::app()->clientScript->registerScript('SearchParticipant', "

    $(document).ready(function(){
    
        sessionList = $('#session');
        
        $('#searchButton').click(function(){ 
            $.fn.yiiGridView.update('sessionProjectGrid', {
                data: $(this).serialize()
            }); 
            return false;
        });
        
        $('#session').change(function(){
            $.fn.yiiGridView.update('sessionProjectGrid', {
                 data: $(this).serialize()
            });
            return false;
        });

        $('#program').change(function(){
        
            $.ajax({
                    type: 'POST',
                    url: '".CController::createUrl('SessionsByProgram')."',
                    traditional: true,
                    data: {program_id: $('#program').val()},
                    success: function(data) {
                        if (data){
                            sessionList.empty();
                            sessionList.append(data);
                            
                            $.fn.yiiGridView.update('sessionProjectGrid', {
                                 data: {session: $('#session option:selected').val(),
                                        program: $('#program').val()
                                       }
                            });

                        }
                    }

            });
            
            return false;

        });


    });

    function beforeSessionProjectUpdate(id, options)
    {   
        options.url += '&filter=' + $('#filter').val();
    }

    ");
?>

<?php

            /* Programs section */
            echo CHtml::Label('Programs', 'Programs', array('class'=>'label')); 

            $programList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description');

            echo CHtml::dropDownList('program', '', $programList, array('empty' => 'Select a program..'));
            
            echo CHtml::Label('Sessions', 'Sessions', array('class'=>'label')); 
            echo CHtml::dropDownList('session', 'any', array(), array('empty' => 'any'));
            

?>



<div style="margin: 10px;">
    <div>
        <span class='long-input left left-5'><?php echo CHtml::textField('filter', ''); ?></span>
        <span class="styled-bttn right right-5 styled-bttn-long"><?php echo CHtml::button('GO', array ('id'=>'searchButton')); ?></span>
        <div class="clear"></div>
    </div>

    <?php 
    
        $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'sessionProjectGrid',
        'dataProvider'=> $model->search(), //->toHH($model->ID),,
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'beforeAjaxUpdate'=>'beforeSessionProjectUpdate',
        'template'=>'{items}{pager}{summary}',
        'title'=>'Projects',
        'editActionUrl'=>$this->createUrl('evaluate'),
        'editButtonText' => 'Evaluate',
        'addButtonVisible' => false,
        'deleteButtonVisible' => false,            
        //'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
        'idParameterName'=>'sessionProjectId',
        'columns'=>array(
            array(
               'name' => 'Name', 
               'header'=>'Participant Name',
               'value'=>'$data->PersonRelation->FirstName." ".$data->PersonRelation->LastName'
             ),
            array(
               'name' => 'Household', 
               'header'=>'Household',
               'value'=>'$data->PersonRelation->HouseholdRelation->Name'
             ),
            array(
               'name' => 'Role', 
               'header'=>'Role',
               'value'=>'$data->PersonRelation->Role->Name'
             ),                        
            array(
               'name' => 'Session', 
               'header'=>'Session Name',
               'value'=>'$data->SessionRelation->Description'
             ),
            array(
               'name' => 'Points', 
               'header'=>'Score',
               'value'=>'$data->Points'
             ),
            ),
        )); 
?>

</div>
