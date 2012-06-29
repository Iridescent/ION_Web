<?php

class HouseholdBulkLoader extends BaseBulkLoader {
    const checkSql = "SELECT count(*) FROM household WHERE Name = :household_name";
    const insertSql = "INSERT INTO household (Name, Location, ZipPostal, Phone, Emergency1FirstName, Emergency1LastName,
                       Emergency1Relationship, Emergency1MobilePhone, Emergency2FirstName, Emergency2LastName,
                       Emergency2Relationship, Emergency2MobilePhone, LastUpdated, UpdateUserId)
                       VALUES (:household_name, :household_location, :household_zip, :household_phone,
                       :emergency1_first_name, :emergency1_last_name, :emergency1_relationship, :emergency1_mobile_phone,
                       :emergency2_first_name, :emergency2_last_name, :emergency2_relationship, :emergency2_mobile_phone,
                       :last_updated, :update_user_id)";
    
    public function __construct($connectionString, $dbUser, $dbPassword){
        parent::__construct($connectionString, $dbUser, $dbPassword);
    }
    
    public function Save(&$rows) {
        $result = null;
        $result->succeed = true;
        
        $saved = 0;
        try {
            $db = parent::getDbObject();
            
            $checkStatement = $db->prepare(self::checkSql);
            $insertStatement = $db->prepare(self::insertSql);
            
            $householdName = '';
            $householdLocation = '';
            $householdZip = '';
            $householdPhone = '';
            $emergency1FirstName = '';
            $emergency1LastName = '';
            $emergency1Relationship = '';
            $emergency1MobilePhone = '';
            $emergency2FirstName = '';
            $emergency2LastName = '';
            $emergency2Relationship = '';
            $emergency2MobilePhone = '';
            $lastUpdated = '';
            $updateUserId = '';
            
            $checkStatement->bindParam(':household_name', $householdName);
            $insertStatement->bindParam(':household_name', $householdName);
            $insertStatement->bindParam(':household_location', $householdLocation);
            $insertStatement->bindParam(':household_zip', $householdZip);
            $insertStatement->bindParam(':household_phone', $householdPhone);
            $insertStatement->bindParam(':emergency1_first_name', $emergency1FirstName);
            $insertStatement->bindParam(':emergency1_last_name', $emergency1LastName);
            $insertStatement->bindParam(':emergency1_relationship', $emergency1Relationship);
            $insertStatement->bindParam(':emergency1_mobile_phone', $emergency1MobilePhone);
            $insertStatement->bindParam(':emergency2_first_name', $emergency2FirstName);
            $insertStatement->bindParam(':emergency2_last_name', $emergency2LastName);
            $insertStatement->bindParam(':emergency2_relationship', $emergency2Relationship);
            $insertStatement->bindParam(':emergency2_mobile_phone', $emergency2MobilePhone);
            $insertStatement->bindParam(':last_updated', $lastUpdated);
            $insertStatement->bindParam(':update_user_id', $updateUserId);
                    
            $db->beginTransaction();
            foreach($rows as $row){
                $householdName = $row[0];
                
                $checkStatement->execute();
                $count = (int)$checkStatement->fetchColumn();
                if ($count < 1){
                    $householdLocation = $row[1];
                    $householdZip = $row[2];
                    $householdPhone = $row[3];
                    $emergency1FirstName = $row[4];
                    $emergency1LastName = $row[5];
                    $emergency1Relationship = $row[6];
                    $emergency1MobilePhone = $row[7];
                    $emergency2FirstName = $row[8];
                    $emergency2LastName = $row[9];
                    $emergency2Relationship = $row[10];
                    $emergency2MobilePhone = $row[11];                    
                    $lastUpdated = $row[12];
                    $updateUserId = $row[13];
                    $insertStatement->execute();
                    $saved++;
                }
            }
            $db->commit();
        }
        catch (Exception $e){
            $result->succeed = false;
            $result->error = new ParseGenericError($e->getMessage());
            $db->rollBack();
        }        
        $result->totalSaved = $saved;
        
        return $result;
    }
}

?>
