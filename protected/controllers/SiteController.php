<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
        
        /**
         * Initial role permissions installation
         */
        public function actionInstall() {

        $auth=Yii::app()->authManager;

        //сбрасываем все существующие правила
        $auth->clearAll();

       // $bizRule='return count(array_diff($_POST["location"], Yii::app()->user->location)) == 0;'; //  return in_array(Yii::app()->user->location, $params["post"]["location"]);
        $bizRuleUser = 'return count(array_diff($_POST["location"], Yii::app()->user->location)) == 0;';
        $bizRule = 'return in_array($params["post"]["location"], Yii::app()->user->location);';
        
        // OPERATIONS
                $auth->createOperation('createRecord','create a record');
                $auth->createOperation('readRecord','read a record');
                $auth->createOperation('updateRecord','update a record');
                $auth->createOperation('deleteRecord','delete a record');
                
                $auth->createOperation('manageUsers','create/update/delete users');//, $bizRule);

                $task=$auth->createTask('createLocal','create entity only in local area',$bizRule);
                $task=$auth->createTask('updateLocal','update entity only in local area',$bizRule);
                $task=$auth->createTask('readLocal','read entity only in local area',$bizRule);
                $task=$auth->createTask('deleteLocal','delete entity only in local area',$bizRule);
                
                // for Users
                $task=$auth->createTask('createLocalUser','create User entity only in local area',$bizRuleUser);
                $task=$auth->createTask('updateLocalUser','update User entity only in local area',$bizRuleUser);
                $task=$auth->createTask('readLocalUser','read User entity only in local area',$bizRuleUser);
                $task=$auth->createTask('deleteLocalUser','delete User entity only in local area',$bizRuleUser);
                
         // ROLES - from DB
                $criteria = new CDbCriteria();
                $criteria->select = array('ID', 'Name');
                $roleList = CHtml::listData(Roles::model()->findAll(), 'ID', 'Name'); //Program::model()->findAll(array('order' => 'Description')), 'ID', 'Description');// 
              //  print_r($roleList); die();
                foreach ($roleList as $key => $value) {
                 //   print_r($value); //die();
                    $role=$auth->createRole($value);
                    $role->addChild('createRecord');
                    $role->addChild('createLocal');
                    $role->addChild('createLocalUser');
                    
                    $role->addChild('updateRecord');
                    $role->addChild('updateLocal');
                    $role->addChild('updateLocalUser');
                    
                    $role->addChild('readRecord');
                    $role->addChild('readLocal');
                    $role->addChild('readLocalUser');

                    $role->addChild('deleteRecord');
                    $role->addChild('deleteLocal');
                    $role->addChild('deleteLocalUser');
                    
                    if ($key === 1 || $key ===2) {
                     
                        $role->addChild('manageUsers');
                    
                    }
                }
                $userList = CHtml::listData(User::model()->findAll(), 'ID', 'Role');  

                foreach ($userList as $key => $value) {
                    $userRole = Roles::model()->findByAttributes(array('ID'=>$value));
                    if(!empty($userRole)){
                        Yii::app()->authManager->assign($userRole->Name, $key);
                    }
                }    
           $auth->save();
        }        
        
        public function actionStatesByCountry($list = false) {

              $locationsList = implode(',', Yii::app()->user->location);

              $countryID = (int)$_POST['countryid'];              
              $accessCondition = '';

              if (!Yii::app()->user->checkAccess('Super Admin')) {
                  $accessSql = "SELECT DISTINCT State FROM locations ".
                                     "WHERE Country = :countryid AND State IS NOT NULL AND ID IN (".$locationsList.")";
                  $command = Yii::app()->db->createCommand($accessSql);
                  $command->bindValue(':countryid', $countryID);
                  $accessData = $command->queryAll(true); 

                  $accessArray = array();
                  foreach ($accessData as $key => $value) {
                      $accessArray[] = $value['State']; 
                  } 
                  $accessList = implode (",", $accessArray);                  
                  $accessCondition = " AND ID IN (".$accessList.")";                                 
              }
              
              $sql = "SELECT ID, Name, Country FROM state ".
                     "WHERE Country = :countryid".$accessCondition;
              
              $command = Yii::app()->db->createCommand($sql);
              $command->bindValue(':countryid', $countryID);
              $data = $command->queryAll(true); 
              
              $data = CHtml::listData($data,'ID','Name');

              if ($list) {
                  $data = ReportsController::unionArrays(array("_all" => "Select a state:") , $data);
                  foreach($data as $value=>$name) {
                      echo CHtml::tag('option',
                        array('value'=>$value),CHtml::encode($name),true);
                  }
              } else {    
                  //echo CHtml::checkboxList('states','',$data, array('tag' => $countryID));
                  echo VisualHelper::CheckBoxList($data, $countryID, 'states', true);
              }  

        }
        
        public function actionCitiesByState($list = false){
          
          $locationsList = implode(',', Yii::app()->user->location);
  
          $stateID = (int)$_POST['stateid'];
          $accessCondition = '';

              if (!Yii::app()->user->checkAccess('Super Admin')) {
                  $accessSql = "SELECT DISTINCT City FROM locations ".
                                     "WHERE State = :stateid AND City IS NOT NULL AND ID IN (".$locationsList.")";
                  $command = Yii::app()->db->createCommand($accessSql);
                  $command->bindValue(':stateid', $stateID);
                  $accessData = $command->queryAll(true); 

                  $accessArray = array();
                  foreach ($accessData as $key => $value) {
                      $accessArray[] = $value['City']; 
                  } 
                  $accessList = implode (",", $accessArray);                  
                  $accessCondition = " AND ID IN (".$accessList.")";                                 
              }

          $sql = "SELECT ID, Name, State, Country FROM city ".
                 "WHERE State = :stateid".$accessCondition;
          $command = Yii::app()->db->createCommand($sql);
          $command->bindValue(':stateid', $stateID);

          $data = $command->queryAll(true); //print_r ($data);
          $data = CHtml::listData($data,'ID','Name'); 
          
          if ($list) {
          
              $data = ReportsController::unionArrays(array("_all" => "Select a city:") , $data);
              foreach($data as $value=>$name) {
                echo CHtml::tag('option',
                    array('value'=>$value),CHtml::encode($name),true);
              }
          } else {
             //echo CHtml::checkboxList('cities','',$data, array('tag' => $stateID));
             echo VisualHelper::CheckBoxList($data, $stateID, 'cities', true);
          }    
        }            
        
        public function actionAddState(){ 
            $data = CJSON::decode($_POST['geo'], true); 
            return $_POST['geo'];
/*        
          $countryID = (int)$_POST['country']['id'];
          $itemID = (int)$_POST['item']['id'];
          $defaultState = $_POST['defaultState'];
          $statesList = $_POST['statesList'];
          $model = $_POST['model'];
          $form = $_POST['form'];
          
          $sql = "SELECT ID, Name, State FROM city ".
                 "WHERE State = :stateid";
          $command = Yii::app()->db->createCommand($sql);
          $command->bindValue(':stateid', $countryID);

          $data = $command->queryAll(true);;
          $data = CHtml::listData($data,'ID','Name'); 
          $data = ReportsController::unionArrays(array("_all" => "Select a city:") , $data);
          //return (int)$_POST['id'];
         // $this->renderPartial('//user/_form', array('data'=>$_POST['id']));
          
          /*
          foreach($data as $value=>$name)
          {
            echo CHtml::tag('option',
                array('value'=>$value),CHtml::encode($name),true);

          }
            
           */
        }            

        
/**
 * This is the action to handle TEST multiple video and image upload.
 */                
        
        /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionUpload()
	{ 
            
            if (isset($_FILES['uploadvideo'])) {
                // Call BlackBox function - FileHandler
                $new = new FileHandler(NULL);
                $new->handleVideo($_FILES['uploadvideo']);
            }
            
            if (isset($_FILES['uploadimage'])) {
                $new = new FileHandler(NULL);
                $new->handleImage($_FILES['uploadimage']);

            }
            $this->render('//survey/upload', array('model' => $model));  
              
            
	}
}