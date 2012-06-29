<?php

class PersonValidator extends BaseValidator {
    private $barcodeColumnName;
    private $firstNameColumnName;
    private $lastNameColumnName;
    private $roleNameColumnName;
    
    private $barcodeIdx;
    private $firstNameIdx;
    private $lastNameIdx;
    private $roleIdx;
    
    public function __construct(){
        $header = array('barcode #', 'participant first name', 'participant last name', 'dob', 'gender', 'role',
                        'relation', 'mobile#', 'home#', 'work#', 'e-mail', 'school', 'grade', 'insurance carrier',
                        'insurance number', 'physician name', 'physician phone number', 'allergies', 'medications',
                        'notes', 'special circumstances');
        parent::__construct($header);
        
        $this->barcodeColumnName = "Barcode #";
        $this->firstNameColumnName = "First Name";
        $this->lastNameColumnName = "Last Name";
        $this->dobColumnName = "Date Of Birth";
        $this->genderColumnName = "Gender";
        $this->roleNameColumnName = "Role";
        
        $this->barcodeIdx = 0;
        $this->firstNameIdx = 1;
        $this->lastNameIdx = 2;
        $this->dobIdx = 3;
        $this->genderIdx = 4;
        $this->roleIdx = 5;
    }
    
    public function Validate(&$row, $rowNumber){
        $result = array();
        
        $barcode = $row[$this->barcodeIdx];
        $firstName = $row[$this->firstNameIdx];
        $lastName = $row[$this->lastNameIdx];
        $dateOfBirth = $row[$this->dobIdx];
        $gender = $row[$this->genderIdx];
        $role = $row[$this->roleIdx];
        
        if(!$barcode){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->barcodeColumnName, ParseResult::EmptyValue, $barcode);
        }
        else if (strlen($barcode) != 6 || (int)$barcode == 0){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->barcodeColumnName, ParseResult::IncorrectFormat, $barcode);
        }
        if (!$firstName){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->firstNameColumnName, ParseResult::EmptyValue, $firstName);
        }
        if (!$lastName){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->lastNameColumnName, ParseResult::EmptyValue, $lastName);
        }
        if (!$role){
            $result[] = new ParseRowErrorInfo($rowNumber, $this->roleNameColumnName, ParseResult::EmptyValue, $role);
        }
        if ($dateOfBirth) {
            if (!Localization::IsClientDateValid($dateOfBirth)){
                $result[] = new ParseRowErrorInfo($rowNumber, $this->dobColumnName, ParseResult::IncorrectFormat, $dateOfBirth);
                $row[$this->dobIdx] = null;
            }
            else {
                $row[$this->dobIdx] = Localization::ToServerDate($dateOfBirth, true);
            }
        }
        else {
            $row[$this->dobIdx] = null;
        }
        
        return $result;
    }
}

?>
