<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryUser
 *
 * @author dshyshen
 */
class QueryUser extends CActiveRecord {
    
    public $filter = "";
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    public function tableName()	{
        return 'users';
    }
    
    public function relations(){
        return array(
            'RoleRelation'=>array(self::BELONGS_TO, 'Roles', 'Role', 'select'=>'Name'),
            );
    }
    
    public function scopes(){
            return array('activeUsers' =>array('condition' => "IsDeleted=0",),);
        }
    
    public function search($pageSize=20){
  
        $criteria=new CDbCriteria;
        
        if (!Yii::app()->user->checkAccess('Super Admin')) {
           // $criteria->condition = 'Location = :locationId AND Role >= :role';
          //  $criteria->params = array(':locationId'=>Yii::app()->user->location, ':role'=>Yii::app()->user->role);
        }
        
        $criteria->with = array('RoleRelation');
        $criteria->scopes = 'activeUsers';
        $criteria->compare('Login', $this->filter, true, 'OR');
        $criteria->compare('FirstName', $this->filter, true, 'OR');
        $criteria->compare('LastName', $this->filter, true, 'OR');
        $criteria->compare('RoleRelation.Name', $this->filter, true, 'OR');
        
        $sort = new CSort();
        $sort->attributes = array(
            'Role'=>array(
                'asc'=>'RoleRelation.Name ASC',
                'desc'=>'RoleRelation.Name DESC',
            ),
            '*',
        );
        
        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'sort'=>$sort, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Login' => 'Login',
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
            'IsDeleted' => 'Deleted',
        );
    }
}

?>
