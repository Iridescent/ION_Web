<?php

class SessionProjectController extends BaseSessionProjectController {
    
    public function actionIndex(){
        
        $model=new QuerySessionProject();
        $model->unsetAttributes();
        if(isset($_GET['QuerySessionProject'])){
            $model->attributes=$_GET['QuerySessionProject'];
        }
        if(isset($_GET['filter'])){
            $model->filter=$_GET['filter'];
        }
        if(isset($_GET['session']) && $_GET['program'] != ''){
            $model = $model->bySession();
        }
        
        $this->render('index', array('model'=>$model));
    }
    
    
    public function actionEvaluate(){
        
        $sessionProjectId;
      
        if (isset($_POST['sessionProjectId'])){
            $sessionProjectId = $_POST['sessionProjectId'];
        }

        $model = $sessionProjectId ? $this->loadModel($sessionProjectId) : new SessionProject();
        
        $this->render('edit', array('model'=>$model));
    }
    
    public function loadModel($id){	
        $model = SessionProject::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
    
    public function actionSessionsByProgram() {
        $data=DomainSession::model()->findAll('Program=:program_id',
                      array(':program_id'=>(int) $_POST['program_id']), 'ID', 'Description');

        if (!empty($data)) {
            $data=CHtml::listData($data,'ID','Description');
        } else {
            $data = ReportsController::unionArrays(array("_any" => "any") , $data);    
        }
        
        foreach($data as $value=>$name) {
            echo CHtml::tag('option',
                array('value'=>$value),CHtml::encode($name),true);
        }
    }

    
    public function actionError() {
        $error=Yii::app()->errorHandler->error;
        if(!$error) {
            $this->redirectToHome();
            return;
        }
        Yii::log($error);
        $this->render('error');
    }
}
?>
