<?php

class DictionaryEntityList {
    
    private $collection;
    
    public function __construct($collection){
        $this->collection = $collection;
    }
    
    public function GetIdByName($name){
        $result = -1;
        
        $trimmed = strtolower(trim($name));
        foreach($this->collection as $item){
            if (strtolower($item->Name) == $trimmed){
                $result = $item->Id;
                break;
            }
        }
            
        return $result;
    }
}

?>
