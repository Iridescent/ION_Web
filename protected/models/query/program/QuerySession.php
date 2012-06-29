<?php

class QuerySession extends CActiveRecord {
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'sessions';
    }
    
    public function SelectListByProgramId($program_id) {
        return $this->findAll('Program=:program_id', array(':program_id' => $program_id), 'ID', 'Description');
    }
}

?>
