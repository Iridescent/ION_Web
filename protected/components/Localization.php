<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Localization
 *
 * @author dshyshen
 */
class Localization {
    const SERVER_DATE_FORMAT = "Y-m-d";
    const SERVER_TIME_FORMAT = "H:i:s";
    const SERVER_DATETIME_FORMAT = "Y-m-d H:i:s";
    
    const CLIENT_DATE_FORMAT = "m/d/Y";
    const CLIENT_TIME_FORMAT = "h:i A";
    const CLIENT_DATETIME_FORMAT = "m/d/Y h:i A";
    
    public static function ToServerDate($clientDate, $null=false){
        if ($clientDate != ''){
            if (!self::IsClientDateValid($clientDate)){
                return $clientDate;
            }
            $date = new DateTime($clientDate);
            return $date->format(self::SERVER_DATE_FORMAT);
        }
        return $null ? null : '';
    }
    
    public static function ToClientDate($serverDate, $null=false){
        if ($serverDate != ''){
            if (!self::IsServerDateValid($serverDate)){
                return $serverDate;
            }
            $date = new DateTime($serverDate);
            return $date->format(self::CLIENT_DATE_FORMAT);
        }
        return $null ? null : '';
    }
    
    public static function ToServerTime($clientTime, $null=false){
        if ($clientTime != ''){
            $time = new DateTime($clientTime);
            return $time->format(self::SERVER_TIME_FORMAT);
        }
        return $null ? null : '';
    }
    
    public static function ToClientTime($serverTime, $null=false){
        if ($serverTime != ''){
            $time = new DateTime($serverTime);
            return $time->format(self::CLIENT_TIME_FORMAT);
        }
        return $null ? null : '';
    }
    
    public static function IsClientDateValid($date){
        $valid = false;
        
        $parts = explode("/", $date);
        if (count($parts) == 3){
            $month = (int)$parts[0];
            $day = (int)$parts[1];
            $year = (int)$parts[2];
            return checkdate($month, $day, $year);
        }
        
        return $valid;
    }
    
    public static function IsServerDateValid($date){
        $valid = false;
        
        $parts = explode("-", $date);
        if (count($parts) == 3){
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            return checkdate($month, $day, $year);
        }
        
        return $valid;
    }
    
    public static function FormatPhone($phone){
        $timmed = trim($phone);
        if(strlen($timmed) == 10  && is_numeric($timmed)){
            return sprintf("(%u) %u-%u", substr($timmed, 0, 3), substr($timmed, 3, 3), substr($timmed, 6, 4));
        }
        return $phone;
    }
}

?>
