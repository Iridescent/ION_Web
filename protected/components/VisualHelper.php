<?php

class VisualHelper {
    public static function CheckBoxList($items, $parentId, $markerId, $withAll = false){
        $result = '';
        
        $result .= '<input id="' . $markerId . '_parent" value="' . $parentId . '" type="hidden" />';
        if (count($items) > 0){
            if ($withAll){
                $result .= '<div><input id="' . $markerId . '_0" type="checkbox" value="0" />';
                $result .= '<label for="0">All</label></div>';
            }
            foreach($items as $key => $value){
                $result .= '<div><input id="' . $markerId . '_' . $key . '" type="checkbox" value="' . $key . '" />';
                $result .= '<label for="'. $key . '">' . $value . '</label></div>';
            }
        }
        
        return $result;
    }
}

?>
