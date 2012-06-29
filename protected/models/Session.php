<?php

/**
 * This is the model class for table "sessions".
 *
 * The followings are the available columns in table 'sessions':
 * @property string $ID
 * @property integer $Program
 * @property string $Start
 * @property string $End
 * @property string $Description
 * @property string $Instructors
 * @property integer $NonSchoolSite
 * @property integer $SchoolSite
 * @property integer $CourseManager
 * @property string $Notes
 */
class Session extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Session the static model class
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
		return 'sessions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Program, NonSchoolSite, SchoolSite, CourseManager, UserManager', 'numerical', 'integerOnly'=>true),
			array('Description', 'length', 'max'=>255),
			array('Start, End, Instructors, Notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, Program, Start, End, Description, Instructors, NonSchoolSite, SchoolSite, CourseManager, UserManager, Notes', 'safe', 'on'=>'search'),
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
			'Program' => 'Program',
			'Start' => 'Start',
			'End' => 'End',
			'Description' => 'Description',
			'Instructors' => 'Instructors',
			'NonSchoolSite' => 'Non School Site',
			'SchoolSite' => 'School Site',
			'CourseManager' => 'Course Manager',
                        'UserManager' => 'User Course Manager',
			'Notes' => 'Notes',
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
		$criteria->compare('Program',$this->Program);
		$criteria->compare('Start',$this->Start,true);
		$criteria->compare('End',$this->End,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Instructors',$this->Instructors,true);
		$criteria->compare('NonSchoolSite',$this->NonSchoolSite);
		$criteria->compare('SchoolSite',$this->SchoolSite);
		$criteria->compare('CourseManager',$this->CourseManager);
                $criteria->compare('UserManager',$this->UserManager);
		$criteria->compare('Notes',$this->Notes,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}