<?php

class User extends CActiveRecord {
        const WEAK = 0;
        const STRONG = 1;
        public $prevRole = NULL;
        public $prevPassword = NULL;
        public $locationHierarchy = NULL;
        private $changeRole = FALSE;

	public static function model($className=__CLASS__){
            return parent::model($className)->activeUsers();
	}
        
        public function scopes(){
            return array('activeUsers' =>array('condition' => "IsDeleted=0",),);
        }
        
	public function tableName() {
            return 'users';
	}

	public function rules(){
            return array(
                array('ID', 'numerical'),
                array('Login', 'unique', 'message'=>'{attribute} "{value}" already exists!'),
                array('Login', 'required', 'message'=>'Login is required!'),
                array('Login', 'match', 'pattern' => '/^[a-zA-Z][a-zA-Z0-9!_-]+$/', 'message'=>'Login contains illegal characters'),
                array('Login', 'length', 'min'=>4),
                array('Password', 'required', 'on'=>'insert', 'message' => 'Password is required!'),
                array('Password', 'match', 'pattern' => '/^[a-zA-Z0-9!_*#-]{3,64}$/', 'message'=>'Password contains illegal characters'),
                array('Login', 'length', 'max'=>60),
                array('Password', 'length', 'max'=>32),
                array('LastName, FirstName', 'length', 'max'=>64),
                array('Role', 'length', 'max'=>11),
                array('Role', 'checkRole'),
                array('Role', 'required', 'message'=>'Role is required!'),
                array('ID, Login, Password, LastName, FirstName', 'safe', 'on'=>'search'),
                array('locationHierarchy', 'checkLocation'),
            );
	}

	public function attributeLabels() {
            return array(
                'ID' => 'ID',
                'Login' => 'Login',
                'Password' => 'Password',
                'LastName' => 'Last Name',
                'FirstName' => 'First Name',
                'IsDeleted' => 'Is Deactivated',
                'Role' => 'Role',
            );
	}
        
        public function passwordStrength($attribute, $params)
        {
            if ($params['strength'] === self::WEAK)
                $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';  
            elseif ($params['strength'] === self::STRONG)
                $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';  

            if(!preg_match($pattern, $this->$attribute))
              $this->addError($attribute, 'Your password is not strong enough!');
        }
        
        public function checkLocation($attribute, $params) {
            $role = Roles::model()->getRoleName($this->Role);
            if ($role != UserRoles::SuperAdmin) {
                if($this->locationHierarchy == null || count($this->locationHierarchy) < 1){
                    $this->addError($attribute, 'Your should select a location!');
                }
            }
        }
        
        public function getLocation() { 
            $result = Locations::model()->getLocationHierarchy($this->ID);            
            return $result;
        }

        public function checkRole($attribute, $params) {
            $auth = Yii::app()->authManager;
            $role = Roles::model()->getRoleName($this->prevRole);
            
            if($role === UserRoles::SuperAdmin && !Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                $this->addError($attribute, 'You aren\'t authorized to change this role!');
                $this->changeRole = FALSE;
            }
            else {
                $this->changeRole = TRUE;
            }
        }        
        
        public function beforeSave() {
            if(parent::beforeSave()) {   
                $locations = Locations::model()->defineLocation(true, $this->locationHierarchy);
                if ($this->isNewRecord || ($this->Password != NULL)) {
                    $pass = md5($this->Password);   
                } else {
                    $pass = $this->prevPassword;   
                }
                $this->Password = $pass;
                $_POST['location'] = $locations;
                if(!$this->changeRole) {
                    $this->Role = $this->prevRole;
                    return false;
                }
                else {
                    if (!Yii::app()->user->checkAccess('createLocalUser', array('post'=>$_POST)) && !Yii::app()->user->checkAccess(UserRoles::SuperAdmin)) {
                         $this->addError('', 'You aren\'t authorized to create/update User in this area!');
                         return false;
                    }
                    else {
                        return true;
                    }    
                }
            }
            else {
                return false;
            }
        }

        public function afterSave() {
            parent::afterSave(); 

            if ($this->isNewRecord) {
                $userId = $this->primaryKey;
            } else {
                $userId = $this->ID;
            }

            Locations::model()->setLocation($userId, $this->isNewRecord);
            $auth = Yii::app()->authManager;
            $prevRole = Roles::model()->getRoleName($this->prevRole);
            $role = Roles::model()->getRoleName($this->Role);

            if (Yii::app()->user->checkAccess('manageUsers')) {
                if($this->changeRole) {
                    $auth->revoke($prevRole, $this->ID); 
                    $auth->assign($role, $this->ID);
                    $auth->save();            
                }
            }
            return true;
        }
        
        public function beforeDelete() {
            parent::beforeDelete();
            $sql = "DELETE FROM `userlocations` WHERE UserID=".$this->ID.";";
            $command = Yii::app()->db->createCommand($sql); 
            $command->execute();
            
            $auth=Yii::app()->authManager;
            
            $role = Roles::model()->getRoleName($this->Role);
            $auth->revoke($role, $this->Login);
            $auth->save();
            return true;
        }
}