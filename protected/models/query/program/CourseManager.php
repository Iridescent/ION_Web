<?php

class CourseManager extends CActiveRecord {
 
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'view_coursemanager';
    }
    
    public function Autocomplete($name, $limit=10){
        $criteria = new CDbCriteria;
        
        $criteria->condition = '(LastName LIKE :Name OR FirstName LIKE :Name)';
        $criteria->params = array(':Name'=>'%'.$name.'%');
        $criteria->limit = $limit;
        
        if (!UserIdentity::IsSuperAdmin()){
            $criteria->condition = $criteria->condition . ' AND Location IN (:locations)';
            $criteria->params[':locations'] = implode (",", UserIdentity::Location());
        }
        
        return $this->findAll($criteria);
    }
}

?>
