<?php

class UserIdentity extends CUserIdentity
{   
    private $_id;
    
    public function authenticate()
    {
        $record=User::model()->findByAttributes(array('Login'=>$this->username));
      
        if($record===null)
        {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }
        else if($record->Password!==md5($this->password))
        {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
        /*else if($record->IsDeleted)
        {
            $this->errorCode=self::ERROR_USER_INACTIVE;
        }*/
        else
        {      
            $this->_id=$record->ID;
            $this->setState('id', $record->ID);
            $this->setState('title', $record->FirstName . ' ' . $record->LastName);
            $this->setState('role', $record->Role);
            $this->setState('location', Locations::model()->getAllUserLocations($this->_id));
            $this->setState('locationToSave', Locations::model()->getUserLocation($this->_id));
            $this->setState('isAdmin', $record->IsAdmin);

            $this->errorCode=self::ERROR_NONE;           
            
            $auth=Yii::app()->authManager;
        }
        return !$this->errorCode;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public static function IsSuperAdmin(){
        return Yii::app()->user->checkAccess(UserRoles::SuperAdmin);
    }
    
    public static function IsLocalAdmin(){
        return Yii::app()->user->checkAccess(UserRoles::LocalAdmin);
    }
    
    public static function Location(){
        return Yii::app()->user->location;
    }
    
}