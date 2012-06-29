<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseModel
 *
 * @author dshyshen
 */
class BaseModel extends CActiveRecord {
    protected function getServerDate($clientDate){
        return Localization::ToServerDate($clientDate, true);
    }
    
    protected function getClientDate($serverDate){
        return Localization::ToClientDate($serverDate);
    }
    
    protected function getServerTime($clientTime){
        return Localization::ToServerTime($clientTime, true);
    }
    
    protected function boolToInt($value) {
        return CommonHelper::BoolToInt($value);
    }
    
    protected function getCurrentDateTime() {
        return date(Localization::SERVER_DATETIME_FORMAT);
    }
    
    protected function jsonToPHP($json) {
        $result = json_decode($json);
        return serialize($result);
    }
    
    public function SetUpdateInfo(){
        $this->LastUpdated = $this->getCurrentDateTime();
        $this->UpdateUserId = Yii::app()->user->id;
    }
    
    /* $clientServer: true - to client format, false - to server */
    public function AdjustDateTimeFields($clientServer){
        
    }
}

?>
