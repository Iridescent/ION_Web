<?php

class HomeController extends Controller {
    
    public function actionIndex(){
        $this->redirectToHome();
    }
     
    public function actionLogin() {   
        if (!Yii::app()->user->isGuest)
        {
            $this->redirectToHome();
        }

        $model=new LoginForm;

        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];

            if($model->validate() && $model->login()) {
               $this->redirectToHome();
            }        
        }

        $this->render('login',array('model'=>$model));
    }
    
    public function actionLogout() {
        if (Yii::app()->user->isGuest)
        {
            $this->redirectToHome();
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
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
