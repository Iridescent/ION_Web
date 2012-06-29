<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author dshyshen
 */
class DomainSession extends BaseModel {
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'sessions';
    }
    
    public function relations(){
        //return array('Program'=>array(self::BELONGS_TO, 'DomainProgram', 'Program'));
        return array(
            'SchoolSiteRelation'=>array(self::BELONGS_TO, 'School', 'SchoolSite', 'select'=>'ID, Name'),
            'NonSchoolSiteRelation'=>array(self::BELONGS_TO, 'Sites', 'NonSchoolSite',  'select'=>'ID, Name'),
            'CourseManagerRelation'=>array(self::BELONGS_TO, 'Person', 'CourseManager',  'select'=>'ID, LastName, FirstName'),
            'UserManagerRelation'=>array(self::BELONGS_TO, 'User', 'UserManager',  'select'=>'ID, LastName, FirstName'),
            );
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Program' => 'Program',
            'StartDate' => 'Start Date',
            'StartTime' => 'Start Time',
            'Description' => 'Description',
            'Instructors' => 'Instructors',
            'NonSchoolSite' => 'NonSchool Site',
            'SchoolSite' => 'School Site',
            'CourseManager' => 'Course Manager',
            'UserManager' => 'User Course Manager',
            'Notes' => 'Notes',
            );
    }
    
    public function rules(){
        return array(
            array('Description', 'length', 'max'=>255),
            //array('StartDate', 'application.extensions.Validators.ClientDateTimeValidator'),
            array('StartDate, StartTime, Instructors, Notes, NonSchoolSite, SchoolSite, CourseManager, UserManager', 'safe'),
        );
    }
    
    public function AdjustDateTimeFields($clientServer){
        if($clientServer){
            
        }
        else{
            $this->StartDate = $this->getServerDate($this->StartDate);
            $this->StartTime = $this->getServerTime($this->StartTime);
        }
    }
}

?>
