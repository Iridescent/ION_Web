<?php
require_once AMFPHP_ROOTPATH . 'All/DbConfig.php';

class KeyTagService
{
    const personTypesQuery = "SELECT * FROM persontype";
    const personSubTypesQuery = "SELECT * FROM personsubtype";
    const gradeLevelsQuery = "SELECT * FROM gradelevel";
    const programTypesQuery = "SELECT * FROM programtype";
    const countriesQuery = "SELECT * FROM country";
    const statesQuery = "SELECT * FROM state";
    const citiesQuery = "SELECT * FROM city";
    const locationsQuery = "SELECT * FROM locations";
    const rolesQuery = "SELECT * FROM roles";
    const usersQuery = "SELECT * FROM users";
    const schoolsQuery = "SELECT * FROM schools";
    const sitesQuery = "SELECT * FROM sites";
    const householdsQuery = "SELECT * FROM household";
    const personsQuery = "SELECT * FROM persons";
    const programsQuery = "SELECT * FROM programs";
    const sessionsQuery = "SELECT * FROM sessions";
    const attendanceQuery = "SELECT * FROM attendance";
    const weatherDictionaryQuery = "SELECT * FROM weatherdictionary";
    const sessionAdditionalQuery = "SELECT * FROM sessionadditional";
	
    const personUpdateQuery = "UPDATE persons SET
                BarcodeID = :barcodeid,
                LastName = :lastname,
                FirstName = :firstname,
                Household = :household,
                EmailAddress = :emailaddress,
                Sex = :sex,
                GradeLevel = :gradelevel,
                Type = :type,
                Subtype = :subtype,
                School = :school,
                DateofBirth = :dateofbirth,
                WorkPhone = :workphone,
                HomePhone = :homephone,
                MobilePhone = :mobilephone,
                SpecialCircumstances = :specialcircumstances,
                PhysicianName = :physicianname,
                PhysicianPhoneNumber = :physicianphonenumber,
                Allergies = :allergies,
                Medications = :medications,
                PicasaLink = :picasalink,
                GDocSurvey = :gdocsurvey,
                GDocApplication = :gdocapplication,
                InsuranceCarrier = :insurancecarrier,
                InsuranceNumber = :insurancenumber,
                LastUpdated = :lastupdated,
                Notes = :notes,
                UpdateUserId = :updateuserid
            WHERE ID = :id AND (LastUpdated <= :lastupdated OR LastUpdated is null)";

    const selectCountAttendance = "SELECT count(*) FROM attendance WHERE Person = :person AND Session = :session";
    const attendanceInsertQuery = "INSERT INTO attendance (Person, Session, EnterDate, ExitDate, Keytag, LastUpdated, UpdateUserId)
                                   VALUES (:person,:session,:enterdate,:exitdate,:keytag,:lastupdated,:updateuserid)";
    const attendanceUpdateQuery = "UPDATE attendance SET EnterDate = :enterdate, ExitDate = :exitdate, Keytag = :keytag,
                                                         UpdateUserId = :updateuserid, LastUpdated = :lastupdated
                                   WHERE Person = :person AND Session = :session AND (LastUpdated <= :lastupdated OR LastUpdated is null)";
    
    const sessionAdditionalCountQuery = "SELECT count(*) FROM sessionadditional WHERE SessionId = :sessionid";
    const sessionAdditionalInsertQuery = "INSERT INTO sessionadditional (Duration, WeatherId, SessionId, LastUpdated)
                                          VALUES (:duration, :weatherid, :sessionid, :lastupdated)";
    const sessionAdditionalUpdateQuery = "UPDATE sessionadditional SET Duration = :duration, WeatherId = :weatherid, LastUpdated = :lastupdated
                                          WHERE SessionId = :sessionid";

    public function GetTimeZoneOffset() {
        $timezone = new DateTimeZone(date_default_timezone_get());
        return $timezone->getOffset(new DateTime("now"));
    }
	
    public function GetPersonTypeList() {
        return $this->getResult(self::personTypesQuery);
    }
	
    public function GetPersonSubTypeList() {
        return $this->getResult(self::personSubTypesQuery);
    }
	
    public function GetGradeLevelList() {
        return $this->getResult(self::gradeLevelsQuery);
    }

    public function GetProgramTypeList() {
        return $this->getResult(self::programTypesQuery);
    }

    public function GetCountryList() {
        return $this->getResult(self::countriesQuery);
    }

    public function GetStateList() {
        return $this->getResult(self::statesQuery);
    }

    public function GetCityList() {
        return $this->getResult(self::citiesQuery);
    }

    public function GetLocationList() {
        return $this->getResult(self::locationsQuery);
    }

    public function GetRoleList() {
        return $this->getResult(self::rolesQuery);
    }

    public function GetUserList() {
        return $this->getResult(self::usersQuery);
    }
	
    public function GetSchoolList() {
        return $this->getResult(self::schoolsQuery);
    }

    public function GetSiteList() {
        return $this->getResult(self::sitesQuery);
    }

    public function GetHouseholdList() {
        return $this->getResult(self::householdsQuery);
    }

    public function GetPersonList() {
        return $this->getResult(self::personsQuery);
    }

    public function GetProgramList() {
        return $this->getResult(self::programsQuery);
    }

    public function GetSessionList() {
        return $this->getResult(self::sessionsQuery);
    }

    public function GetAttendanceList() {
        return $this->getResult(self::attendanceQuery);
    }

    public function GetWeatherList() {
        return $this->getResult(self::weatherDictionaryQuery);
    }
	
    public function GetSessionAdditionalList() {
        return $this->getResult(self::sessionAdditionalQuery);
    }
	
    public function Synchronize($persons, $attendance, $sessionAdditional) {
        $syncResult = "";
        $db = null;

        try {
            //init
            $db = new PDO("mysql:host=".DbConfig::Host.";dbname=".DbConfig::DbName, DbConfig::DbUser, DbConfig::DbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //persons preparation
            $personPrepare = $db->prepare(self::personUpdateQuery);

            $p_id="";
            $p_barcodeid="";
            $p_lastname="";
            $p_firstname="";
            $p_household="";
            $p_emailaddress="";
            $p_sex="";
            $p_gradelevel="";
            $p_type="";
            $p_subtype ="";
            $p_school="";
            $p_dateofbirth="";
            $p_workphone="";
            $p_homephone="";
            $p_mobilephone="";
            $p_specialcircumstances="";
            $p_physicianname="";
            $p_physicianphonenumber="";
            $p_allergies="";
            $p_medications="";
            $p_picasalink="";
            $p_gdocsurvey="";
            $p_gdocapplication="";
            $p_insurancecarrier="";
            $p_insurancenumber="";
            $p_notes="";
            $p_lastupdated="";
            $p_updateuserid="";

            $personPrepare->bindParam(":id", $p_id);
            $personPrepare->bindParam(":barcodeid", $p_barcodeid);
            $personPrepare->bindParam(":lastname", $p_lastname);
            $personPrepare->bindParam(":firstname", $p_firstname);
            $personPrepare->bindParam(":household", $p_household);
            $personPrepare->bindParam(":emailaddress", $p_emailaddress);
            $personPrepare->bindParam(":sex", $p_sex);
            $personPrepare->bindParam(":gradelevel", $p_gradelevel);
            $personPrepare->bindParam(":type", $p_type);
            $personPrepare->bindParam(":subtype", $p_subtype);
            $personPrepare->bindParam(":school", $p_school);
            $personPrepare->bindParam(":dateofbirth", $p_dateofbirth);
            $personPrepare->bindParam(":workphone", $p_workphone);
            $personPrepare->bindParam(":homephone", $p_homephone);
            $personPrepare->bindParam(":mobilephone", $p_mobilephone);
            $personPrepare->bindParam(":notes", $p_notes);
            $personPrepare->bindParam(":specialcircumstances", $p_specialcircumstances);
            $personPrepare->bindParam(":physicianname", $p_physicianname);
            $personPrepare->bindParam(":physicianphonenumber", $p_physicianphonenumber);
            $personPrepare->bindParam(":allergies", $p_allergies);
            $personPrepare->bindParam(":medications", $p_medications);
            $personPrepare->bindParam(":medications", $p_medications);
            $personPrepare->bindParam(":picasalink", $p_picasalink);
            $personPrepare->bindParam(":gdocsurvey", $p_gdocsurvey);
            $personPrepare->bindParam(":gdocapplication", $p_gdocapplication);
            $personPrepare->bindParam(":insurancecarrier", $p_insurancecarrier);
            $personPrepare->bindParam(":insurancenumber", $p_insurancenumber);
            $personPrepare->bindParam(":lastupdated", $p_lastupdated);
            $personPrepare->bindParam(":updateuserid", $p_updateuserid);

            //attendance preparation
            $attendanceCountPrepare = $db->prepare(self::selectCountAttendance);
            $attendanceUpdatePrepare = $db->prepare(self::attendanceUpdateQuery);
            $attendanceInsertPrepare = $db->prepare(self::attendanceInsertQuery);

            $a_person="";
            $a_session="";
            $a_enterdate="";
            $a_exitdate="";
            $a_keytag="";
            $a_lastupdated="";
            $a_updateuserid="";

            $attendanceCountPrepare->bindParam(":person", $a_person);
            $attendanceCountPrepare->bindParam(":session", $a_session);

            $attendanceUpdatePrepare->bindParam(":person", $a_person);
            $attendanceUpdatePrepare->bindParam(":session", $a_session);
            $attendanceUpdatePrepare->bindParam(":enterdate", $a_enterdate);
            $attendanceUpdatePrepare->bindParam(":exitdate", $a_exitdate);
            $attendanceUpdatePrepare->bindParam(":keytag", $a_keytag);
            $attendanceUpdatePrepare->bindParam(":lastupdated", $a_lastupdated);
            $attendanceUpdatePrepare->bindParam(":updateuserid", $a_updateuserid);

            $attendanceInsertPrepare->bindParam(":person", $a_person);
            $attendanceInsertPrepare->bindParam(":session", $a_session);
            $attendanceInsertPrepare->bindParam(":enterdate", $a_enterdate);
            $attendanceInsertPrepare->bindParam(":exitdate", $a_exitdate);
            $attendanceInsertPrepare->bindParam(":keytag", $a_keytag);
            $attendanceInsertPrepare->bindParam(":lastupdated", $a_lastupdated);
            $attendanceInsertPrepare->bindParam(":updateuserid", $a_updateuserid);
            
            //sessionAdditional prepare
            $sessionCountStatement = $db->prepare(self::sessionAdditionalCountQuery);
            $sessionInsertStatement = $db->prepare(self::sessionAdditionalInsertQuery);
            $sessionUpdateStatement = $db->prepare(self::sessionAdditionalUpdateQuery);
            
            $sa_duration="";
            $sa_weatherid="";
            $sa_sessionid="";
            $sa_lastupdated="";
            
            $sessionCountStatement->bindParam(":sessionid", $sa_sessionid);
            
            $sessionInsertStatement->bindParam(":sessionid", $sa_sessionid);
            $sessionInsertStatement->bindParam(":duration", $sa_duration);
            $sessionInsertStatement->bindParam(":weatherid", $sa_weatherid);
            $sessionInsertStatement->bindParam(":lastupdated", $sa_lastupdated);
            
            $sessionUpdateStatement->bindParam(":sessionid", $sa_sessionid);
            $sessionUpdateStatement->bindParam(":duration", $sa_duration);
            $sessionUpdateStatement->bindParam(":weatherid", $sa_weatherid);
            $sessionUpdateStatement->bindParam(":lastupdated", $sa_lastupdated);
            
            $db->beginTransaction();
            //save persons
            if ($persons != null) {
                foreach ($persons as $person) {
                    $p_id = intval($person->Id);
                    $p_barcodeid = strval($person->BarcodeId);
                    $p_lastname = strval($person->LastName);
                    $p_firstname = strval($person->FirstName);
                    $p_household = intval($person->Household);
                    $p_emailaddress = strval($person->EmailAddress);
                    $p_sex = strval($person->Sex);
                    $p_gradelevel = $this->toNullableInt($person->GradeLevel);
                    $p_type = intval($person->Type);
                    $p_subtype = $this->toNullableInt($person->SubType);
                    $p_school = $this->toNullableInt($person->School);
                    $p_dateofbirth = $this->toNullableString($person->DateOfBirthString);
                    $p_workphone = strval($person->WorkPhone);
                    $p_homephone = strval($person->HomePhone);
                    $p_mobilephone = strval($person->MobilePhone);
                    $p_specialcircumstances = strval($person->SpecialCircumstances);
                    $p_physicianname = strval($person->PhysicianName);
                    $p_physicianphonenumber = strval($person->PhysicianPhoneNumber);
                    $p_allergies = strval($person->Allergies);
                    $p_medications = strval($person->Medications);
                    $p_picasalink = strval($person->PicasaLink);
                    $p_gdocsurvey = strval($person->GDocSurvey);
                    $p_gdocapplication = strval($person->GDocApplication);
                    $p_insurancecarrier = strval($person->InsuranceCarrier);
                    $p_insurancenumber = strval($person->InsuranceNumber);
                    $p_lastupdated = $this->toNullableString($person->LastUpdatedString);
                    $p_notes = strval($person->Notes);
                    $p_updateuserid = $this->toNullableInt($person->UpdateUserId);

                    $personPrepare->execute();
                }
            }
            //save attendance
            if($attendance != null) {
                foreach($attendance as $nextattendance) {
                    $a_person = intval($nextattendance->PersonId);
                    $a_session = intval($nextattendance->SessionId);
                    $a_enterdate = $this->toNullableString($nextattendance->EnterDateString);
                    $a_exitdate = $this->toNullableString($nextattendance->ExitDateString);
                    $a_keytag = intval($nextattendance->KeyTag);
                    $a_lastupdated = $this->toNullableString($nextattendance->LastUpdatedString);
                    $a_updateuserid = $this->toNullableInt($nextattendance->UpdateUserId);

                    $attendanceCountPrepare->execute();
                    $count = (int)$attendanceCountPrepare->fetchColumn();

                    if ($count > 0){
                        $attendanceUpdatePrepare->execute();
                    }
                    else {
                        $attendanceInsertPrepare->execute();
                    }
                }
            }
            //save session additional
            if($sessionAdditional != null){
                foreach($sessionAdditional as $item) {
                    $sa_duration = intval($item->Duration);
                    $sa_weatherid = intval($item->WeatherId);
                    $sa_sessionid = intval($item->SessionId);
                    $sa_lastupdated = $this->toNullableString($item->LastUpdatedString);
                    
                    $sessionCountStatement->execute();
                    $count = (int)$sessionCountStatement->fetchColumn();

                    if ($count > 0){
                        $sessionUpdateStatement->execute();
                    }
                    else {
                        $sessionInsertStatement->execute();
                    }
                }
            }
            $db->commit();
        }
        catch (Exception $e) {
            $syncResult = $e->getMessage();
            if ($db != null) {
                $db->rollback();
            }
        }

        return $syncResult;
    }

    private function getResult($query) {
        $result = array();
        try {
            $db = new PDO("mysql:host=".DbConfig::Host.";dbname=".DbConfig::DbName, DbConfig::DbUser, DbConfig::DbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st = $db->prepare($query);
            $st->execute();
            $result = $st->fetchAll(PDO::FETCH_CLASS);
        }
        catch (Exception $e) {
            //return $e->getMessage();
        }
        return $result;
    }
    
    private function toNullableString($value){
        $result = strval($value);
        return $result == "" ? null : $result;
    }
    
    private function toNullableInt($value){
        $result = intval($value);
        return $result == 0 ? null : $result;
    }
}
 ?>