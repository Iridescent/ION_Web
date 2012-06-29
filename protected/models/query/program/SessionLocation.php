<?php

class SessionLocation extends CActiveRecord  {
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'view_sessionlocation';
    }
    
    public function Autocomplete($name, $limit=10){
        $citeria = new CDbCriteria;
        
        $citeria->condition = 'Name LIKE :Name';
        $citeria->params = array(':Name'=>'%'.$name.'%');
        $citeria->limit = $limit;
        
        if (!UserIdentity::IsSuperAdmin()){
            $criteria->condition = $criteria->condition . ' AND Location IN (:locations)';
            $criteria->params[':locations'] = implode (",", UserIdentity::Location());
        }
        
        return $this->findAll($citeria);
    }
}
?>
