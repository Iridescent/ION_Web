<?php

class PersonController extends BaseManageController {
    public function secondLevelNavigationType() {
        return NavigationType::MANAGE_PERSONS;
    }
    
    public function accessRules(){
        return array(array('allow', 'users'=>array('@')),array('deny', 'users'=>array('*')),);
    }
    
    public function actionEdit($householdId = NULL){
        $personId;
     
        if (isset($_POST['personId'])){
            $personId = $_POST['personId'];
        }
        
        $model = $personId ? $this->loadModel($personId) : new Person();
        if (!$personId && $householdId) {
             $model->Household = $householdId;
             $household = Household::model()->findByPk($householdId);
             $model->AdjustEmergencyContact($household,$model->SchoolRelation);
        }
                
        if(isset($_POST['Person'])){ 
            $model->attributes=$_POST['Person'];
         
            // Household releted Person
            if (isset($householdId)) {
                if ($_POST['cancel']) {
                    $this->redirect(array('household/edit', 'personHouseholdId'=>$householdId));
                }
               
                if($model->save()){
                    $this->updateHousehold($model);
                    $this->redirect(array('household/edit', 'personHouseholdId'=>$householdId));
                }                
            }
            else {
                if ($_POST['cancel']) {
                    $this->redirect(array('index'));
                }
                if($model->save()){
                    $this->updateHousehold($model);
                    $model->saveLinks();
                    $this->redirect(array('index'));
                }
            }
        }
        // if Household related Person
        if (isset($householdId)) {
            $this->render('edit_related',array('model'=>$model, 'householdId'=>$householdId));
        } else {
            $this->render('edit',array('model'=>$model));
        }
        
    }

    public function actionDelete(){
        $programId = $_POST['personId'];
        if(Yii::app()->request->isPostRequest)
        {
            $this->loadModel($programId)->delete();

            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        }
        else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    
    public function actionIndex(){
        $model=new QueryPerson();
        $model->unsetAttributes();
        if(isset($_GET['QueryPerson'])){
            $model->attributes=$_GET['QueryPerson'];
        }
        if(isset($_GET['filter'])){
            $model->filter=$_GET['filter'];
        }
	$this->render('index', array('model'=>$model,));
    }
    
    public function actionGetEmergencyContacts($householdId){
        $result = array();
        if ($householdId){
            $household = Household::model()->findByPk($householdId);
            $result[] = (object)array('firstName'=>$household->Emergency1FirstName, 'lastName'=>$household->Emergency1LastName,
                                      'relationship'=>$household->Emergency1Relationship, 'mobile'=>$household->Emergency1MobilePhone);
            $result[] = (object)array('firstName'=>$household->Emergency2FirstName, 'lastName'=>$household->Emergency2LastName,
                                      'relationship'=>$household->Emergency2Relationship, 'mobile'=>$household->Emergency2MobilePhone);
        }
        $this->ajaxResopnse($result);
    }

    public function loadModel($id){
        $model=Person::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='person-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSubtypeByType(){
      $sql = "SELECT ID, PersonType, Name FROM personsubtype ".
             "WHERE PersonType = :type_id";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindValue(':type_id', $_POST['type_id']);
      $data = $command->queryAll(true);;
      $data = CHtml::listData($data,'ID','Name');

      $data = ReportsController::unionArrays(array("_all" => "Select a relationship:") , $data);
      //print_r($data); die();
      foreach($data as $value=>$name)
      {
        echo CHtml::tag('option',
        array('value'=>$value),CHtml::encode($name),true);
      }
    }
   
    
    private function updateHousehold(&$personModel){
        $household = Household::model()->updateByPk($personModel->Household, array(
            'Emergency1FirstName'=>$personModel->Emergency1FirstName,
            'Emergency1LastName'=>$personModel->Emergency1LastName,
            'Emergency1Relationship'=>$personModel->Emergency1Relationship,
            'Emergency1MobilePhone'=>$personModel->Emergency1MobilePhone,
            'Emergency2FirstName'=>$personModel->Emergency2FirstName,
            'Emergency2LastName'=>$personModel->Emergency2LastName,
            'Emergency2Relationship'=>$personModel->Emergency2Relationship,
            'Emergency2MobilePhone'=>$personModel->Emergency2MobilePhone,
        ));
    }
}
