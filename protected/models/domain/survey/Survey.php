<?php

class Survey extends BaseModel {
    
    public $ProgramId;
    public $JsonQuestions;
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'survey';
    }
    
    public function rules() {
        return array(
            array('Title, SessionId', 'required'),
            array('Title, Description', 'length', 'max' => 255),
            array('ID, Title, Description, Questions, SessionId, Enabled, UpdateUserId, LastUpdated', 'safe'),
        );
    }
    
    public function relations(){
        return array('SessionRelation' => array(self::BELONGS_TO, 'DomainSession', 'SessionId', 'select' => 'Program'),);
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Title' => 'Title',
            'Description' => 'Description',
            'SessionId' => 'Session',
        );
    }
    
    public function QuestionsToJSON() {
        $questions = array();
        if ($this->Questions) {
            $questions = unserialize($this->Questions);
        }
        return json_encode($questions);
    }

    public function beforeSave() {
        $this->Questions = $this->jsonToPHP($this->JsonQuestions);
        $this->SetUpdateInfo();
        return parent::beforeSave();
    }
    
    public function afterFind(){
        $this->ProgramId = $this->SessionRelation->Program;
    }
}

?>
