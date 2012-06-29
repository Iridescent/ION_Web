<?php

class UserController extends BaseManageController{  
    public function secondLevelNavigationType() {
        return NavigationType::MANAGE_USERS;
    }
    
    protected $_model;
    public $location = null;
    
    public function accessRules(){
        return array(
            array('allow', 'users'=>array('@'),'expression'=>'Yii::app()->user->checkAccess(\'manageUsers\')'),
            array('deny', 'users'=>array('*')),
        );
    }

    public function actionEdit(){
        $userId;
      
        if (isset($_POST['userId'])){
            $userId = $_POST['userId'];
        }

        $model = $userId ? $this->loadModel($userId) : new User();
        
        if(isset($_POST['User'])){ 
            $this->location = $_POST['geoString'];
            $model->locationHierarchy = CJSON::decode($_POST['geoString'], true); 
            if (isset($model->Role)) {
                $model->prevRole = $model->Role;            
            } else {
                $model->prevRole = $_POST['User']['Role'];
            }
            if (isset($model->Password)) {
                $model->prevPassword = $model->Password;
            } else {
                $model->prevPassword = $_POST['User']['Password'];
            }
             
            $model->attributes=$_POST['User'];
            if ($_POST['cancel']) {
                $this->redirect(array('index'));
            }                        
            if($model->save()){
                $this->redirect(array('index'));
            }
        }

        $this->render('edit',array('model'=>$model));
    }

    public function actionIndex(){
        $model=new QueryUser();
        $model->unsetAttributes();
        if(isset($_GET['QueryUser'])){
            $model->attributes=$_GET['QueryUser'];
        }
        if(isset($_GET['filter'])){
            $model->filter=$_GET['filter'];
        }
        $this->render('index', array('model'=>$model,));
    }

    public function actionDelete()
    {
        $userId = $_POST['userId'];
        if(Yii::app()->request->isPostRequest){
            $this->loadModel($userId)->updateByPk($userId, array('IsDeleted' => 1));

            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        }
        else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    
    public function loadModel($id){	
        $model=User::model()->findByPk($id);
        $this->location = CJSON::encode($model->getLocation());
        
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model){ 
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
