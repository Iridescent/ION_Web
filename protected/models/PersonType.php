<?php

class PersonType extends CActiveRecord {
    public $Subtype;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'persontype';
    }

    public function rules()
    {
        return array(
            array('Name', 'length', 'max'=>25),
            array('ID, Name', 'safe', 'on'=>'search'),
            array('Name', 'unique', 'message'=>'{attribute} "{value}" already exists!'),
            array('Name', 'required', 'message'=>'Type name is required!'),
            array('Name', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message'=>'Type name contains illegal characters'),
        );
    }

    public function relations() {
        return array(
            'persons' => array(self::HAS_MANY, 'Person', 'ID'),
            'subtype' => array(self::HAS_MANY, 'Personsubtype', 'ID'),
        );
    }

    public function attributeLabels() {
        return array(
            'ID' => 'ID',
            'Name' => 'Name',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;
        $criteria->compare('ID',$this->ID,true);
        $criteria->compare('Name',$this->Name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}