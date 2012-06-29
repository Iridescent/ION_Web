<?php

class Job extends BaseModel {
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return 'job';
    }
    
    public function rules() {
        return array(
            array('ID, Description, IsSucceed, IsRunning, LastRunDate', 'safe'),
        );
    }
    
    //$direction: TRUE - start, FALSE - stop
    //$is_succeed: TRUE/FALSE
    public function StartStop($direction, $is_succeed = TRUE) {
        $attributes = array();
        if ($direction) {
            $attributes['IsRunning'] = 1;
        }
        else {
            $attributes['IsRunning'] = 0;
            $attributes['IsSucceed'] = $this->boolToInt($is_succeed);
            $attrubutes['LastRunDate'] = $this->getCurrentDateTime();
        }
        $this->updateByPk($this->ID, $attributes);
    }
}

?>
