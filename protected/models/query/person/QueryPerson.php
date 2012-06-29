<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryPerson
 *
 * @author dshyshen
 */
class QueryPerson extends CActiveRecord {
    
    public $filter = "";
    public $id = null;
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return "persons";
    }
    
    public function relations(){
        return array(
            'Role'=>array(self::BELONGS_TO, 'PersonType', 'Type', 'select'=>'Name'),
            'Relation' => array(self::BELONGS_TO, 'Personsubtype', 'Subtype', 'select'=>'Name'),
            'HouseholdRelation'=>array(self::BELONGS_TO, 'QueryHousehold', 'Household', 'select'=>'Name, Location'),
            'SchoolRelation'=>array(self::BELONGS_TO, 'School', 'School', 'select'=>false),
            );
    }
    
    public function search($pageSize=20){
        $criteria=new CDbCriteria;
        
        $with = array();
        $with['Role'] = array();
        $with['HouseholdRelation'] = array();
       // $this->applySecurity($with);
        $criteria->with = $with;
        $criteria->compare('BarcodeID', $this->filter, true, 'OR');
        $criteria->compare('FirstName', $this->filter, true, 'OR');
        $criteria->compare('LastName', $this->filter, true, 'OR');
        $criteria->compare('Role.Name', $this->filter, true, 'OR');
        $criteria->compare('HouseholdRelation.Name', $this->filter, true, 'OR');
        
        $sort = new CSort();
        $sort->attributes = array(
            'Household'=>array(
                'asc'=>'HouseholdRelation.Name ASC',
                'desc'=>'HouseholdRelation.Name DESC',
            ),
            'Type'=>array(
                'asc'=>'Role.Name ASC',
                'desc'=>'Role.Name DESC',
            ),
            '*',
        );
        
        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'sort'=>$sort, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
    
    public function toReports($ids)
    {
        $criteria=new CDbCriteria;
        
        $id_list = (string) implode(",", $ids);
        $criteria->condition = 't.ID in ('.$id_list.')';  
        $with = array();
        $with['Role'] = array();
        $with['HouseholdRelation'] = array();
        $this->applySecurity($with);
        $criteria->with = $with;

        return new CActiveDataProvider($this, array('criteria'=>$criteria));        
    }
    
    
    public function toHH($hh){
        $criteria=new CDbCriteria;
        
        $criteria->condition = 'Household='.(int)$hh;  
        $with = array();
        $with['Role'] = array();
        $with['HouseholdRelation'] = array();
        $this->applySecurity($with);
        $criteria->with = $with;

        return new CActiveDataProvider($this, array('criteria'=>$criteria));
    }
    
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'BarcodeID' => 'Barcode ',
            'FirstName' => 'First',
            'LastName' => 'Last',
            'HomePhone' => 'Home #',
            'EmailAddress'=>'Email',
            'MobilePhone' => 'Mobile #',
            'Household' => 'Household',
            'EmailAddress' => 'E-mail',
            'PicasaLink' => "<img class='header-image' src='images/logo_picasa.png' alt='Picasa' />",
            'GDocSurvey' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' />",
            'GDocApplication' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' />",            
        );
    }
    
    public function Autocomplete($name, $limit=10){
        $criteria = new CDbCriteria;
        $with = array();
        $this->applySecurity($with);
        $criteria->with = $with;
        $criteria->limit = $limit;
        $criteria->condition = 'LastName LIKE :Name OR FirstName LIKE :Name';
        $criteria->params = array(':Name'=>'%'.$name.'%');
        $criteria->select = 'ID, LastName, FirstName';
        return $this->findAll($criteria);
    }
    
    public function GetNextForSynchronization() {
        $criteria = new CDbCriteria;
        $criteria->alias = 'p';
        $criteria->join='LEFT JOIN personsynchronizationresult psr ON p.ID = psr.PersonId';
        $criteria->condition = 'psr.PersonId IS NULL';
        $criteria->limit = 1;
        return $this->find($criteria);
    }
    
    private function applySecurity(&$with){
        if (!Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
            if (Yii::app()->user->checkAccess(UserRoles::LocalAdmin)) {//local admin
                $with['HouseholdRelation'] = array(
                        'condition'=>'HouseholdRelation.Location = :locationId',
                        'params'=>array(':locationId'=>Yii::app()->user->location),
                    );
            } else {// School representative
                $with['SchoolRelation'] = array(
                        'condition'=>'SchoolRelation.Location=:locationId',
                        'params'=>array(':locationId'=>Yii::app()->user->location),
                    );
            }
        }
    }
}

?>
