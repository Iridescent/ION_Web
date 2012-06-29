<?php

/**
 * This is the model class for table "Household".
 *
 * The followings are the available columns in table 'Household':
 * @property string $ID
 * @property string $Name
 * @property string $Address
 * @property string $City
 * @property string $StateProvince
 * @property string $ZIPPostal
 * @property string $FullAddress
 * @property string $Phone
 * @property string $LastUpdated
 *
 * The followings are the available model relations:
 * @property Persons[] $persons
 */
class Household extends BaseModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Household the static model class
	 */
        public $countries;
        public $states;
        public $cities;    

         
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'household';
	}
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
            return array(
                array('LastUpdated', 'unsafe'),
                array('Name, Address ', 'length', 'max'=>255),
                array('ZIPPostal', 'length', 'max'=>5),
                array('FullAddress', 'length', 'max'=>243),
                array('Phone', 'length', 'max'=>10),
                array('Location', 'length', 'max'=>11),                
                array('Emergency1MobilePhone, Emergency2MobilePhone', 'length', 'max'=>25),
                array('Emergency1FirstName, Emergency1LastName, Emergency1Relationship, 
                       Emergency2FirstName, Emergency2LastName, Emergency2Relationship', 'length', 'max'=>50),
                array('PicasaLink', 'length', 'max'=>255),        
                array('Name', 'required'),
                array('countries', 'checkLocation', 'id' => $_POST['countries']),
                array('states', 'checkLocation', 'id' => $_POST['states']),
                array('cities', 'checkLocation', 'id' => $_POST['cities']),

                // Please remove those attributes that should not be searched.
                array('ID, Name, Address, ZIPPostal, FullAddress, Phone, LastUpdated', 'safe', 'on'=>'search'),
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
			'persons' => array(self::HAS_MANY, 'Person', 'ID'),
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
                    'country'=>'Country',
                    'states'=>'State',
                    'cities'=>'City',
                    'ZIPPostal' => 'ZIP',
                    //'FullAddress' => 'Full Address',
                    'Location' => 'Location',
                    'Phone' => 'Phone',
                    'Emergency1FirstName' => 'First Name',
                    'Emergency1LastName' => 'Last Name',
                    'Emergency1Relationship' => 'Relationship',
                    'Emergency1MobilePhone' => 'Mobile',
                    'Emergency2FirstName' => 'First Name',
                    'Emergency2LastName' => 'Last Name',
                    'Emergency2Relationship' => 'Relationship',
                    'Emergency2MobilePhone' => 'Mobile',
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
                
                if (!Yii::app()->user->checkAccess('Super Admin')) {
                    $criteria->condition = 'Location='.(int)Yii::app()->user->location;            
                }  

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=>array(
                            'pageSize'=>20,
                        ),
		));
	}
        
       public function checkLocation($attribute, $params) {
          // check Country
         
          switch ($attribute) {
              case 'countries':
                  $attrName = 'Country';
                  break;
              case 'states':
                  $attrName = 'State';
                  break;
              case 'cities':
                  $attrName = 'City';
                  break;

          }

          if ($params['id'] === '_all' || !$params['id']) {  //print_r($attribute); die();
                 $this->addError($attribute, 'Your should select a '.$attrName.'!');
            }

        }
        
        public function getLocation() 
        {
            $location = Locations::model()->getLocation((int)$this->Location);
            $this->countries = $location->cn;
            $this->states = $location->sn;
            $this->cities = $location->cin;
        }
        
        
        public function beforeSave() {  
            if(parent::beforeSave()) {
                $this->SetUpdateInfo();
                $this->Location = Locations::model()->defineLocation(false);
                
                $_POST['location'] = $this->Location;
                if (!Yii::app()->user->checkAccess('createLocal', array('post'=>$_POST)) && !Yii::app()->user->checkAccess('Super Admin')) {
                    $this->addError('', 'You aren\'t authorized to create/update Household in this area!');
                    return false;
                } else {
                    return true;
                }
            }
            else {
                return false;
            }

        }
}
