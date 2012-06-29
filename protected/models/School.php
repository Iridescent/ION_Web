<?php

class School extends BaseModel
{
    public $countries;
    public $states;
    public $cities;
    public $filter = "";
    
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    
    public function tableName() {
        return 'schools';
    }

    public function rules() {
        return array(
            array('Zip, LAUSCSchoolCode', 'numerical'),
            array('Name', 'required'),
            array('Location', 'length', 'max'=>11),
            array('Zip', 'length', 'max'=>5),
            array('Website', 'match', 'pattern' => '/^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/', 'message'=>'Website address has incorrect format'),
            array('Type, Name, LAUSDAddress, Phone, Fax, LAUSDBoardDistrict, LAUSDLocalDistrict, LAUSDSchoolType, LAUSDSchoolCalendar, GradeLevel, LACCharterSchoolNum, Address', 'length', 'max'=>255),
            //array('Phone', 'length', 'max'=>10),
            //array('Fax', 'length', 'max'=>10),
            array('Phone', 'match', 'pattern' => '/^\(\d{3}\)\d{3}-\d{4}$/', 'message'=>'Phone number has incorrect format (ex. (111)222-3333)'),
            array('Fax', 'match', 'pattern' => '/^\(\d{3}\)\d{3}-\d{4}$/', 'message'=>'Fax number has incorrect format (ex. (111)222-3333)'),
            array('Website', 'safe'),
            array('countries', 'checkLocation', 'id' => $_POST['countries']),
            array('states', 'checkLocation', 'id' => $_POST['states']),
            array('cities', 'checkLocation', 'id' => $_POST['cities']),
            array('ID, Type, Name, Location, LAUSDAddress, Zip, Phone, Fax, LAUSDBoardDistrict, LAUSDLocalDistrict, LAUSDSchoolType, LAUSDSchoolCalendar, LAUSCSchoolCode, GradeLevel, LACCharterSchoolNum, Website, Address', 'safe', 'on'=>'search'),
            );
	}

    public function relations() {
        return array( );
    }

    public function attributeLabels()
    {
        return array(
            'ID' => 'ID',
            'Type' => 'Type',
            'Name' => 'Name',
            'LAUSDAddress' => 'Lausd Address',
            'Location' => 'Location',
            'country'=>'Country',
            'states'=>'State',
            'cities'=>'City',
            'Zip' => 'Zip',
            'Phone' => 'Phone',
            'Fax' => 'Fax',
            'LAUSDBoardDistrict' => 'Lausdboard District',
            'LAUSDLocalDistrict' => 'Lausdlocal District',
            'LAUSDSchoolType' => 'Lausdschool Type',
            'LAUSDSchoolCalendar' => 'Calendar',
            'LAUSCSchoolCode' => 'Lauscschool Code',
            'GradeLevel' => 'Grade Level',
            'LACCharterSchoolNum' => 'Laccharter School Num',
            'Website' => 'Website',
            'Address' => 'Address',
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
            $criteria->select = 'ID, Name, Type, Address';
            $criteria->compare('Name', $this->filter, true, 'OR');
            $criteria->compare('Type', $this->filter, true, 'OR');
            $criteria->compare('Address', $this->filter, true, 'OR');
            
            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'pagination'=>array(
                        'pageSize'=>20,
                    ),
            ));
    }
        
    public function getIdByName($schoolName){
        return $this->findAll(
            array(
                'condition' => 'Name = :Name',
                'params'=>array(':Name'=>$schoolName),
                'select' => 'ID',
            ));
    }
    
    public function Autocomplete($name, $limit=10){
        $criteria = new CDbCriteria;
        $criteria->limit = $limit;
        $criteria->condition = 'Name LIKE :Name';
        $criteria->params = array(':Name'=>'%'.$name.'%');
        $criteria->select = 'ID, Name';
        
        if (!UserIdentity::IsSuperAdmin()){
            $criteria->condition = $criteria->condition . ' AND Location IN (:locations)';
            $criteria->params[':locations'] = implode (",", UserIdentity::Location());
        }
        
        return $this->findAll($criteria);
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
}