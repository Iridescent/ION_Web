<?php

class QueryProgram extends CActiveRecord {
    
    public $DescriptionOrType="";
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return "programs";
    }
    
    public function relations(){
        return array('ProgramTypeRelation'=>array(self::BELONGS_TO, 'ProgramType', 'ProgramType', 'select'=>'Name'));
    }

    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Description' => 'Name',
            'StartDate' => 'Start',
            'EndDate' => 'End',
            'PicasaLink' => "<img class='header-image' src='images/logo_picasa.png' alt='Picasa' />",
            'GDocLink' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' />",
            'GDocExtra' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' />",
            'GDocLog' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' />",
            'BasecampLink' => "<img class='header-image' src='images/logo_basecamp.png' alt='GoogleDoc Link' />",
        );
    }
    
    public function search($pageSize=20){
        $criteria=new CDbCriteria;
        
        $criteria->select = 'ID, Description, StartDate, EndDate, 
                PicasaLink, GDocLink, GDocExtra, GDocLog, BasecampLink';
        $criteria->with = array('ProgramTypeRelation');
        $criteria->compare('Description', $this->DescriptionOrType, true, 'OR');
        $criteria->compare('ProgramType', $this->DescriptionOrType, true, 'OR');
        
        $sort = new CSort;
        $sort->attributes = array(
            'ProgramType'=>array(
                'asc'=>'ProgramTypeRelation.Name ASC',
                'desc'=>'ProgramTypeRelation.Name DESC',
            ),
            '*',
        );
        
        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'sort'=>$sort, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
}

?>
