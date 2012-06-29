<?php

class LocationCache {
    
    private $cache;
    
    public function __construct() {
        $cache = array();
    }
    
    public function GetLocationId($countryName, $stateName, $cityName){        
        $country = strtolower(trim($countryName));
        $state = strtolower(trim($stateName));
        $city = strtolower(trim($cityName));
        $key = $country . '_' . $state . '_' . $city;
        
        $result = $this->cache[$key];
        if (!$result){
            $result = -1;
            $ids = Locations::model()->getLocationId($country, $state, $city);
            if (count($ids) == 1){
                $result = $ids[0]->ID;
                $this->cache[$key] = $result;
            }
        }
        return $result;
    }
}

?>
