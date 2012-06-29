<?php
$this->beginContent('//layouts/layout_main');
$this->navigationMenu = array(
    array('label'=>'Users','url'=>array('user/index'), 'visible'=> UserIdentity::IsSuperAdmin() || UserIdentity::IsLocalAdmin(), 
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_USERS)),
    array('label'=>'Programs','url'=>array('program/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_PROGRAMS)),
    array('label'=>'Households','url'=>array('household/index'), 'visible'=> UserIdentity::IsSuperAdmin() || UserIdentity::IsLocalAdmin(),
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_HOUSEHOLDS)),
    array('label'=>'Participants','url'=>array('person/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_PERSONS)),
    array('label'=>'Schools', 'url'=>array('school/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_SCHOOLS)),
    array('label'=>'Surveys', 'url'=>array('survey/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_SURVEYS)),
    /*array('label'=>'Sites', 'url'=>array('sites/index'), 'visible'=>!Yii::app()->user->isGuest,
        'itemOptions' => $this->getTabItemOptions(NavigationType::MANAGE_SITES)),*/
    );
echo $content;
$this->endContent();
?>