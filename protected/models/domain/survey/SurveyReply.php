<?php

class SurveyReply extends BaseModel {
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'surveyreply';
    }
}

?>
