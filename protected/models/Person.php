<?php

class Person extends BaseModel{
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
            return 'persons';
    }

    public function rules() {
        return array(
            array('FirstName, LastName, BarcodeID, Household', 'required'),
            array('Household, GradeLevel, Type, Subtype, UpdateUserId', 'numerical', 'integerOnly'=>true),
            array('BarcodeID', 'length', 'max'=>20),
            array('BarcodeID', 'unique', 'message'=>'{attribute} "{value}" already exists!'),
            array('BarcodeID', 'match', 'pattern' => '/^[0-9]+$/', 'message'=>'Barcode must contain numbers'),
            array('BarcodeID', 'length', 'max'=>6),
            array('Type', 'checkType', 'id' => $_POST['Type']),
            array('LastName, FirstName, EmailAddress, PhysicianName, PhysicianPhoneNumber', 'length', 'max'=>50),
            array('LastName, FirstName', 'match', 'pattern' => '/^[a-zA-Z\s]+$/', 'message'=>'Names can contain only latin characters and spaces'),
            array('EmailAddress', 'match', 'pattern' => '/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', 'message'=>'Email address number has incorrect format'),
            array('DateOfBirth', 'application.extensions.Validators.ClientDateTimeValidator'),
            array('Sex', 'length', 'max'=>2),
            array('GradeLevel', 'length', 'max'=>2),
            array('School', 'length', 'max'=>11),
            array('School', 'match', 'pattern' => '/^[0-9]{1,11}$/', 'message'=>'School number must contain only numbers'),
            array('HomePhone, WorkPhone, MobilePhone, PhysicianPhoneNumber', 'length', 'max'=>10),
          //  array('HomePhone, WorkPhone, MobilePhone, PhysicianPhoneNumber', 'match', 'pattern' => '/^\(\d{3}\)\d{3}-\d{4}$/', 'message'=>'Phone number has incorrect format'),
            array('Allergies, Medications', 'length', 'max'=>140),
            array('PicasaLink, GDocSurvey, GDocApplication', 'length','max'=>255),
            array('InsuranceCarrier, InsuranceNumber', 'length', 'max'=>70),
            array('DateOfBirth, Notes, SpecialCircumstances, LastUpdated', 'safe'),
            
            array('Emergency1MobilePhone, Emergency2MobilePhone', 'length', 'max'=>25),
            array('Emergency1FirstName, Emergency1LastName, Emergency1Relationship, 
                   Emergency2FirstName, Emergency2LastName, Emergency2Relationship', 'length', 'max'=>50),

            array('ID, BarcodeID, LastName, FirstName, Household, EmailAddress, Sex, GradeLevel, Type, Subtype, School, DateOfBirth, HomePhone, WorkPhone, MobilePhone, Notes, SpecialCircumstances, PhysicianName, PhysicianPhoneNumber, Allergies, Medications, InsuranceCarrier, InsuranceNumber, LastUpdated, UpdateUserId, PicasaLink, GDocSurvey, GDocApplication', 'safe', 'on'=>'search'),
        );
    }

    public function relations(){
        return array(
            'HouseholdRelation' => array(self::BELONGS_TO, 'Household', 'Household',
                     'select' => 'Emergency1FirstName, Emergency1LastName, Emergency1Relationship, Emergency1MobilePhone,
                                  Emergency2FirstName, Emergency2LastName, Emergency2Relationship, Emergency2MobilePhone'),
            'SchoolRelation'=>array(self::BELONGS_TO, 'School', 'School', 'select'=>'Name')            
        );
    }

    public function attributeLabels(){
        return array(
            'ID' => 'ID',
            'BarcodeID' => 'Barcode #',
            'LastName' => 'Last',
            'FirstName' => 'First',
            'Household' => 'Household name',
            'EmailAddress' => 'E-mail',
            'Sex' => 'Gender',
            'GradeLevel' => 'Grade',
            'Type'=>'Role',
            'Subtype'=>'Relationship',
            'School' => 'School',
            'DateOfBirth' => 'Birthday',
            'HomePhone' => 'Home Phone #',
            'WorkPhone' => 'Work Phone #',
            'MobilePhone' => 'Mobile Phone #',
            'Notes' => 'Notes',
            'SpecialCircumstances' => 'Special Circumstances',
            'PhysicianName' => 'Physician Name',
            'PhysicianPhoneNumber' => 'Physician Phone Number',
            'Allergies' => 'Allergies',
            'Medications' => 'Medications',
            'PicasaLink' => "<img class='header-image' src='images/logo_picasa.png' alt='Picasa' /> Picture",
            'GDocSurvey' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' /> Survey",
            'GDocApplication' => "<img class='header-image' src='images/logo_gdoc.png' alt='GoogleDoc Link' /> Application",
            'InsuranceCarrier' => 'Insurance Carrier',
            'InsuranceNumber' => 'Insurance Number',
            
            'Emergency1FirstName' => 'First',
            'Emergency1LastName' => 'Last',
            'Emergency1Relationship' => 'Relationship',
            'Emergency1MobilePhone' => 'Mobile',
            'Emergency2FirstName' => 'First',
            'Emergency2LastName' => 'Last',
            'Emergency2Relationship' => 'Relationship',
            'Emergency2MobilePhone' => 'Mobile',
        );
    }
        
    public function checkType($attribute, $params) {
        if ($params['id'] === '_all' || !$params['id']) {  //print_r($attribute); die();
            $this->addError($attribute, 'Your should select a '.$attribute.'!');
        }
    }
   
    public function beforeSave(){

       $this->AdjustDateTimeFields(false);
       $this->SetUpdateInfo();
       $this->Type = $_POST['Type']; 
       $this->Subtype = $_POST['Subtype'];

       if(parent::beforeSave())
        {   
            if (Yii::app()->user->checkAccess(UserRoles::LocalAdmin)) {
                $location = Household::model()->findByPk($_POST['Person']['Household']);                
            } else {
                $location = School::model()->findByPk($_POST['Person']['School']);     
            }
            
            $_POST['location'] = $location->Location;
            
            $this->Links = $_POST["Links"];
            PersonLinks::deleteLinks($this->ID);
            PersonLinks::insertLInks($this->ID, $_POST["Links"]);
            
            //error_log($_POST["Links"]);
            
            if (!Yii::app()->user->checkAccess('createLocal', array('post'=>$_POST)) && !Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                $this->addError('', 'You aren\'t authorized to create/update Person in this area!');
                return false;
            } else {   
                return true;
            }
        }
        else {
            return false;
        }
    }
    
    public function saveLinks($links = array())
    {

    }
    
    public function GetByBarcodeID($barcode_id) {
        return $this->find( array('condition' => 'BarcodeID = :barcode_id', 'params'=>array(':barcode_id'=>$barcode_id)));
    }
    
    public function afterFind(){
        $this->AdjustDateTimeFields(true);
        $this->AdjustEmergencyContact($this->HouseholdRelation, $this->SchoolRelation);
        $this->Links = PersonLinks::getLinks($this->ID);
    }
    
    public function AdjustEmergencyContact(&$household, &$school){
        $this->Emergency1FirstName = $household->Emergency1FirstName;
        $this->Emergency1LastName = $household->Emergency1LastName;
        $this->Emergency1Relationship = $household->Emergency1Relationship;
        $this->Emergency1MobilePhone = $household->Emergency1MobilePhone;
        $this->Emergency2FirstName = $household->Emergency2FirstName;
        $this->Emergency2LastName = $household->Emergency2LastName;
        $this->Emergency2Relationship = $household->Emergency2Relationship;
        $this->Emergency2MobilePhone = $household->Emergency2MobilePhone;
        $this->SchoolName = $school->Name;
    }
    
    public function AdjustDateTimeFields($clientServer){
        if ($clientServer){
            $this->DateOfBirth = $this->getClientDate($this->DateOfBirth);
        }
        else{
            $this->DateOfBirth = $this->getServerDate($this->DateOfBirth);
        }
    }
    
    public $Emergency1FirstName;
    public $Emergency1LastName;
    public $Emergency1Relationship;
    public $Emergency1MobilePhone;
    
    public $Emergency2FirstName;
    public $Emergency2LastName;
    public $Emergency2Relationship;
    public $Emergency2MobilePhone;
    
    public $SchoolName;
    
    public $Links = array();
}