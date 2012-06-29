<div style="margin: 10px;">
    <?php $this->widget('application.extensions.KeyTagGridView.KeyTagGridView', array(
        'id'=>'attendanceGrid',
        'dataProvider'=>$model->search(),
        'ajaxVar'=>true,
        'ajaxUpdate'=>true,
        'template'=>'{items}{pager}{summary}',
        'title'=>'Participants',
        'actionButtons'=>array(),
        'columns'=>array(
		'ID',
		'Person',
		'Session',
		'Keytag',
		'UpdateUserId',
	),
    )); ?>
</div>