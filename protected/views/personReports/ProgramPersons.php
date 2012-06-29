<?php

class groupReport
{
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

    private $noMorePersonsRow = false;
    private $sessionsCount = 0;
    
    private $collectPersonTypeRows = array();

    public function __construct($model, $programId, $sessions, $schools, $sites, $fromDate, $toDate, $showHours, $viewSummary, $perfectAttendance)
    { //print_r($model); die();
        $this->model = $model;
        $this->programId = $programId;     
        $this->showHours = $showHours;
        $this->viewSummary = $viewSummary;
        $this->perfectAttendance = $perfectAttendance;
    }

    
    public function render() // with totals
    
    {  
        if (count ($this->model) === 0) {
           print ('No data to display');
           return;
        }
		
		echo "<div class='clear'></div>";

        echo  "<div id='reportsheet' class='content-black'>";
        echo "<table>"; 
        
       $count = 0;
        foreach($this->model as $key => $row)
        {
            $this->key = $key;
            $this->row = $row;

            $this->renderProgramFooter();

            //if (!$this->noMorePersonsRow) {
                $this->renderSessionFooter();
            //}    
            $this->renderPersonTypeFooter();

            $this->renderProgramHeader($row);
           //  if (!$this->noMorePersonsRow) {
            $this->renderSessionHeader($row); // }
            $this->renderPersonTypeHeader($row);
            $this->renderPersonSubTypeHeader($row);
            
            if (!$this->viewSummary && (!$this->perfectAttendance || !$this->noMorePersonsRow)) {
                $this->renderPersonRow($row);
            }    
         //   $this->renderGrandTotals($row);

            $this->prevProgramId = $row["ProgramId"];
            $this->prevSessionId = $row["SessionId"];
            $this->prevPersonTypeId = $row["PersonTypeId"];

            $this->prevProgram = $row["Program"];
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
        return !is_null($this->row["PersonsID"]);
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

    function renderProgramHeader($item)
    {
        if(!$this->needProgramHeader()) return; //else print_r ($item); die();
      //  print ('Hello '.$this->uniqueSessionsCount);
        //echo 'hello '.$;
        
            $programData = array();
           // $programData = $this->renderProgramFooter();            
         //   print_r($programData); //die();
          //  $row = array_merge($row, $programData);
            
            print $programData['uniquePersons'];
            print $programData['uniqueHouseholds'];
            print $programData['uniqueSessions'];
        
        ?>

    <tr class="program header-big" align="center">
        <td colspan="8"><?php echo " Program: ".CHtml::encode($item['Program']); ?></td>
    </tr>
    <tr class="date">
        <td colspan="8">
            <?php echo Localization::ToClientDate($item['ProgramStartDate']) . ' - ' . Localization::ToClientDate($item['ProgramEndDate']) ?>
        </td>
    </tr>
    

    <?php
    
        
        $this->hasProgramHeader = true;
        $this->renderSessionHeader($item, true);
    }

    function renderSessionHeader($item, $force = false)
    {
        if(!$force)
            if(!$this->needSessionHeader()) return;
          
         if ($this->sessionsCount>0) {  
            $this->noMorePersonsRow = true;
         } else {   
            $this->sessionsCount += 1;
         }   
        ?>
    <tr class="session-header"><td></td></tr>
    <tr class="session-header first-row">
        <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
        <td colspan="3"><?php echo CHtml::encode($item['Session']); ?></td>
        <td colspan="3"><?php echo Localization::ToClientDate($item['SessionStart']); ?></td>
        <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
        <td><?php //echo CHtml::encode($item['FirstName']); ?></td>
        <td><?php //echo CHtml::encode($item['MiddleName']); ?></td>
        <td><?php //echo CHtml::encode($item['LastName']); ?></td>
    </tr>
    <tr class="session-header">
       <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td> 
       <td colspan="3"><?php echo "Location: "; ?></td>
       <td colspan="3"><?php echo CHtml::encode($item['SiteName']); ?></td>
    </tr>    
    
    <?php if(!$this->viewSummary && (!$this->perfectAttendance || !$this->noMorePersonsRow)): ?>
    <tr>
        <th></th>
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
        $this->uniqueSessions += 1;
        $this->hasSessionHeader = true;
        $this->renderPersonTypeHeader($item, true);
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
        ?>
        <tr class="person">
            <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
            <td><?php echo CHtml::encode($item['BarcodeID']); ?></td>
            <td><?php echo CHtml::encode($item['FirstName']); ?></td>

            <td><?php echo CHtml::encode($item['LastName']); ?></td>
            <td><?php echo CHtml::encode($item['PersonType']); ?></td>
            <td><?php echo CHtml::encode($item['PersonSubType']); ?></td>
            <td><?php echo CHtml::encode($item['HouseholdName']); ?></td>
            <td><?php if(isset($this->showHours) and ((int)$this->showHours === 1)): ?>
                <?php echo CHtml::encode($item['Hours']); ?>
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
          //  print_r($this->row); print('hello'); die();
        ?>
     <tr class="program-footer">
               <td></td>
                <td colspan="3"><?php echo "Total Participants: "; // ".CHtml::encode($this->prevProgram)." ?><?php echo $this->row["uniquePersons"]; ?></td>
                <td colspan="2"><?php echo "Unique Households: "; ?>

                <?php echo $this->row["uniqueHouseholds"]; ?></td>

                <td  colspan="2"><?php echo "Sessions: "; ?>
                <?php echo $this->row["uniqueSessions"]; $this->uniqueSessionsCount = $this->row["uniqueSessions"];?></td>

            </tr>
    <?php
            $all['uniquePersons'] = $this->row["uniquePersons"];
            $all['uniqueHouseholds'] = $this->row["uniqueHouseholds"];
            $all['uniqueSessions'] = $this->row["uniqueSessions"];
            
       //   print_r($all); print('bye'); //die();
            $this->hasProgramHeader = false;
           // return $all;
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

//print_r ($model);
 

$gp = new groupReport($model, $programId, $sessions, $schools, $sites, $fromDate, $toDate, $showHours, $viewSummary, $perfectAttendance);
$gp->render();

?>
