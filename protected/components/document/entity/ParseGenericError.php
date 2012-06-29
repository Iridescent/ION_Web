<?php

class ParseGenericError {
    private $errorDetails;
    
    public function __construct($errorDetails = '') {
        $this->errorDetails = $errorDetails;
    }

    public function toString(){
        return $this->errorDetails;
    }
}

?>
