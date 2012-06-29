<?php

class BaseValidator {
    private $header;
    
    protected function __construct(&$header) {
        $this->header = $header;
    }
    
    public function ValidateHeader(&$row){
        $columnCount = count($this->header);
        $result = $columnCount == count($row);
        if ($result){
            for($i=0; $i<$columnCount; $i++){
                if ($this->header[$i] != strtolower(trim($row[$i]))) {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }
    
    public function IsEmptyRow(&$row){
        $result = true;
        foreach($row as $column){
            if ($column){
               $result = false;
               break;
            }
        }
        return $result;
    }
}

?>
