<?php

class groupReport
{
    private $prevPersonId = null;
    private $prevProgramId = null;
    private $prevSessionId = null;
    private $prevPersonTypeId = null;

    private $prevProgram = null;
    private $prevSession = null;
    private $prevPersonType = null;

    private $model = null;
    private $programId = null;
    private $sessionId = null;
    
    private $row = null;
    private $key = null;

    private $hasProgramHeader = null;
    private $hasSessionHeader = null;
    private $hasPersonTypeHeader = null;
    private $uniqueSessions = null;
    private $uniqueSessionsCount = null;
    private $sessionsHeaders = '';

    private $noMorePersonsRow = false;
    private $sessionsCount = 0;
    private $sessions = array();
    private $personSessions = array();
    
    private $uniqueHouseholdsList = array();
    private $uniquePersons = array();
    private $rolesList = array();
    private $uniqueRoles = array();
    private $uniqueHouseholds = 0;
    
    private $collectPersonTypeRows = array();

    public function __construct($model, $programId, $sessions, $schools, $sites, $fromDate, $toDate, $showHours, $viewSummary, $perfectAttendance)
    { 
        $this->model = $model;
        $this->programId = $programId;     
        $this->showHours = $showHours;
        $this->viewSummary = $viewSummary;
        $this->perfectAttendance = $perfectAttendance;
    }

    
    public function render() // with totals
    
    {  
        $data = $this->model;
        
        $this->sessionsCount = $data[0];
        array_shift($data);

        $firstSession = $this->model[0]['commonSessID'];// print_r($firstSession); //die();
        $i = 0;
        while ($i < $this->sessionsCount) {
            $this->sessionsHeaders .= "<th class='personType'>".Localization::ToClientDate($data[0]['sessionStartDate'])."</th>"; //"<th class='personType'>Session ".$i."</th>"; 
            $this->sessions[] = array_shift($data);
            $i += 1;
        }
        if(!$this->viewSummary) {
            $this->sessionsHeaders .="<th class='personType'>Attendance %</th>"; 
        }    
        
        foreach ($this->sessions as $key => $value){
            $this->uniquePersons[$value['sessionID']] = 0;
        }
        
        $this->rolesList = CHtml::listData(PersonType::model()->findAll(array('order' => 'Name')), 'ID', 'Name');
        
       
        foreach($data as $key => $row) {
            
            if ($row['PersonID'] == $prevPerson) {
                foreach ($this->sessions as $key => $value){
                    if ($row['SessionPresence'] == $value['sessionID']) { 
                         $this->personSessions[$row['PersonID']][$value['sessionID']] = 1;
                         $this->personSessions[$row['PersonID']]['Attendance'] += 1; 
                         $this->personSessions[$row['PersonID']]['sumHours'] += $row['Hours'];
                         $this->uniquePersons[$value['sessionID']] += 1;
                    }
                }    

            } else {
                $this->personSessions[$row['PersonID']]['Attendance'] = 0;
                foreach ($this->sessions as $key => $value){
                    $this->personSessions[$row['PersonID']][$value['sessionID']] = 0; 
                    $this->personSessions[$row['PersonID']]['sumHours'] = 0;
                            
                    if ($row['SessionPresence'] == $value['sessionID']) {
                          $this->personSessions[$row['PersonID']][$value['sessionID']] = 1;
                          $this->personSessions[$row['PersonID']]['Attendance'] += 1;
                           $this->personSessions[$row['PersonID']]['sumHours'] += $row['Hours'];
                          $this->personSessions[$row['PersonID']]['Role'] = $row['Role']; 
                          if ($row['HouseholdID']){
                            $this->uniqueHouseholdsList[] = $row['HouseholdID'];
                          }  
                          $this->uniquePersons[$value['sessionID']] += 1;
                   } 
                }  
            } 
            
            $prevPerson = $row['PersonID'];
        } 
        $this->uniqueHouseholds = count(array_count_values($this->uniqueHouseholdsList));
        
        foreach ($this->personSessions as $key => $value) {
            
            if (in_array($value['Role'], $this->rolesList)) {
                $this->uniqueRoles[$value['Role']] += 1;
            } 
            
        }
        		
	echo "<div class='clear'></div>";

        echo  "<div id='reportsheet' class='content-black'>";
        echo "<table>"; 

        if (count ($data) <= 1) {
           print ('No data to display');
           return;
        }
        
        foreach($data as $key => $row)
        {
            $this->key = $key;
            $this->row = $row;

            $this->renderProgramHeader($row, $this->sessionsHeaders);
           
            
            
            if (!$this->viewSummary && (!$this->perfectAttendance || !$this->noMorePersonsRow)) {
                              
                $this->renderPersonRow($row);
            }    

            $this->prevProgramId = $row["ProgramId"];
            $this->prevSessionId = $row["SessionId"];
            
            $this->prevPersonId = $row["PersonID"];
            
            $this->prevPersonTypeId = $row["PersonTypeId"];

            $this->prevProgram = $row["ProgramDescr"];
            $this->prevSession = $row["Session"];
            $this->prevPersonType = $row["PersonType"]; 
           
        }     // print_r($this->uniqueSessionsCount); die();
 
        $this->renderProgramFooter(true);

        echo "</table>";
        echo  "</div>";
    }

    function needProgramHeader()
    {
        return $this->programChanged() and !$this->hasProgramHeader and $this->isPersonRow();
    }

    function needSessionHeader()
    {
        return $this->sessionChanged() and !$this->hasSessionHeader and $this->isPersonRow();
    }

    function needPersonTypeHeader()
    {
        return $this->personTypeChanged() and !$this->hasPersonTypeHeader and $this->isPersonRow();
    }

    function programChanged()
    {
        return $this->prevProgramId != $this->row["ProgramId"];
    }

    function sessionChanged()
    {     
        return $this->prevSessionId != $this->row["SessionId"];
      
    }

    function personTypeChanged()
    {
        return $this->prevPersonTypeId != $this->row["PersonTypeId"];
    }

    function isPersonRow()
    {
        return !is_null($this->row["PersonID"]);
    }

    function isPersonTypeFooter()
    {
        return is_null($this->row["PersonsID"]) and !is_null($this->row["PersonTypeId"]);
    }

    function isSessionFooter()
    {
        return is_null($this->row["PersonTypeId"]) and !is_null($this->row["SessionId"]);
    }

    function isProgramFooter()
    {
        return is_null($this->row["SessionId"]) and !is_null($this->row["ProgramId"]);
    }

    function isGrandTotals()
    {
        return is_null($this->row["PersonsID"]) and is_null($this->row["PersonTypeId"]) and is_null($this->row["SessionId"]) and is_null($this->row["ProgramId"]);
    }

    function renderProgramHeader($item, $headers)
    {
        if ($this->prevProgramId === $item['ProgramId']) {
            return; 
        } else {
        ?>

    <tr class="program header-big" align="center">
        <td colspan="8"><?php echo CHtml::encode($item['PrDescr']); ?></td>
    </tr>
    <tr class="date">
        <td colspan="8">
            <?php echo Localization::ToClientDate($item['ProgramStartDate']) . ' - ' . Localization::ToClientDate($item['ProgramEndDate']) ?>
        </td>
    </tr>
    
    <?php
        }
        
        $this->hasProgramHeader = true;
     ?>   
        <tr class="session-header"><td></td></tr>
        <tr class="session-header first-row">
        <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
        <td colspan="3"><?php echo CHtml::encode($item['Session']); ?></td>
        <td colspan="3"><?php echo Localization::ToClientDate($item['SessionStart']); ?></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <?php
            $i = 0;
            while ($i < $this->sessionsCount) {        
                print "<td></td>";
                $i += 1;
            }
        ?>
    </tr>
    <tr class="session-header">
       <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td> 
       <td colspan="3"><?php //echo "Location: "; ?></td>
       <td colspan="3"><?php //echo CHtml::encode($item['SiteName']); ?></td>
    </tr>    
    
    <?php if(!$this->viewSummary) { ?>
    <tr>
        <th></th>
        <th class="personType">Household Name</th>

        <th class="personType">Barcode #</th>
        <th class="personType">Student Name</th>
        <th class="personType">Role</th>
        <th class="personType">Age</th>
        <th class="personType">Telephone</th>
        <th class="personType">E-mail</th>
        <th class="personType">Photo Waiver</th>
       <?php } else { ?>
        <th></th>
        <th class="personType"></th>

        <th class="personType"></th>
        <th class="personType"></th>
        <th class="personType"></th>
        <th class="personType"></th>
        <th class="personType"></th>
        <th class="personType"></th>
        <th class="personType"></th>        
       <?php }
            print $headers;          
       ?>                 
        
        <?php if(isset($this->showHours) and ((int)$this->showHours === 1) && !$this->viewSummary): ?>
            <th class="personType">Hours</th>
        <?php endif; ?>
            
    </tr>
   
    <?php
        $this->uniqueSessions += 1;
        $this->hasSessionHeader = true;
        $this->renderPersonTypeHeader($item, true);

    }

    function renderSessionHeader($item, $force = false)
    {
    }

    function renderPersonTypeHeader($item, $force = false)
    {
        return;
        if(!$force)
            if(!$this->needPersonTypeHeader()) return;

        ?>
    <tr class="personType">
        <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
        <td><?php //echo CHtml::encode($item['Session']); ?></td>
        <td><?php //echo CHtml::encode($item['PersonTypeId']); ?></td>
        <td colspan="5"><?php echo "Participant Type: ".CHtml::encode($item['PersonType']); ?></td>
        <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
        <td><?php //echo CHtml::encode($item['FirstName']); ?></td>
        <td><?php //echo CHtml::encode($item['MiddleName']); ?></td>
        <td><?php //echo CHtml::encode($item['LastName']); ?></td>
    </tr
    
    <?php if(!$this->viewSummary): ?>
    <tr class="personType">
        <th class="personType">Barcode</th>
        <th class="personType">First name</th>
        
        <th class="personType">Last name</th>
        <th class="personType">Role</th>
        <th class="personType">Type</th>
        <th class="personType">Household</th>
        <?php if(isset($this->showHours) and ((int)$this->showHours === 1)): ?>
            <th class="personType">Hours</th>
        <?php endif; ?> 

    </tr>
    <?php endif; ?>
    <?php
        $this->hasPersonTypeHeader = true;
    }
    
    function renderPersonSubTypeHeader($item, $force = false)
    {
        return;
        if(!$force)
            if(!$this->needPersonTypeHeader()) return;

        ?>
    <tr class="personType">
        <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
        <td><?php //echo CHtml::encode($item['Session']); ?></td>
        <td><?php //echo CHtml::encode($item['PersonTypeId']); ?></td>
        <td colspan="5"><?php echo "Participant Type: ".CHtml::encode($item['PersonType']); ?></td>
        <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
        <td><?php //echo CHtml::encode($item['FirstName']); ?></td>
        <td><?php //echo CHtml::encode($item['MiddleName']); ?></td>
        <td><?php //echo CHtml::encode($item['LastName']); ?></td>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th class="personType">Barcode</th>
        <th class="personType">First name</th>
        
        <th class="personType">Last name</th>
        <th class="personType">Person Type</th>
    </tr>
    <?php
        $this->hasPersonTypeHeader = true;
    }    

    function renderPersonRow($item)
    {
        if(!$this->isPersonRow()) return;

        if ($item['PersonID'] == $this->prevPersonId) 
            return;   

        ?>
        <tr class="person">
            <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
            <td><?php echo CHtml::encode($item['HouseholdName']); ?></td>
            <td><?php echo CHtml::encode($item['BarcodeID']); ?></td>
            <td><?php echo CHtml::encode($item['StudentName']); ?></td>

            <td><?php echo CHtml::encode($item['Role']); ?></td>
            <td><?php echo CHtml::encode($item['Age']); ?></td>
            <td><?php echo CHtml::encode($item['Telephone']); ?></td>
            <td><?php echo CHtml::encode($item['Email']); ?></td>
            <td><?php echo CHtml::encode($item['PhotoWaiver']); ?></td>
            <?php
               foreach ($this->sessions as $key => $value){
                        print "<td>".$this->personSessions[$item['PersonID']][$value['sessionID']]."</td>";
                }
            ?>    
            
            <td><?php echo CHtml::encode(round($this->personSessions[$item['PersonID']]['Attendance']/$this->sessionsCount*100)); ?></td>

            
            <td><?php if(isset($this->showHours) and ((int)$this->showHours === 1)): ?>
                <?php echo CHtml::encode(round($this->personSessions[$item['PersonID']]['sumHours']/60, 2));// sprintf("%d:%d", floor($this->personSessions[$item['PersonID']]['sumHours']/60), $this->personSessions[$item['PersonID']]['sumHours']%60)  ?>
            <?php endif; ?> </td>

            <?php // if POST['Hours'] <td><?php echo CHtml::encode($item['Hours']);</td> ?>

        </tr>
    <?php
    }

    function renderGrandTotals($item)
    {
        if(!$this->isGrandTotals()) return;
        ?>
    <!--<tr class="grandTotals footer">
        <td></td>
        <td colspan="3"><?php echo "Grand totals participants: "; ?></td>


        <td><?php echo $this->row["uniquePersons"]; ?></td>
        <td colspan="2" ><?php echo "Unique Households: "; ?></td>

        <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
    </tr>-->
    <?php
    }

    function renderProgramFooter($force = false)
    {
        if($this->hasProgramHeader and $this->isProgramFooter())
        {   $all = array();
            $this->renderSessionFooter(true);
          
        ?>
     <tr class="program-footer">
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td colspan="2" style="text-align: center">
               <?php echo "Number of Attendants: "; // ".CHtml::encode($this->prevProgram)."  <td colspan="2"><?php echo "Unique Households: ";  ?></td>
                
               <?php
                    foreach ($this->sessions as $key => $value){
                        print "<td>".CHtml::encode($this->uniquePersons[$value['sessionID']])."</td>";
                    }
               ?> 

               </td>

           
                <?php //echo $this->row["uniqueSessions"]; $this->uniqueSessionsCount = $this->row["uniqueSessions"];?></td>

    </tr>
    
    <tr class="program-footer">
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td colspan="2" style="text-align: center">
                   <?php echo "# of Families: "; ?>
               </td>
                   <?php print "<td>".CHtml::encode($this->uniqueHouseholds)."</td>"; ?> 
    </tr>
    <?php 
        foreach ($this->rolesList as $key => $value) {
            if ($this->uniqueRoles[$value] != 0) {
    ?>        
                <tr class="program-footer">
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td colspan="2" style="text-align: center">
                               <?php echo "# of ".$value."s: "; ?>
                           </td>
                               <?php print "<td>".CHtml::encode($this->uniqueRoles[$value])."</td>"; ?> 
                </tr>
    <?php
            } // if
        } // foreach
        
            $all['uniquePersons'] = $this->row["uniquePersons"];
            $all['uniqueHouseholds'] = $this->row["uniqueHouseholds"];
            $all['uniqueSessions'] = $this->row["uniqueSessions"];
            
            $this->hasProgramHeader = false;
        } 
    }

    function renderSessionFooter()
    {
        if($this->hasSessionHeader and $this->isSessionFooter())
        {
            $this->renderPersonTypeFooter();
            if (!$this->perfectAttendance || !$this->noMorePersonsRow) {
            ?>
        <tr> </tr>    
        <tr class="session footer">
            <td></td>
            <td colspan="3"><?php echo "Total: "; ?><?php echo $this->row["uniquePersons"]; ?></td>

            <td colspan="4" ><?php echo "Unique Households: "; ?><?php echo $this->row["uniqueHouseholds"]; ?></td>
        </tr>
        <?php }
            $this->hasSessionHeader = false;


            $currSessionPersons = array();
            foreach($this->collectPersonTypeRows as $row)
            {
                if($row["SessionId"] == $this->row["SessionId"])
                {
                    if(!isset($currSessionPersons[$row["PersonType"]]))
                    {
                        $currSessionPersons[$row["PersonType"]] = 0;
                    }
                    $currSessionPersons[$row["PersonType"]] += $row["uniquePersons"];
                }
            }
            
            if (!$this->perfectAttendance || !$this->noMorePersonsRow) {
            
                foreach($currSessionPersons as $index => $personType)
                {
                    ?>
                <tr class="session footer">
                    <td></td>
                    <td colspan="6"><?php echo CHtml::encode($index); ?>


                    <?php echo CHtml::encode($personType); ?>
                    <?php echo $personType["uniquePersons"]; ?></td>

                    <td ></td>
                </tr>
                <?php
                }
            }
            
        }
    }

    function renderPersonTypeFooter()
    {
        if($this->isPersonTypeFooter())
        {
            $this->collectPersonTypeRows[] = $this->row;
        }

        /*if($this->hasPersonTypeHeader and $this->isPersonTypeFooter())
        {
            ?>
            <tr class="personType footer">
                <td></td>
                <td></td>
                <td colspan="2"><?php echo CHtml::encode($this->prevPersonType).": "; ?></td>

                <td><?php echo $this->row["uniquePersons"]; ?></td>
                <td colspan="2" ><?php echo "Unique Households: "; ?></td>

                <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
            </tr>
            <?php
            $this->hasPersonTypeHeader = false;
        }*/
    }
    

    function countPrograms($item)
    {
        if($this->hasProgramHeader and $this->isProgramFooter())
        {   $all = array();
            $this->countSessions(true); 
            
            //print('Hello '.$this->row["uniquePersons"]);
            
            
            $all['uniquePersons'] = $item["uniquePersons"];
            $all['uniqueHouseholds'] = $item["uniqueHouseholds"];
            $all['uniqueSessions'] = $item["uniqueSessions"];
            
           
            $this->hasProgramHeader = false;
             return $all;
        } 
    }
    
    
   function countSessions()
    {
        if($this->hasSessionHeader and $this->isSessionFooter())
        {
            $this->countPersonTypes();
            ?>
        <?php
            $this->hasSessionHeader = false;           
        }
    }

    function countPersonTypes()
    {
        if($this->isPersonTypeFooter())
        {
            $this->collectPersonTypeRows[] = $this->row;
        }

        /*if($this->hasPersonTypeHeader and $this->isPersonTypeFooter())
        {
            ?>
            <tr class="personType footer">
                <td></td>
                <td></td>
                <td colspan="2"><?php echo CHtml::encode($this->prevPersonType).": "; ?></td>

                <td><?php echo $this->row["uniquePersons"]; ?></td>
                <td colspan="2" ><?php echo "Unique Households: "; ?></td>

                <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
            </tr>
            <?php
            $this->hasPersonTypeHeader = false;
        }*/
    }
    
}


 

$gp = new groupReport($model, $programId, $sessions, $schools, $sites, $fromDate, $toDate, $showHours, $viewSummary, $perfectAttendance);
$gp->render();

?>
