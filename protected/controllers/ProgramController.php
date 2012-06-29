<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProgramController
 *
 * @author dshyshen
 */
class ProgramController extends BaseManageController {
    public function secondLevelNavigationType() {
        return NavigationType::MANAGE_PROGRAMS;
    }
    
    public function accessRules(){
        return array(
                array('allow', 'users'=>array('@')),
                array('deny', 'users'=>array('*')),
                );
    }
    
    public function actionEdit(){
        $programId;
        
        if (isset($_POST['programId'])){
            $programId = $_POST['programId'];
        }
        
        $model = $programId ? DomainProgram::model()->findByPk($programId) : new DomainProgram();
        
        if(isset($_POST['DomainProgram'])){
            if ($_POST['cancel']) {
                $this->redirect(array('index'));
            }
            $model->attributes=$_POST['DomainProgram'];
            if($model->saveFull($_POST['sessionList'])){
                $this->redirect(array('index'));
            }
        }
        $this->render('edit',array('model'=>$model));
    }
    
    public function actionDelete(){
        if (isset($_POST['programId'])){
            $model = DomainProgram::model()->findByPk($_POST['programId']);
            $model->deleteFull();
        }
        $this->redirect(array('index'));
    }
    
    public function actionIndex(){
        $model=new QueryProgram();
        $model->unsetAttributes();
        if(isset($_GET['QueryProgram'])){
            $model->attributes=$_GET['QueryProgram'];
        }
        if (isset($_GET['DescriptionOrType'])){
            $model->DescriptionOrType = $_GET['DescriptionOrType'];
        }
	$this->render('index', array('model'=>$model,));
    }
    
    public function actionGetSessionList($programId){
        $result = array();
        if ($programId){ 
            $sessionList = DomainProgram::model()
                    ->with('SessionList', 'SessionList.SchoolSiteRelation', 'SessionList.NonSchoolSiteRelation', 'SessionList.CourseManagerRelation', 'SessionList.UserManagerRelation')
                    ->findByPk($programId)->SessionList;
         
            foreach($sessionList as $session){
                $result[] = array('Id'=>$session->ID, 'Program'=>$session->Program,
                    'StartDate'=>Localization::ToClientDate($session->StartDate),
                    'StartTime'=>Localization::ToClientTime($session->StartTime),
                    'Description'=>$session->Description, 'Instructors'=>$session->Instructors, 'Notes'=>$session->Notes,
                    'SchoolSite'=>array('Id'=>$session->SchoolSiteRelation->ID, 'Name'=>$session->SchoolSiteRelation->Name),
                    'NonSchoolSite'=>array('Id'=>$session->NonSchoolSiteRelation->ID, 'Name'=>$session->NonSchoolSiteRelation->Name),
                    'CourseManager'=>array('Id'=>$session->CourseManagerRelation->ID,
                                           'Name'=>trim($session->CourseManagerRelation->LastName . ' ' . $session->CourseManagerRelation->FirstName)),
                    'UserManager'=>array('Id'=>$session->UserManagerRelation->ID,
                                           'Name'=>trim($session->UserManagerRelation->LastName . ' ' . $session->UserManagerRelation->FirstName)),
                    );
            } 
        } 
        $this->ajaxResopnse($result);
    }
    
    public function actionSessionLocationAutocomplete($term){
        $locations = SessionLocation::model()->Autocomplete($term);
        $result = array();
        foreach($locations as $location) {
            $result[] = array('id'=>$location->ID, 'label'=>$location->Name, 'type'=>$location->Type);
        }
        $this->ajaxResopnse($result);
    }
    
    public function actionPersonAutocomplete($term){
        //$persons = QueryPerson::model()->Autocomplete($term);
        $allUsers = CourseManager::model()->Autocomplete($term);
        $result = array();
        foreach($allUsers as $user) {
            $result[] = array('id'=>$user->ID, 'label'=>$user->LastName . ' ' . $user->FirstName, 'type'=>$user->Type);
        }
               
        $this->ajaxResopnse($result);
    }
}

?>
