<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vkharyt
 * Date: 1/6/12
 * Time: 5:53 PM
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.extensions.phpexcel.JPhpExcel');

class ReportsController extends BaseReportingController
{
    public $layout='//layouts/reporting';
    private $report;
    private $excelReport;
    public $buttons;
    
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', 'users'=>array('@')),
            array('deny', 'users'=>array('*')),
        );
    }

    public function actionProgramSessionsPersons()
    {
        $this->report =  new ProgramsReport; //new AttendanceReport;
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
        $perfectAttendance = $_POST['perfectAttendance'];
        
        // $dataToExport = 
        $this->render('ProgramsReport', array( //'ProgramSessionsPersons',array(
            'model'=>$this->report->report($programId, $sessions, $schools, $sites, $fromDate, $toDate, $showHours, $perfectAttendance),
            'programId'=>$programId,
            'sessions'=>$sessions,
            'schools'=>$schools,
            'sites'=>$sites,
            'fromDate'=>$fromDate,
            'toDate'=>$toDate,
            'showHours'=>$showHours,
            'viewSummary'=>$viewSummary,
            'perfectAttendance'=>$perfectAttendance
        ));//, true); 
        
       // $this->actionExcel($dataToExport);
        
        
    } 

    public function actionExcel () //($dataToExport)
    {    
     
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=ProgramReport_".date('Y-m-d', time()).'_'.date('H', time()).'-'.date('i', time()).".xls");
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
    
    public function actionIndex()
    { 
        $this->render('reports');
    }

    function unionArrays($arrFirst, $arrSecond)
    {
        $tempArray = array();
        foreach($arrFirst as $key=>$value)
        {
            $tempArray[$key] = $value;
        }
        foreach($arrSecond as $key=>$value)
        {
            $tempArray[$key] = $value;
        }
        return $tempArray;
    }
}
