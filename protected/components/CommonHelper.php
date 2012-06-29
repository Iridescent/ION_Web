<?php

class CommonHelper {
    public static function BoolToInt($value) {
        return $value ? 1 : 0;
    }
    
    public static function SplitWithVBar($str) {
        return explode("|", $str);
    }
    
    public static function JoinWithVBar($array) {
        return implode("|", $array);
    }
    
}

?>
