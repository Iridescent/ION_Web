<?php

class CuriosityMachineServiceClient {
    
    private $endpointUrl;
    private $userName;
    private $password;
    
    public function __construct($endpointUrl, $userName, $password){
        $this->endpointUrl = $endpointUrl;
        $this->userName = $userName;
        $this->password = $password;
    }
    
    public function Login(){
        
    }
    
    public function Logout(){
        
    }
    
    public function GetCMUserById($userId){
        $user = $this->getFakeUser();
        return $user;
    }
    
    public function GetCMUserByInfo($firstName, $lastName, $birthDate){
        $user = $this->getFakeUser();
        return $user;
    }
    
    public function SaveCMUser($user){
        $user = $this->getFakeUser();
        return $user;
    }
    
    private function getFakeUser() {
        $result = new stdClass();
        
        $result->user_id = 1;
        $result->user_profile_link = 'http://exapmle.com/userprofile?1';
        $result->user_profile_picture_link = 'http://exapmle.com/sites/default/files/1.jpg';
        $result->user_points = 123;
        $result->user_badges = array('http://exapmle.com/sites/default/files/open_badges/badge_1.jpg',
                                     'http://exapmle.com/sites/default/files/open_badges/badge_1.jpg');
        $result->user_experiments = array('http://exapmle.com/node/123', 'http://exapmle.com/node/234');
        
        return $result;
    }
}

?>
