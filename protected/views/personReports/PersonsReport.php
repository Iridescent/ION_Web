<?php

class groupReport
{
    private $programsHeader = '';
    
    private $programs = array();

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
        
        $programs = $data["programs"];
        $attendences = $data["attendences"];
        $persons = $data["persons"];
        
        foreach($programs as $program)
        {
            $this->programsHeader .= "<th class='personType'>".Localization::ToClientDate($program['PrDescr'])."</th>";
        }
        
        $new_persons = array();
        
        foreach ($persons as $person){
            foreach ($programs as $program)
            {
                $person["program_".$program["ProgramId"]] = 0;
                foreach($attendences as $attendence){
                    if ($attendence["PersonID"]==$person["PersonID"] && $attendence["ProgramId"]==$program["ProgramId"]){
                         $person["program_".$program["ProgramId"]] += $attendence["SpendTimeSec"];
                    }
                }                
            }
            unset($person["PersonID"]);
            unset($person["PersontypeID"]);
            unset($person["BarcodeID"]);
            unset($person["HouseholdID"]);
            $new_persons[] = array_diff($person, array(""));
        }
        
        echo "<div class='clear'></div>";

        echo  "<div id='reportsheet' class='content-black'>";
        echo "<table>"; 

        if (count ($data) <= 1) {
           print ('No data to display');
           return;
        }
        $this->renderReportHeader($this->programsHeader);
        
        foreach($new_persons as $person)
            
        {?>
            <tr class="person">
                <?php foreach($person as $key => $value)
                {?>
                <td><?php 
                    if (substr($key, 0, 8)=="program_"){
                        $hours = floor($value/3600);
                        $minutes = floor(floor($value/60)-$hours*60);
                        $seconds = floor($value - $hours*3600 - $minutes*60);
                        echo CHtml::encode($hours)."h ".CHtml::encode($minutes)."m ".$seconds."s";
                    }else{
                        echo CHtml::encode($value); 
                    }
                    
                    ?></td>
                <?php
                }
                ?>
            </tr>
        <?php
        
        }
        
        //$this->renderPersonFooter(true);

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

    function renderReportHeader($header)
    {   ?>
    <tr>
        <th class="personType">Household Name</th>
        <th class="personType">Student Name</th>
        <th class="personType">Role</th>
             
       <?php 
            print $header;          
       ?>                 
    </tr>
    <?php
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
