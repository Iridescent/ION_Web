<?php

class PersonSynchronizationResult extends BaseModel {
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return 'personsynchronizationresult';
    }
    
    public function rules() {
        return array(
            array('PersonId, CompleteDate, IsSucceed, Details', 'safe'),
        );
    }
    
     public function beforeSave() {
        $this->CompleteDate = $this->getCurrentDateTime();
        return parent::beforeSave();
    }
}

?>
