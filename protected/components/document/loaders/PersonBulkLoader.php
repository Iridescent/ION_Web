<?php

class PersonBulkLoader extends BaseBulkLoader {
    const checkSql = "SELECT count(*) FROM persons WHERE BarcodeID = :barcode_id";
    const insertSql = "INSERT INTO persons (BarcodeID, FirstName, LastName, Household, DateOfBirth, Sex, Type, Subtype, MobilePhone, HomePhone,
                                            WorkPhone, EmailAddress, School, GradeLevel, InsuranceCarrier, InsuranceNumber, PhysicianName,
                                            PhysicianPhoneNumber, Allergies, Medications, Notes, SpecialCircumstances, LastUpdated, UpdateUserId)
                       VALUES (:barcode_id, :first_name, :last_name, :household, :date_of_birth, :sex, :type, :sub_type, :mobile_phone,
                               :home_phone, :work_phone, :email_address, :school, :grade_level, :insurance_carrier, :insurance_number, :physician_name,
                               :physician_phone_number, :allergies, :medications, :notes, :special_circumstances, :last_updated, :update_user_id)";
    
    public function __construct($connectionString, $dbUser, $dbPassword){
        parent::__construct($connectionString, $dbUser, $dbPassword);
    }
    
    public function Save(&$rows) {
        $result = null;
        $result->succeed = true;
        $db = null;
        
        $saved = 0;
        try {
            $db = parent::getDbObject();
            
            $checkStatement = $db->prepare(self::checkSql);
            $insertStatement = $db->prepare(self::insertSql);
            
            $barcodeId = '';
            $firstName = '';
            $lastName = '';
            $household = '';
            $dateOfBirth = '';
            $sex = '';
            $type = '';
            $subType = '';
            $mobilePhone = '';
            $homePhone = '';
            $workPhone = '';
            $emailAddress = '';
            $school = '';
            $gradeLevel = '';
            $insuranceCarrier = '';
            $insuranceNumber = '';
            $physicianName = '';
            $physicianPhoneNumber = '';
            $allergies = '';
            $medications = '';
            $notes = '';
            $speicalCircumstances = '';
            $lastUpdated = '';
            $updateUserId = '';
            
            $checkStatement->bindParam(':barcode_id', $barcodeId);
            $insertStatement->bindParam(':barcode_id', $barcodeId);
            $insertStatement->bindParam(':first_name', $firstName);
            $insertStatement->bindParam(':last_name', $lastName);
            $insertStatement->bindParam(':household', $household);
            $insertStatement->bindParam(':date_of_birth', $dateOfBirth);
            $insertStatement->bindParam(':sex', $sex);
            $insertStatement->bindParam(':type', $type);
            $insertStatement->bindParam(':sub_type', $subType);
            $insertStatement->bindParam(':mobile_phone', $mobilePhone);
            $insertStatement->bindParam(':home_phone', $homePhone);
            $insertStatement->bindParam(':work_phone', $workPhone);
            $insertStatement->bindParam(':email_address', $emailAddress);            
            $insertStatement->bindParam(':school', $school);
            $insertStatement->bindParam(':grade_level', $gradeLevel);
            $insertStatement->bindParam(':insurance_carrier', $insuranceCarrier);
            $insertStatement->bindParam(':insurance_number', $insuranceNumber);
            $insertStatement->bindParam(':physician_name', $physicianName);
            $insertStatement->bindParam(':physician_phone_number', $physicianPhoneNumber);
            $insertStatement->bindParam(':allergies', $allergies);
            $insertStatement->bindParam(':medications', $medications);
            $insertStatement->bindParam(':notes', $notes);
            $insertStatement->bindParam(':special_circumstances', $speicalCircumstances);
            $insertStatement->bindParam(':last_updated', $lastUpdated);
            $insertStatement->bindParam(':update_user_id', $updateUserId);
                    
            $db->beginTransaction();
            foreach($rows as $row){
                $barcodeId = $row[0];
                
                $checkStatement->execute();
                $count = (int)$checkStatement->fetchColumn();
                if ($count < 1){
                    $firstName = $row[1];
                    $lastName = $row[2];
                    $household = $row[3];
                    $dateOfBirth = $row[4];
                    $sex = $row[5];
                    $type = $row[6];
                    $subType = $row[7];
                    $mobilePhone = $row[8];
                    $homePhone = $row[9];
                    $workPhone = $row[10];
                    $emailAddress = $row[11];
                    $school = $row[12];
                    $gradeLevel = $row[13];
                    $insuranceCarrier = $row[14];
                    $insuranceNumber = $row[15];
                    $physicianName = $row[16];
                    $physicianPhoneNumber = $row[17];
                    $allergies = $row[18];
                    $medications = $row[19];
                    $notes = $row[20];
                    $speicalCircumstances = $row[21];
                    $lastUpdated = $row[22];
                    $updateUserId = $row[23];
                    $insertStatement->execute();
                    $saved++;
                }
            }
            $db->commit();
        }
        catch (Exception $e){
            $result->succeed = false;
            $result->error = new ParseGenericError($e->getMessage());
            if ($db){
                $db->rollBack();
            }
        }        
        $result->totalSaved = $saved;
        
        return $result;
    }
}

?>
