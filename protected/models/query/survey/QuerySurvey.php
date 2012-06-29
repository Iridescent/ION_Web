<?php

class QuerySurvey extends CActiveRecord {
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName()	{
        return 'survey';
    }
    
    public function relations(){
        return array('SessionRelation'=>array(self::BELONGS_TO, 'DomainSession', 'SessionId', 'select'=>'Description'));
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Title' => 'Title',
            'Description' => 'Description',
        );
    }
    
    public function search($pageSize=20){
        $criteria=new CDbCriteria;
        $sort = new CSort;
        
        $criteria->select = 'ID, Title';
        $criteria->with = array('SessionRelation');
        //$criteria->compare('Description', $this->DescriptionOrType, true, 'OR');
        //$criteria->compare('ProgramType', $this->DescriptionOrType, true, 'OR');
        
        /*$sort->attributes = array(
            'ProgramType'=>array(
                'asc'=>'ProgramTypeRelation.Name ASC',
                'desc'=>'ProgramTypeRelation.Name DESC',
            ),
            '*',
        );*/
        
        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'sort'=>$sort, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
}

?>
