<?php

/**
 * This is the model class for table "locations".
 *
 * The followings are the available columns in table 'locations':
 * @property integer $ID
 * @property integer $City
 * @property integer $State
 * @property integer $Country
 */
class Locations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Locations the static model class
	 */
        
        public $cn;
        public $sn;
        public $cin;    

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**s
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('State, Country', 'required'),
			array('City, State, Country', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, City, State, Country', 'safe', 'on'=>'search'),
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

       
       public function selectCountry($notUser) {
              $sql = "SELECT * FROM country ";
              $command = Yii::app()->db->createCommand($sql);
              $data = $command->queryAll(true);
              
              $data = CHtml::listData($data,'ID','Name');
              if ($notUser) {
                  $data = ReportsController::unionArrays(array("_all" => "Select a country:") , $data);
              }    
              return $data;
       }

       
       public function selectStates($default) {
             $locationsList = implode(',', Yii::app()->user->location);
             $accessCondition = '';

              if (!Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                  $accessSql = "SELECT DISTINCT State FROM locations ".
                                     "WHERE Country = :countryid AND State IS NOT NULL AND ID IN (".$locationsList.")";
                  $command = Yii::app()->db->createCommand($accessSql);
                  $command->bindValue(':countryid', $default);
                  $accessData = $command->queryAll(true); 
                  
                  if (!empty($accessData)) {
                      $accessArray = array();
                      foreach ($accessData as $key => $value) {
                          $accessArray[] = $value['State']; 
                      } 
                      $accessList = implode (",", $accessArray);                  
                      $accessCondition = " AND ID IN (".$accessList.")";
                  }    
              } 
              $sql = "SELECT * FROM state WHERE Country = :countryid".$accessCondition;
              
              $command = Yii::app()->db->createCommand($sql);
              $command->bindValue(':countryid', $default, PDO::PARAM_INT);
              
              $data = $command->queryAll(true);              
              $data = CHtml::listData($data,'ID','Name'); 
              
              return $data;
       }

       public function selectCities($default) {
             $locationsList = implode(',', Yii::app()->user->location);
             $accessCondition = '';

             if (!Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                  $accessSql = "SELECT DISTINCT City FROM locations ".
                                     "WHERE State = :stateid AND City IS NOT NULL AND ID IN (".$locationsList.")";
                  $command = Yii::app()->db->createCommand($accessSql);
                  $command->bindValue(':stateid', $default);
                  $accessData = $command->queryAll(true); 
                  
                  if (!empty($accessData)) {
                      $accessArray = array();
                      foreach ($accessData as $key => $value) {
                          $accessArray[] = $value['City']; 
                      } 
                      $accessList = implode (",", $accessArray);                  
                      $accessCondition = " AND ID IN (".$accessList.")";
                  }    
              }
                      
              $sql = "SELECT * FROM city WHERE State = :stateid".$accessCondition;
              $command = Yii::app()->db->createCommand($sql);
              $command->bindValue(':stateid', $default, PDO::PARAM_INT);
              
              $data = $command->queryAll(true);
              $data = CHtml::listData($data,'ID','Name');      
             // if ($_POST['states'] && $_POST['states'] != '_all') {
             //     $data = ReportsController::unionArrays(array("_all" => "Select a city:") , $data);
             // }    
              return $data; 
       }
       

       public function selectHouseholdsByLocation() {
              if (!Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                  $locationsList = implode(',', Yii::app()->user->location);
                  $sql = "SELECT ID, Name FROM household WHERE Location IN (".$locationsList.") ORDER BY Name";
                  $command = Yii::app()->db->createCommand($sql);

                  $data = $command->queryAll(true);
                  $data = CHtml::listData($data,'ID','Name');      
              } else {
                  $data = CHtml::listData(Household::model()->findAll(array('order' => 'Name')), 'ID', 'Name');                  
              }
              
              return $data; 
       }       

       public function selectSchoolsByLocation() {
              if (!Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                  $locationsList = implode(',', Yii::app()->user->location);
                  $sql = "SELECT ID, Name FROM schools WHERE Location IN (".$locationsList.") ORDER BY Name";
                  $command = Yii::app()->db->createCommand($sql);

                  $data = $command->queryAll(true);
                  $data = CHtml::listData($data,'ID','Name');      
              } else {
                  $data = CHtml::listData(School::model()->findAll(array('order' => 'Name')), 'ID', 'Name');                  
              }
              
              return $data; 
       }              
       
       public function defineLocation($isUser, $locationHierarchy = NULL) {
           if ($isUser) {               
               if (count($locationHierarchy) < 1){
                   return Yii::app()->user->locationToSave;
               }
               else{

                   $params = array();
                   $sql = 'SELECT ID FROM locations WHERE ';
                   
                   foreach($locationHierarchy as $country => $states){
                       if(count($states) < 1) {
                           $sql .= '(Country = ? AND State IS NULL AND City IS NULL) OR ';
                           $params[] = $country;
                       }
                       else {
                           foreach($states as $state => $cities){
                               if(count($cities) < 1){
                                   $sql .= '(Country = ? AND State = ? AND City IS NULL) OR ';
                                   $params[] = $country;
                                   $params[] = $state;
                               }
                               else{
                                   foreach($cities as $city => $fake){
                                       $sql .= '(Country = ? AND State = ? AND City = ?) OR ';
                                       $params[] = $country;
                                       $params[] = $state;
                                       $params[] = $city;
                                   }
                               }
                           }
                       }
                   }
                   
                   $sql = substr($sql, 0, strlen($sql) - 4);
                   $command = Yii::app()->db->createCommand($sql);
                   return $command->queryColumn($params);
               }
          } 
          else {
              if ($_POST['cities'] != '_all') {
                  $cityCondition = "AND City = :cityid ";
                  $city = $_POST['cities'];
              } else {
                  $cityCondition = "AND City is :cityid";
                  $city = NULL;
              }

              $sql = "SELECT ID FROM locations ". 
                     "WHERE Country = :countryid AND State = :stateid ".
                     $cityCondition;
              
              $command = Yii::app()->db->createCommand($sql);

              $command->bindValue(':countryid', $_POST['countries']);
              $command->bindValue(':stateid', $_POST['states']);
              $command->bindValue(':cityid', $city);
              
              $data = $command->queryScalar(); // Location ID
              return $data;              
          }  
       }
       
       public function getLocation($params) {

            $criteria = new CDbCriteria();
            $criteria->alias = 'locations';
            $criteria->select = array(Locations::model()->tableName().'.ID as ID', 'country.ID as cn', 'state.ID as sn', 'city.ID as cin');
            
            $criteria->join = ' INNER JOIN country ON country.ID=Country';
            $criteria->join .= ' INNER JOIN state ON state.ID=State';
            $criteria->join .= ' LEFT JOIN city ON city.ID=City';
           
            if ((int)$params != 0 || $params != NULL) {
                $criteria-> condition = $this->tableName().'.ID='.$params;
                $all = $this->find($criteria);
            }  
            else {
                $all = new Locations;
            }  
            return $all;
       }

       public function getAllUserLocations($userId) {
           $result = array();
           
           $sql = "SELECT l.* FROM userlocations ul INNER JOIN locations l ON l.ID = ul.LocationID WHERE ul.UserId = :userid";
           $command = Yii::app()->db->createCommand($sql);
           $command->bindValue(':userid', $userId);
           $rows = $command->queryAll();
          
           foreach($rows as $row){
               if ($row['City']){
                   $result[] = $row['ID'];
               }
               else if($row['State']){
                   $sql = "SELECT ID FROM locations WHERE State = :stateid";
                   $command = Yii::app()->db->createCommand($sql);
                   $command->bindValue(':stateid', $row['State']);
                   $ids = $command->queryColumn();
                   $result = array_merge($result, $ids);
               }
               else if($row['Country']) {
                   $sql = "SELECT ID FROM locations WHERE Country = :countryid";
                   $command = Yii::app()->db->createCommand($sql);
                   $command->bindValue(':countryid', $row['Country']);
                   $ids = $command->queryColumn();
                   $result = array_merge($result, $ids);
               }
           }
           
           return $result;
       }
       
       public function getUserLocation($userId){
           $sql = "SELECT LocationId from userlocations WHERE UserId = ".$userId;          
           $command = Yii::app()->db->createCommand($sql); 
           $data = $command->queryColumn();
           
           // for old users with only one location - only a temporary JAM
           if (!$data) {
               $sql = "SELECT Location from users WHERE Id = ".$userId;          
               $command = Yii::app()->db->createCommand($sql); 
               $data = $command->queryColumn();               
           }
           
           return $data;
       }
       
       public function setLocation($userId, $isNewUser) { 
           $locations = implode (',', $_POST['location']); 
           $sql = '';
           if (!$isNewUser) {
               $sql = "DELETE FROM `userlocations` WHERE UserID=".$userId.";";
               $command = Yii::app()->db->createCommand($sql); 
               $command->execute();

           }
           foreach ($_POST['location'] as $key => $value) {
               $sql = " INSERT INTO userlocations (UserID, LocationID) VALUES (".$userId.",".$value.");"; 
               $command = Yii::app()->db->createCommand($sql); 
               $command->execute();               
           }

           return;
       }       
       
       public function getLocationId($country, $state, $city){          
           $criteria = new CDbCriteria();
           $criteria->alias = 'l';
           $criteria->select = 'l.ID';
           $criteria->join = 'INNER JOIN country cnt ON cnt.ID = l.Country';
           $criteria->join .= ' INNER JOIN state s ON s.ID = l.State';
           $criteria->join .= ' LEFT JOIN city c ON c.ID = l.City';

           $criteria->condition = "cnt.Name = :country AND (s.Name = :state OR s.AbbreviationName = :state)";
           $criteria->params = array(':country'=>$country, ':state'=>$state);
           if ($city){
               $criteria->condition .= " AND c.Name = :city";
               $criteria->params[':city'] = $city;
           }
           else{
               $criteria->condition .= " AND l.City IS NULL";
           }
           
           return $this->findAll($criteria); 
       }
       
        public function getLocationHierarchy($userId){
            $result = (object) array();
            
            $sql = "SELECT l.Country, l.State, l.City
                    FROM locations l
                    INNER JOIN userlocations ul ON ul.LocationID = l.ID
                    INNER JOIN users u ON u.ID = ul.UserID
                    WHERE u.ID = :userid";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':userid', $userId);
            $data = $command->queryAll();           
            
            if ($data){
                foreach($data as $item){
                    $country = $item["Country"];
                    $state = $item["State"];
                    $city = $item["City"];
                    if (!$result->{$country}){
                        $result->{$country} = (object) array();
                    }
                    if ($state){
                        if(!$result->{$country}->{$state}){
                            $result->{$country}->{$state} = (object) array();
                        }
                        if($city){
                            if(!$result->{$country}->{$state}->{$city}){
                                $result->{$country}->{$state}->{$city} = (object) array();
                            }
                        }
                    }
                }
            }
            
            return $result;
        } 
}