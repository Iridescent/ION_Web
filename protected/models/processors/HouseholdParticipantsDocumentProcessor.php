<?php

class HouseholdParticipantsDocumentProcessor extends BaseDocumentProcessor {
    
    private $locationCache;
    private $schoolCache;
    private $genderList;
    private $personTypeList;
    private $personSubTypeList;
    private $gradeLevelList;
    
    public function __construct(){
        $this->locationCache = new LocationCache();
        $this->schoolCache = new SchoolCache();
        $this->genderList = new DictionaryEntityList($this->getGenderCollection());
        $this->personTypeList = new DictionaryEntityList($this->getDictionaryEntityCollection(PersonType::model()->findAll()));
        $this->personSubTypeList = new DictionaryEntityList($this->getDictionaryEntityCollection(Personsubtype::model()->findAll()));
        $this->gradeLevelList = new DictionaryEntityList($this->getDictionaryEntityCollection(GradeLevel::model()->findAll(), 'Grade'));
    }
    
    public function Process($path){
        $result->succeed = true;
        $result->errorDetails = '';
        $result->sheets = array();
        $objPHPExcel = null;
                
        try {
            $objPHPExcel = $this->Load($path);
        }
        catch(Exception $e) {
            $result->succeed = false;
            $result->errorDetails = 'Error while loading file';
        }
        
        if ($result->succeed){
            $sheetCount = $objPHPExcel->getSheetCount();

            if($sheetCount > 0){
                $sheet = $objPHPExcel->setActiveSheetIndex(0);
                if ($sheet->getSheetState() == 'visible') {
                    $result->sheets[] = $this->processHouseholdSheet($sheet);
                }

                for ($i=1; $i<$sheetCount; $i++){
                    $sheet = $objPHPExcel->setActiveSheetIndex($i);
                    if ($sheet->getSheetState() == 'visible') {
                        $title = strtolower(trim($sheet->getTitle()));
                        $ids = QueryHousehold::model()->getIdByName($title);
                        $result->sheets[] = $this->processPersonSheet($sheet, count($ids) == 1 ? $ids[0]->ID : -1);
                    }
                }
            }
            else{
                $result->succeed = false;
                $result->errorDetails = 'File is empty';
            }
        }
        
        return $result;
    }
    
    private function processHouseholdSheet(&$sheet){
        $rows = $sheet->toArray();
        $toSave = array();
        $errors = array();
        $rowsCount = count($rows);
        
        $result->title = $sheet->getTitle();
        $result->totalRead = 0;
        $result->totalSaved = 0;
        $result->totalValid = 0;
        $result->errors = array();

        //Parse
        $validator = new HouseholdValidator();
        
        if ($validator->ValidateHeader($rows[0])){
            for ($i=2; $i<$rowsCount; $i++){
                $row = $rows[$i];
                if (!$validator->IsEmptyRow($row)){
                    $err = $validator->Validate($row, $i+1);
                    if (count($err) == 0){
                        $id = $this->locationCache->GetLocationId($row[1], $row[2], $row[3]);
                        if ($id < 1){
                            $errors[] = new ParseRowErrorInfo($i+1, 'Location', ParseResult::InvalidValue, '');
                        }
                        else{
                            $toSave[] = array($row[0], $id, $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
                                $row[10], $row[11], $row[12], $row[13],
                                date(Localization::SERVER_DATETIME_FORMAT), Yii::app()->user->id);
                        }
                    }
                    else{
                        $errors = array_merge($errors, $err);
                    }
                }
            }

            $result->totalRead = $rowsCount - 2;
            $result->totalValid = count($toSave);
            $result->errors = $errors;

            //Save
            $db = Yii::app()->db;
            $bulkLoader = new HouseholdBulkLoader($db->connectionString, $db->username, $db->password);
            $loadResult = $bulkLoader->Save($toSave);
            if ($loadResult->succeed){
                $result->totalSaved = $loadResult->totalSaved;
            }
            else{
                $result->errors[] = $loadResult->error;
            }
        }
        else {
            $result->errors[] = new ParseGenericError('Invalid header');
        }
        
        return $result;
    }
    
    private function processPersonSheet(&$sheet, $householdId = null){
        $rows = $sheet->toArray();
        $toSave = array();
        $errors = array();
        $rowsCount = count($rows);
        
        $result->title = $sheet->getTitle();
        $result->totalRead = 0;
        $result->totalSaved = 0;
        $result->totalValid = 0;
        $result->errors = array();
        
        if ($householdId && $householdId != -1){
            //Parse
            $validator = new PersonValidator();
            if ($validator->ValidateHeader($rows[0])){
                for ($i=2; $i<$rowsCount; $i++){
                    $row = $rows[$i];
                    if (!$validator->IsEmptyRow($row)){
                        $err = $validator->Validate($row, $i+1);
                        if (count($err) == 0){
                            $genderId = $this->genderList->GetIdByName($row[4]);
                            $personTypeId = $this->personTypeList->GetIdByName($row[5]);
                            $personSubTypeId = $this->personSubTypeList->GetIdByName($row[6]);
                            $schoolId = $this->schoolCache->GetSchoolId($row[11]);
                            $gradeLevelId = $this->personSubTypeList->GetIdByName($row[12]);

                            if ($genderId == -1){
                                $errors[] = new ParseRowErrorInfo($i+1, 'Gender', ParseResult::InvalidValue, $row[5]);
                            }
                            else if ($personTypeId < 1){
                                $errors[] = new ParseRowErrorInfo($i+1, 'Role', ParseResult::InvalidValue, $row[6]);
                            }
                            else {
                                $toSave[] = array($row[0], $row[1], $row[2], $householdId, $row[3], $genderId, $personTypeId, 
                                                  $this->getNullableInt($personSubTypeId), $row[7], $row[8], $row[9],
                                                  $row[10], $this->getNullableInt($schoolId), $this->getNullableInt($gradeLevelId), $row[13], $row[14],
                                                  $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], date(Localization::SERVER_DATETIME_FORMAT),
                                                  Yii::app()->user->id);
                            }
                        }
                        else{
                            $errors = array_merge($errors, $err);
                        }
                    }
                }

                $result->totalRead = $rowsCount - 2;
                $result->totalValid = count($toSave);
                $result->errors = $errors;

                //Save
                $db = Yii::app()->db;
                $bulkLoader = new PersonBulkLoader($db->connectionString, $db->username, $db->password);
                $loadResult = $bulkLoader->Save($toSave);
                if ($loadResult->succeed){
                    $result->totalSaved = $loadResult->totalSaved;
                }
                else{
                    $result->errors[] = $loadResult->error;
                }
            }
            else {
                $result->errors[] = new ParseGenericError('Invalid header');
            }
        }
        else {
            $result->errors[] = new ParseGenericError('Household "' . $result->title . '" does not exists');
        }
        
        return $result;
    }
    
    private function getDictionaryEntityCollection($rows, $nameField = 'Name'){
        $result = array();
        foreach ($rows as $row){
            $item = null;
            $item->Id = $row->ID;
            $item->Name = $row->{$nameField};
            $result[] = $item;
        }
        return $result;
    }
    
    private function getGenderCollection(){
        $result = array();
        $result[] = (object)array("Id"=>"F", "Name"=>"F");
        $result[] = (object)array("Id"=>"M", "Name"=>"M");
        return $result;
    }
    
    private function getNullableInt($id){
        return $id > 0 ? $id : null;
    }
}

?>
