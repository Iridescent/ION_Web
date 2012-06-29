<?php

class AttendanceController extends BaseTrackingController {
    public function filters(){
        return array('accessControl',);
    }

    public function accessRules() {
        return array(
                array('allow', 'users'=>array('@')),
                array('deny', 'users'=>array('*')),
                );
    }
    
    /*public function actionEdit(){
        $attendanceId;
        
        if (isset($_POST['attendanceId'])){
            $personId = $_POST['attendanceId'];
        }
        
        $model = $attendanceId ? $this->loadModel($attendanceId) : new Attendance();
        
        if(isset($_POST['Attendance'])){
            $model->attributes=$_POST['Attendance'];
            if ($_POST['cancel']) {
                $this->redirect(array('index'));
            }                        
            if($model->save()){
                $this->redirect(array('index'));
            }
        }

        $this->render('index');
    }

    public function actionDelete($id){
        if(Yii::app()->request->isPostRequest){
            $this->loadModel($id)->delete();
            if(!isset($_GET['ajax'])){
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else{
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }*/

    public function actionIndex() {
        $this->redirectToHome();
        /*$model=new Attendance();
        $model->unsetAttributes();
        if(isset($_GET['Attendance'])){
            $model->attributes=$_GET['Attendance'];
        }
	$this->render('index', array('model'=>$model,));*/
    }

    public function loadModel($id) {
        $model=Attendance::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
}
