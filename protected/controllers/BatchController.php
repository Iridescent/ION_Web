<?php

class BatchController extends Controller{
    public function accessRules(){
        return array(
            array('allow', 'users'=>array('@')),
            array('deny', 'users'=>array('*')),
            );
    }
    
    public function actionUploadHouseholdsParticipants(){
        Yii::import("application.extensions.EAjaxUpload.qqFileUploader");
 
        $folder = UploadDocumentConfig::$UploadFolder;
        $allowedExtensions = UploadDocumentConfig::$AllowedExtensions;
        $sizeLimit = UploadDocumentConfig::$MaximumSizeLimit;
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        
        if ($result['success']){
            $processor = new HouseholdParticipantsDocumentProcessor();
            $result['processResult'] = $this->renderPartial('uploadResult',
                    array('model'=>$processor->Process($folder . $result['filename'])), true);
        }
        $this->ajaxResopnse($result);
    }
    
    public function actionUploadProgramsSessions(){
        
    }
    
    public function actionDownloadHouseholdsParticipantsTemplate(){
        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment; filename="HouseholdsParticipantsTemplate.xlsx";');
        readfile('templates/HouseholdParticipantsTemplate.xlsx');
        Yii::app()->end();
    }
}

?>
