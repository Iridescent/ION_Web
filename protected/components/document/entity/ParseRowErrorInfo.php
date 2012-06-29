<?php

class ParseRowErrorInfo {
    
    public function __construct($rowNumber = -1, $columnName = '', $parseResult = ParseResult::OK, $columnContent = ''){
        $this->rowNumber = $rowNumber;
        $this->columnName = $columnName;
        $this->parseResult = $parseResult;
        $this->columnContent = $columnContent;
        $this->errorDetails = ParseResult::getError($parseResult);
    }
    
    public $rowNumber;
    public $columnName;
    public $columnContent;
    public $parseResult;
    public $errorDetails;
    
    public function toString(){
        return 'Column "' . $this->columnName . '" Row ' .  $this->rowNumber . ' ' .  $this->errorDetails;
    }
}

?>
