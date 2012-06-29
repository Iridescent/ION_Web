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
class QueryHousehold extends BaseModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Household the static model class
	 */
        public $countries;
        public $states;
        public $cities;    
        public $filter = "";
         
        public static function model($className=__CLASS__) {
            return parent::model($className);
	}

	public function tableName() {
            return 'household';
	}
        
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

                array('ID, Name, Address, ZIPPostal, FullAddress, Phone, LastUpdated', 'safe', 'on'=>'search'),
            );
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'persons' => array(self::HAS_MANY, 'Person', 'ID', 'select'=>'ID'),
                        'PersonRelation'=>array(self::HAS_MANY, 'QueryPerson', 'Household', 'select'=>'*'),
		);
	}

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
                    'PicasaLink' => "<img class='header-image' src='images/logo_picasa.png' alt='Picasa' /> Picture"
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
                    //$criteria->condition = 'Location='.(int)Yii::app()->user->location;            
                }  

                $criteria->compare('Name', $this->filter, true, 'OR');
                $criteria->compare('Address', $this->filter, true, 'OR');
                $criteria->compare('ZIPPostal', $this->filter, true, 'OR');
                
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

          if ($params['id'] === '_all' || !$params['id']) {  
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
            if(parent::beforeSave()){
                $this->SetUpdateInfo();
                $this->Location = Locations::model()->defineLocation(false);  
                
                $_POST['location'] = $this->Location; 

                if (!Yii::app()->user->checkAccess('createLocal', array('post'=>$_POST)) && !Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                    $this->addError('', 'You aren\'t authorized to create/update Household in this area!');
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }

        public function Autocomplete($name, $limit=1){
           return $this->findAll(array('limit' => $limit,
                'condition' => 'Name LIKE :Name',
                'params'=>array(':Name'=>'%'.$name.'%'),
                'select' => 'ID, Name, Address, ZIPPostal',
                ));
        }
        
        public function getIdByName($househodlName){
            return $this->findAll(
                array(
                    'condition' => 'Name = :Name',
                    'params'=>array(':Name'=>$househodlName),
                    'select' => 'ID',
                ));
        }
}

?>
