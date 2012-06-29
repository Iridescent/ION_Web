<?php

class HouseholdValidator extends BaseValidator {
    private $nameColumnName;
    private $zipColumnName;
    private $nameIdx;
    private $zipIdx;
    
    public function __construct(){
        $header = array('household name', 'country', 'state', 'city', 'zip', 'phone',
                        'emergency 1 first name', 'emergency 1 last name', 'emergency 1 relationship', 'emergency 1 phone #',
                        'emergency 2 first name', 'emergency 2 last name', 'emergency 2 relationship', 'emergency 2 phone #',);
        parent::__construct($header);
        
        $this->nameColumnName = "Household Name";
        $this->zipColumnName = "Zip";
        $this->nameIdx = 0;
        $this->zipIdx = 4;
    }
    
    public function Validate(&$row, $rowNumber){
        $result = array();
        
        $name = $row[$this->nameIdx];
        $zip = $row[$this->zipIdx];
        
        if(!$name){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->nameColumnName, ParseResult::EmptyValue, $name);
        }
        if(!$zip){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->zipColumnName, ParseResult::EmptyValue, $zip);
        }
        else if (strlen($zip) != 5 || !is_numeric ($zip)){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->zipColumnName, ParseResult::IncorrectFormat, $zip);
        }
        
        return $result;
    }
}

?>
