<?php

?>

<div style="margin: 10px 10px 10px 10px;">
<?php $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
    'id'=>'surveyGrid',
    'dataProvider'=>$model->search(),
    'ajaxVar'=>true,
    'ajaxUpdate'=>true,
    'template'=>'{items}{pager}{summary}',
    'title'=>'Surveys',
    'addActionUrl'=>$this->createUrl('edit'),
    'editActionUrl'=>$this->createUrl('edit'),
    'deleteActionUrl'=>$this->createUrl('delete'),
    'deleteButtonVisible'=>Yii::app()->user->checkAccess(UserRoles::SuperAdmin),
    'idParameterName'=>'surveyId',
    'columns'=>array(
        'Title',
        array(
               'name' => 'Session',
               'header'=>'Session',
               'value'=>'$data->SessionRelation->Description'
             ),
        ),
)); 
?>
</div>
