<?php

/**
 * This is the model class for table "attendance".
 *
 * The followings are the available columns in table 'attendance':
 * @property string $ID
 * @property integer $Person
 * @property integer $Session
 * @property string $DateRecorded
 * @property string $Keytag
 * @property integer $UpdateUserId
 */
class Attendance extends CActiveRecord{
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return 'attendance';
    }

    public function rules(){
        return array(
            array('Person, Session, UpdateUserId', 'numerical', 'integerOnly'=>true),
            array('Keytag', 'length', 'max'=>1),
            array('DateRecorded', 'safe'),
            array('ID, Person, Session, DateRecorded, Keytag, UpdateUserId', 'safe', 'on'=>'search'),
        );
    }

    public function attributeLabels(){
        return array(
            'ID' => 'ID',
            'Person' => 'Person',
            'Session' => 'Session',
            'EnterDate' => 'Enter Date',
            'ExitDate' => 'Exit Date',
            'Keytag' => 'Keytag',
            'UpdateUserId' => 'Update User',
        );
    }
    
    public function search($pageSize=20){
        $criteria=new CDbCriteria;

        $criteria->compare('ID',$this->ID,true);
        $criteria->compare('Person',$this->Person);
        $criteria->compare('Session',$this->Session);
        $criteria->compare('EnterDate',$this->EnterDate,true);
        $criteria->compare('ExitDate',$this->ExitDate,true);
        $criteria->compare('Keytag',$this->Keytag,true);
        $criteria->compare('UpdateUserId',$this->UpdateUserId);

        return new CActiveDataProvider($this, array('criteria'=>$criteria, 'pagination'=>array('pageSize'=>$pageSize,)));
    }
}