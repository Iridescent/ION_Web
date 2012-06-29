<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CollectionUtil
 *
 * @author dshyshen
 */
class CollectionUtil {
    public static function Diff($first, $second, $key){
        $result = array();
    }
    
    public static function Find($source, $key, $value){
        $result;
        foreach($source as $item){
            if ($item[$key] == $value){
                $result = $item;
                break;
            }
        }
        return $result;
    }
}

?>
