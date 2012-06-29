<?php

class QuerySessionProject extends CActiveRecord {
    
    public $filter = "";
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'sessionproject';
    }
    
    public function relations(){
        return array(
            'SessionRelation'=>array (self::BELONGS_TO, 'Session', 'SessionId', 'select'=>'ID, Description'),
            'PersonRelation'=>array (self::BELONGS_TO, 'QueryPerson', 'PersonId', 'select'=>'*'),
            'PersonRelation_1'=>array (self::BELONGS_TO, 'QueryPerson', 'PersonId', 'select'=>'*'),
            'VideoRelation'=>array (self::BELONGS_TO, 'File', 'VideoId', 'select'=>'*'),
            'ImageRelation'=>array (self::BELONGS_TO, 'File', 'ImageId', 'select'=>'*'),
            
            'HouseholdRelation'=>array (self::HAS_ONE, 'QueryHousehold', 'Household', 'through' => 'PersonRelation', 'select' => 'Name'),
            'RoleRelation'=>array (self::HAS_ONE, 'PersonType', 'Type', 'through' => 'PersonRelation_1', 'joinType' => 'INNER JOIN', 'select' => 'Name')
        );
    }

    public function scopes(){
        
        return array('bySession' => array('condition' => 'SessionId = :session_id', 'params' => array(':session_id'=>(int)$_GET['session']),));
    }
    
    public function search($pageSize=20){
        $criteria=new CDbCriteria;
        
        $with = array();
        
        $with['PersonRelation'] = array();
        $with['SessionRelation'] = array();
        $with['HouseholdRelation'] = array();
        $with['RoleRelation'] = array();
        $criteria->with = $with; 
        
        $criteria->compare('PersonRelation.LastName', $this->filter, true, 'OR');
        $criteria->compare('PersonRelation.FirstName', $this->filter, true, 'OR');
        $criteria->compare('SessionRelation.Description', $this->filter, true, 'OR');
        
        $sort = new CSort();
        $sort->attributes = array(
            'Name'=>array(
                'asc'=>'PersonRelation.LastName ASC',
                'desc'=>'PersonRelation.LastName DESC',
            ),
            'Role'=>array(
                'asc'=>'RoleRelation.Name ASC',
                'desc'=>'RoleRelation.Name DESC',
            ),
            'Household'=>array(
                'asc'=>'HouseholdRelation.Name ASC',
                'desc'=>'HouseholdRelation.Name DESC',
            ),

            'Session'=>array(
                'asc'=>'SessionRelation.Description ASC',
                'desc'=>'SessionRelation.Description DESC',
            ),
            
            '*',
        );

                
        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'sort'=>$sort, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
    
            
}

?>
