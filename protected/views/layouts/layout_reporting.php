<?php
$this->beginContent('//layouts/layout_main');
$this->navigationMenu = array(
    array('label'=>'Programs', 'url'=>array('reports/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::REPORTING_PROGRAMS)),
    array('label'=>'Persons', 'url'=>array('personReports/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::REPORTING_PERSONS))
        );
echo $content;
$this->endContent();
?>