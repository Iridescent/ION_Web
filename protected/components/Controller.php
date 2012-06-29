<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    public $layout='//layouts/layout_main';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    
    public $navigationMenu=array();

    public function filters()
    {
        return array('accessControl');
    }
    
    protected function redirectToHome()
    {
        if (UserIdentity::IsSuperAdmin() || UserIdentity::IsLocalAdmin()) {
            $this->redirect($this->createUrl('user/index'));
        }
        else {
            $this->redirect($this->createUrl('person/index'));
        }
    }
    
    public function firstLevelNavigationType(){
        return "";
    }
    
    public function secondLevelNavigationType(){
        return "";
    }
    
    public function getTabItemOptions($navigationType){
        return $this->secondLevelNavigationType() == $navigationType ? array('class'=>'active') : array();
    }
    
    public function getAddEditText($id){
        return $id ? "Edit" : "Add";
    }
   
    protected function ajaxResopnse($data){
        header('Content-type: application/json');
        echo CJSON::encode($data);
        Yii::app()->end();
    }        
}