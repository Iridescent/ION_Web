<?php

class ParseResult {
    const OK = 0;
    const EmptyValue = 1;
    const IncorrectFormat = 2;
    const InvalidValue = 3;
    
    public static function getError($code){
        switch($code){
            case self::OK: {
                return "";
            }
            case self::EmptyValue: {
                return "Empty cell";
            }
            case self::IncorrectFormat: {
                return "Incorrect format";
            }
            case self::InvalidValue: {
                return "Invalid value";
            }
        }
        return "";
    }
}

?>
