<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vkharyt
 * Date: 1/6/12
 * Time: 5:31 PM
 * To change this template use File | Settings | File Templates.
 */
class PersonReport// extends CActiveRecord
{
    public $sessions;
    
    public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'BarcodeID' => 'Barcode',
			'LastName' => 'Last Name',
			
		);
	}
    
    public function report($currentProgram = null, $sessions = null, $schools = null, $sites = null, $fromDate = null, $toDate = null, $persons = null)
    {
        
        $filters = "";
        $differensHours = "";
        
        if(isset($persons) and ($persons != NULL))
        {   
            $personList = (string) implode(",", $persons);
            $filters .= " WHERE persons.ID in ($personList) ";
        }
        
        if(isset($currentProgram) and ($currentProgram != NULL))
        {
            $filters .= " AND programs.ID = :currentProgram ";
            $addfilters = " AND Program = :currentProgram ";
        } 
        
        
        if(isset($sessions) and ($sessions != NULL))
        {   
            $firstSession = $sessions[0];
         
            $sessionsList = (string) implode(",", $sessions);
            $filters .= " AND sessions.ID in ($sessionsList) ";
            $addfilters .= " AND sessions.ID in ($sessionsList)";
        }
        
        $sessionsNumber = $sessions != NULL ? count($sessions) : "(select count(*) from sessions inner join programs on sessions.program=programs.id where programs.id=:currentProgram)";
        
        if(isset($schools) and ($schools != NULL))
        {   
            $schoolsList = (string) implode(",", $schools);
            $filters .= " AND sessions.SchoolSite in ($schoolsList) ";
        }
        
        if(isset($fromDate) and ($fromDate != NULL))
        {   
            $filters .= " AND programs.StartDate >= :fromDate ";
        }
        
        if(isset($toDate) and ($toDate != NULL))
        {   
            $filters .= " AND programs.EndDate <= :toDate ";
        }
        
        if(isset($sites) and ($sites != NULL))
        {   
            $sitesList = (string) implode(",", $sites);
            $filters .= " AND sessions.NonSchoolSite in ($sitesList) ";
        }
          
        
        if (Yii::app()->user->checkAccess('Local Admin')) {
       //     $filters .= " AND household.Location = :userLocation ";
        } elseif (Yii::app()->user->checkAccess('School Representative')) {
         //   $filters .= " AND schools.Location = :userLocation ";
        }
        
        
        //, TIMEDIFF(a.ExitDate, a.EnterDate) GROUP BY persons.ID
//        
        $connection=Yii::app()->db;
        
        $programs_sql = "SELECT DISTINCT programs.ID as ProgramId, programs.Description as PrDescr FROM persons 
                                LEFT JOIN attendance a ON persons.ID=a.Person
                                LEFT JOIN sessions ON a.Session=sessions.ID
                                LEFT JOIN programs ON sessions.Program = programs.ID ".$filters;        
        $prog_command = $connection->createCommand($programs_sql);
  
        $attendence_sql = "SELECT 
                        IF(a.Person is not NULL, 1, 0) as Present,
                        persons.ID as PersonID,
                        a.ID as AttendenceID, a.EnterDate, a.ExitDate,
                        IF(a.ExitDate IS NOT NULL, IF(a.EnterDate IS NOT NULL, TIME_TO_SEC(TIMEDIFF(a.ExitDate, a.EnterDate)), 0), 0) as SpendTimeSec,
                        programs.ID as ProgramId,
                        sessionadditional.Duration as sessionDuration
                            FROM persons 
                                LEFT JOIN attendance a ON persons.ID=a.Person
                                LEFT JOIN sessions ON a.Session=sessions.ID
                                LEFT JOIN programs ON sessions.Program = programs.ID
                                LEFT JOIN household hh ON persons.Household = hh.ID
                                LEFT JOIN persontype ON persons.Type = persontype.ID
                                LEFT JOIN personsubtype ON persons.Subtype = personsubtype.ID
                                LEFT JOIN sessionadditional ON sessions.ID = sessionadditional.SessionId
                    ".$filters;
        $attendence_command = $connection->createCommand($attendence_sql);
        
        $persons_sql = "SELECT 
                        distinct hh.ID as HouseholdID, hh.Name as HouseholdName, 
                        persons.ID as PersonID, persons.BarcodeID as BarcodeID, 
                        concat(persons.FirstName, ' ', persons.LastName) as StudentName, 
                        persontype.ID as PersontypeID, persontype.Name as Role
                            FROM persons 
                                LEFT JOIN attendance a ON persons.ID=a.Person
                                LEFT JOIN sessions ON a.Session=sessions.ID
                                LEFT JOIN programs ON sessions.Program = programs.ID
                                LEFT JOIN household hh ON persons.Household = hh.ID
                                LEFT JOIN persontype ON persons.Type = persontype.ID
                                LEFT JOIN personsubtype ON persons.Subtype = personsubtype.ID
                    ".$filters;
        $persons_command = $connection->createCommand($persons_sql);
        
        if(isset($currentProgram) and ($currentProgram != NULL))
        {
            $persons_command->bindParam(":currentProgram", $currentProgram, PDO::PARAM_INT);
            $prog_command->bindParam(":currentProgram", $currentProgram, PDO::PARAM_INT);
            $attendence_command->bindParam(":currentProgram", $currentProgram, PDO::PARAM_INT);
        }

        if(isset($fromDate) and ($fromDate != NULL))
        {
            $persons_command->bindParam(":fromDate", $fromDate, PDO::PARAM_STR);
            $prog_command->bindParam(":fromDate", $fromDate, PDO::PARAM_STR);
            $attendence_command->bindParam(":fromDate", $fromDate, PDO::PARAM_STR);
        }
        
        if(isset($toDate) and ($toDate != NULL))
        {
            $persons_command->bindParam(":toDate", $toDate, PDO::PARAM_STR);
            $prog_command->bindParam(":toDate", $toDate, PDO::PARAM_STR);
            $attendence_command->bindParam(":toDate", $toDate, PDO::PARAM_STR);
        }
        
        
        $prog_rows = $prog_command->queryAll(); 
        $attendence_rows = $attendence_command->queryAll(); 
        $persons_rows = $persons_command->queryAll(); 
        $return_array = array("programs"=>$prog_rows, "attendences"=>$attendence_rows, "persons"=> $persons_rows);
        
        return $return_array;
        
    }
    
    
}
