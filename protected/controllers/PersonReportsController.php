<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonReportController
 *
 * @author osenk
 */
class PersonReportsController extends BaseReportingController {
    public $layout='//layouts/reporting_persons';
    private $report;
    private $excelReport;
    public $buttons;
    private $model;
    private $dataProvider;
    
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
        
    public function actionIndex()
    { 
        $person_model=new QueryPerson();
        $person_model->unsetAttributes();
        if(isset($_GET['QueryPerson'])){
            $person_model->attributes=$_GET['QueryPerson'];
        }
        if(isset($_GET['filter'])){
            $person_model->filter=$_GET['filter'];
        }
        if (!isset($_GET["after_add"])){
            Yii::app()->session["selectedPersons"]=null;
        }        
        if (Yii::app()->session["selectedPersons"]===null)
        {
            $dataProvider = new CActiveDataProvider($model,array('data'=>array()));
        }else{
            //$data = $this->loadModelAll(Yii::app()->session["selectedPersons"]);
            $dataProvider = QueryPerson::model()->toReports(Yii::app()->session["selectedPersons"]);
        }
        
	$this->render('index', array('dataProvider'=>$dataProvider, 'person_model' => $person_model,));
    }
    
    public function actionAdd()
    {
        $personId = $_POST['personId'];
        if(Yii::app()->request->isPostRequest){
            $selectedPersons = Yii::app()->session["selectedPersons"];
            if( $selectedPersons=== null)
            {
                  Yii::app()->session["selectedPersons"] = array($personId);                    
            }else{
                  array_push($selectedPersons, $personId);
                  Yii::app()->session["selectedPersons"] = $selectedPersons;
            }
            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index','after_add'=>true));
            }
        }
        else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    
    public function actionDelete()
    {
        $personId = $_POST['personId'];
        if(Yii::app()->request->isPostRequest)
        {
            $selectedPersons = Yii::app()->session["selectedPersons"];
            if(is_array($selectedPersons))
            {
                $temp_arr = array($personId);
                $selectedPersons = array_diff($selectedPersons, $temp_arr);
                $selectedPersons = array_values($selectedPersons);
            }
            if (count($selectedPersons)==0){
                unset(Yii::app()->session["selectedPersons"]);
            }else{
                Yii::app()->session["selectedPersons"] = $selectedPersons;
            }
            
            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        }
        else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        } 
    }
    public function actionGetReport()
    {
        $this->report =  new PersonReport(); //new AttendanceReport;
        $this->layout = '//layouts/view_reporting'; //'//layouts/view_reporting_bare';
        $programId = $_POST['program'];
        $sessions = $_POST['sessions'];
        $schools = $_POST['schools'];
        $sites = $_POST['sites'];
        
        if(isset($_POST['fromDate']) and ($_POST['fromDate'] != NULL)) {
            $fromDate = date ('Y-m-d', strtotime($_POST['fromDate']));     
        } 

        if(isset($_POST['toDate']) and ($_POST['toDate'] != NULL)) {
            $fromDate = date ('Y-m-d', strtotime($_POST['fromDate']));     
        }
        
        $showHours = $_POST['showHours'];
        $viewSummary = $_POST['viewSummary'];
        
        $selectedPersons = Yii::app()->session["selectedPersons"];
        
        // $dataToExport = 
        $this->render('PersonsReport', array( //'ProgramSessionsPersons',array(
            'model'=>$this->report->report($programId, $sessions, $schools, $sites, $fromDate, $toDate, $selectedPersons),
            'programId'=>$programId,
            'sessions'=>$sessions,
            'schools'=>$schools,
            'sites'=>$sites,
            'fromDate'=>$fromDate,
            'toDate'=>$toDate,
            'showHours'=>$showHours,
            'viewSummary'=>$viewSummary,
            'persons' => $selectedPersons
        ));//, true); 
    }
    public function actionExcel () //($dataToExport)
    {    
     
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=PersonReport_".date('Y-m-d', time()).'_'.date('H', time()).'-'.date('i', time()).".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

      /*  $csv_output ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
                        <head>
                        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                        <meta name="author" content="Iridescent" />
                        <title></title>
                        </head>
                        <body>';
        */                
        $csv_output = $_POST['data'];

        $csv_output .='</body></html>';

        print $csv_output; //$dataToExport; 
       
    }  
    
     public function loadModelAll($ids)
    {
        $model=Person::model()->findAllByPk($ids);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
    
    public function loadModel($id)
    {
        $model=Person::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
}

?>
