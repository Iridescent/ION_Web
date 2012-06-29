<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DomainProgram
 *
 * @author dshyshen
 */
class DomainProgram extends BaseModel {
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'programs';
    }
    
    public function relations(){
        return array('SessionList'=>array(self::HAS_MANY, 'DomainSession', 'Program'));
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Description' => 'Name',
            'StartDate' => 'Start',
            'EndDate' => 'End',
            'ProgramType' => 'Type',
            'PicasaLink' => "<img class='header-image' src='images/logo_picasa.png' alt='Picasa' /> Album",
            "GDocLink" => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' /> Attendance",
            "GDocExtra" => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' /> Extra info",
            "GDocLog" => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' /> Call Log",
            "BasecampLink" => "<img class='header-image' src='images/logo_basecamp.png' alt='' /> Basecamp",
        );
    }
    
    public function rules(){
        return array(
            array('Description, PicasaLink, GDocLink, GDocExtra, GDocLog, BasecampLink', 'length', 'max'=>255),
            array('StartDate, EndDate', 'application.extensions.Validators.ClientDateTimeValidator'),
            array('StartDate, EndDate, ProgramType', 'safe'),
        );
    }
    
    public function saveFull($sessionList){
        $succeed = true;
        $transaction=$this->dbConnection->beginTransaction();
        try
        {
            $succeed = $this->save();

            if ($succeed){

                $data = array();
                $idsToDelete = array();
                foreach($this->SessionList as $oldSession){
                    $current = CollectionUtil::Find($sessionList, 'ID', $oldSession->ID);
                    if (!$current){
                        array_push($idsToDelete, $oldSession->ID);
                    }
                    else{
                        $oldSession->attributes = $current;
                        $this->adjustSessionProperties($oldSession, $this->ID);
                        $succeed = $succeed && $oldSession->save();
                    }
                }
                foreach($sessionList as $newSession){
                    if (!$newSession['ID']){
                        $current = new DomainSession();
                        $current->attributes = $newSession;
                        $this->adjustSessionProperties($current, $this->ID);
                        $succeed = $succeed && $current->save();
                    }
                }

                if (count($idsToDelete) > 0){
                    $criteria=new CDbCriteria;
                    $criteria->addInCondition('ID', $idsToDelete);
                    DomainSession::model()->deleteAll($criteria);
                }

                $transaction->commit();
            }
            else{
                $transaction->rollBack();
            }
        }
        catch(Exception $e)
        {
            $succeed = false;
            $transaction->rollBack();
            throw $e;
        }
        return $succeed;
    }
    
    public function deleteFull(){
        $succeed = true;
        $transaction=$this->dbConnection->beginTransaction();
        try
        {
            foreach($this->SessionList as $session){
                $session->delete();
            }
            $this->delete();
            $transaction->commit();
        }
        catch(Exception $e)
        {
            $succeed = false;
            $transaction->rollBack();
            throw $e;
        }
        return $succeed;
    }
    
    public function beforeSave() {
        $this->AdjustDateTimeFields(false);
        $this->SetUpdateInfo();
        return parent::beforeSave();
    }
    
    public function afterFind(){
        $this->AdjustDateTimeFields(true);
    }
    
    public function AdjustDateTimeFields($clientServer){
        if ($clientServer){
            $this->StartDate = $this->getClientDate($this->StartDate);
            $this->EndDate = $this->getClientDate($this->EndDate);
        }
        else{
            $this->StartDate = $this->getServerDate($this->StartDate);
            $this->EndDate = $this->getServerDate($this->EndDate);
        }
    }
       
    private function adjustSessionProperties(&$session, $programId){
        $session->Program = $programId;
        $session->NonSchoolSite = $session->NonSchoolSite == '' ? null : (int)$session->NonSchoolSite;
        $session->SchoolSite = $session->SchoolSite == '' ? null : (int)$session->SchoolSite;
        $session->CourseManager = $session->CourseManager == '' ? null : (int)$session->CourseManager;
        $session->UserManager = $session->UserManager == '' ? null : (int)$session->UserManager;
        $session->AdjustDateTimeFields(false);
    }
}

?>
