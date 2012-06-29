<?php

class BaseBulkLoader {
    
    protected $connectionString;
    protected $dbUser;
    protected $dbPassword;
    
    protected function __construct($connectionString, $dbUser, $dbPassword) {
        $this->connectionString = $connectionString;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
    }
    
    protected function getDbObject() {
        $db = new PDO($this->connectionString, $this->dbUser, $this->dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}

?>
