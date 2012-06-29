<?php

/**
 * This is the model class for table "sites".
 *
 * The followings are the available columns in table 'sites':
 * @property string $ID
 * @property string $Name
 * @property string $Address
 * @property string $City
 * @property string $State
 * @property string $ZIPPostal
 * @property string $Contact
 */
class Sites extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Sites the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, Address', 'length', 'max'=>255),
                        array('Name, Address', 'required'),
			array('Location', 'length', 'max'=>5),
			array('ZIPPostal', 'length', 'max'=>5),
                        array('ZIPPostal', 'numerical'),
			array('Contact', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, Name, Address, ZIPPostal, Contact', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'Name' => 'Name',
			'Address' => 'Address',
			'Location' => 'Location',
			'ZIPPostal' => 'Zippostal',
			'Contact' => 'Contact',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('Location',$this->Location,true);
		$criteria->compare('ZIPPostal',$this->ZIPPostal,true);
		$criteria->compare('Contact',$this->Contact,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}