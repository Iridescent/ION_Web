<?php
    $cs=Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
?>

<div id="content">
<div class="filters border">
    <div class="row">
        <?php

        function unionArrays($arrFirst, $arrSecond)
        {
            $tempArray = array();
            foreach($arrFirst as $key=>$value)
            {
                $tempArray[$key] = $value;
            }
            foreach($arrSecond as $key=>$value)
            {
                $tempArray[$key] = $value;
            }
            return $tempArray;
        }
        
        /* Programs section */
        echo CHtml::Label('Programs', 'Programs'); 

        $programList = CHtml::listData(QueryProgram::model()->findAll(array('order' => 'Description')), 'ID', 'Description');
        $programList = unionArrays(array("-1"=>"<any>") , $programList);
        echo CHtml::dropDownList('programs', '<any>', $programList,
            array(
            'ajax' => array(
                'type'=>'POST', //request type
                'url'=>CController::createUrl('misc/SessionMultiSelect'), //url to call.
                'update'=>'#sessions', //selector to update
                'data'=>array('programs[program_id]'=> 'js:$(\'#programs\').val()', 'flag'=>TRUE), 
            ))                                    
         ); //textField($model,'Household');
        ?>
        <br/>
        
        <?php /* Sessions section - dependant */
            echo CHtml::Label('Sessions', 'Sessions'); 
            $sessionList = CHtml::listData(Session::model()->findAll(array('order' => 'Description')), 'ID', 'Description');
            echo CHtml::dropDownList('sessions', 'No Program is selected',  array()); //$sessionList); //textField($model,'Household');
        ?>
        
        <br/>
        
        <?php /* Schools section */ ?>
        <?php echo CHtml::Label('Schools', 'Schools'); ?>
        <?php $schoolList = CHtml::listData(School::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); ?>
        <?php echo CHtml::dropDownList('School', 'a 1 b 2', $schoolList); //textField($model,'Household'); ?>
        <input id="btnGenerateReport" type="button" value="Generate report" class="right">
    </div>
    <input id="btnAdvancedFilters" type="button" value="Advanced filters">
    <div class="row advanced">
        <?php echo CHtml::Label('Sites', 'Sites'); ?>
        <?php $sitesList = CHtml::listData(Sites::model()->findAll(array('order' => 'Name')), 'ID', 'Name'); ?>
        <?php echo CHtml::dropDownList('Sites', 'a 1 b 2', $sitesList); //textField($model,'Household'); ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){

            $("#btnAdvancedFilters").click(function () {
                $(".advanced").toggle();
            });

        });
    </script>

</div>
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
        private $row = null;
        private $key = null;

        private $hasProgramHeader = null;
        private $hasSessionHeader = null;
        private $hasPersonTypeHeader = null;

        public function __construct($model)
        {
            $this->model = $model;
        }

        public function render() // with totals
        {
            echo "<table>";
            foreach($this->model as $key => $row)
            {
                $this->key = $key;
                $this->row = $row;

                $this->renderProgramFooter();
                $this->renderSessionFooter();
                $this->renderPersonTypeFooter();

                $this->renderProgramHeader($row);
                $this->renderSessionHeader($row);
                $this->renderPersonTypeHeader($row);

                $this->renderPersonRow($row);

                $this->renderGrandTotals($row);

                $this->prevProgramId = $row["ProgramId"];
                $this->prevSessionId = $row["SessionId"];
                $this->prevPersonTypeId = $row["PersonTypeId"];

                $this->prevProgram = $row["Program"];
                $this->prevSession = $row["Session"];
                $this->prevPersonType = $row["PersonType"];
            }
            $this->renderProgramFooter(true);

            echo "</table>";
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
            if(!$this->needProgramHeader()) return;

            ?>
            <tr class="program">
                <td colspan="6"><?php echo " Program: ".CHtml::encode($item['Program']); ?></td>
                <td><?php //echo CHtml::encode($item['Session']); ?></td>
                <td><?php //echo CHtml::encode($item['PersonTypeId']); ?></td>
                <td><?php //echo CHtml::encode($item['PersonType']); ?></td>
                <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
                <td><?php //echo CHtml::encode($item['FirstName']); ?></td>
                <td><?php //echo CHtml::encode($item['MiddleName']); ?></td>
                <td><?php //echo CHtml::encode($item['LastName']); ?></td>
            </tr>
            <?php

            $this->hasProgramHeader = true;
            $this->renderSessionHeader($item, true);
        }

        function renderSessionHeader($item, $force = false)
        {
            if(!$force)
                if(!$this->needSessionHeader()) return;

            ?>
            <tr class="session">
                <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
                <td colspan="3"><?php echo "Session: ".CHtml::encode($item['Session']); ?></td>

                <td><?php //echo CHtml::encode($item['BarcodeID']); ?></td>
                <td><?php //echo CHtml::encode($item['FirstName']); ?></td>
                <td><?php //echo CHtml::encode($item['MiddleName']); ?></td>
                <td><?php //echo CHtml::encode($item['LastName']); ?></td>
            </tr>
            <?php

            $this->hasSessionHeader = true;
            $this->renderPersonTypeHeader($item, true);
        }

        function renderPersonTypeHeader($item, $force = false)
        {
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
                <th></th>
                <th class="personType">Barcode</th>
                <th class="personType">First name</th>
                <th class="personType">Middle name</th>
                <th class="personType">Last name</th>
            </tr>
            <?php
            $this->hasPersonTypeHeader = true;
        }

        function renderPersonRow($item)
        {
            if(!$this->isPersonRow()) return;
            ?>
            <tr class="person">
                <td><?php //echo $this->key.CHtml::encode($item['Program']); ?></td>
                <td><?php //echo CHtml::encode($item['Session']); ?></td>
                <td><?php //echo CHtml::encode($item['PersonTypeId']); ?></td>
                <td><?php //echo CHtml::encode($item['PersonType']); ?></td>
                <td><?php echo CHtml::encode($item['BarcodeID']); ?></td>
                <td><?php echo CHtml::encode($item['FirstName']); ?></td>
                <td><?php echo CHtml::encode($item['MiddleName']); ?></td>
                <td><?php echo CHtml::encode($item['LastName']); ?></td>
            </tr>
            <?php
        }

        function renderGrandTotals($item)
        {
            if(!$this->isGrandTotals()) return;
            ?>
                <tr class="grandTotals footer">
                    <td></td>
                    <td colspan="3"><?php echo "Grand totals participants: "; ?></td>


                    <td><?php echo $this->row["uniquePersons"]; ?></td>
                    <td colspan="2" ><?php echo "Unique Households: "; ?></td>

                    <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
                </tr>
            <?php
        }

        function renderProgramFooter($force = false)
        {
            if($this->hasProgramHeader and $this->isProgramFooter())
            {
                $this->renderSessionFooter(true);
                ?>
                <tr class="program footer">
                    <td></td>
                    <td colspan="3"><?php echo "Total Participants for Program ".CHtml::encode($this->prevProgram).": "; ?></td>


                    <td><?php echo $this->row["uniquePersons"]; ?></td>
                    <td colspan="2" ><?php echo "Unique Households: "; ?></td>

                    <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
                </tr>
                <?php
                $this->hasProgramHeader = false;
            }
        }

        function renderSessionFooter()
        {
            if($this->hasSessionHeader and $this->isSessionFooter())
            {
                $this->renderPersonTypeFooter();
                ?>
                <tr class="session footer">
                    <td></td>
                    <td colspan="3"><?php echo "Total Participants for Session: ".CHtml::encode($this->prevSession).": "; ?></td>


                    <td><?php echo $this->row["uniquePersons"]; ?></td>
                    <td colspan="2" ><?php echo "Unique Households: "; ?></td>

                    <td ><?php echo $this->row["uniqueHouseholds"]; ?></td>
                </tr>
                <?php
                $this->hasSessionHeader = false;
            }
        }

        function renderPersonTypeFooter()
        {
            if($this->hasPersonTypeHeader and $this->isPersonTypeFooter())
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
            }
        }
    }
    $gp = new groupReport($model);
    $gp->render();

    ?>
</div>