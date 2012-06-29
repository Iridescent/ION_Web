<?php

class SchoolCache {
    private $cache;
    
    public function __construct() {
        $cache = array();
    }
    
    public function GetSchoolId($schoolName){
        $result = -1;
        
        if ($schoolName && $schoolName != ''){
            $key = strtolower(trim($schoolName));
            $result = $this->cache[$key];
            if(!$result){
                $result = -1;
                $ids = School::model()->getIdByName($key);
                if (count($ids) > 0){
                    $result = $ids[0]->ID;
                    $this->cache[$key] = $result;
                }
            }
        }
        return $result;
    }
}

?>
