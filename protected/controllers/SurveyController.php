<?php

class SurveyController extends BaseManageController {
    public function secondLevelNavigationType() {
        return NavigationType::MANAGE_SURVEYS;
    }
    
    public function filters(){
        return array('accessControl',);
    }
    
    public function accessRules() {
        return array(
            array('allow', 'users'=>array('@')),
            array('deny', 'users'=>array('*')),
            );
    }
    
    public function actionEdit() {        
        $surveyId;
        
        if (isset($_POST['surveyId'])){
            $surveyId = $_POST['surveyId'];
        }
        
        $model = $surveyId ? Survey::model()->findByPk($surveyId) : new Survey;
        
        if(isset($_POST['Survey'])){
            if ($_POST['cancel']) {
                $this->redirect(array('index'));
            }
            $model->attributes = $_POST['Survey'];
            $model->ProgramId = $_POST['Survey']['ProgramId'];
            if($model->save()){
                $this->redirect(array('index'));
            }
        }
        
        $this->render('edit', array('model'=>$model));
    }
    
    public function actionIndex() {
        $model=new QuerySurvey();
        
        /*$model->unsetAttributes();
        if(isset($_GET['QueryProgram'])){
            $model->attributes=$_GET['QueryProgram'];
        }
        if (isset($_GET['DescriptionOrType'])){
            $model->DescriptionOrType = $_GET['DescriptionOrType'];
        }*/
        
        $this->render('index', array('model'=>$model));
    }
}

?>
