<?php

class SessionOutcomeService {
    
    const PersonIdentityStore = 'PersonIdentity';
   
    /* Connect to server
     * Returns: If Person is alredy checked in - infromation about Person
     */
    public function Connect() {
        $result = $this->getServiceReuslt();
        $result->Person = $this->getPersonIdentity();
        return $result;
    }
    
    /* Check in Person by Barcode Id
     * Returns: current Person information
     */
    public function CheckIn($barcode_id) {
        $result = $this->getServiceReuslt();
        $result->Person = $this->getPersonIdentity();
        if (!$result->Person) {
            if ($barcode_id) {
                $person = Person::model()->GetByBarcodeID($barcode_id);
                if ($person) {
                    $person_identity = new stdClass();
                    $person_identity->FirstName = $person->FirstName;
                    $person_identity->LastName = $person->LastName;
                    $this->setPersonIdentity($person_identity);
                    $result->Person = $person_identity;
                }
                else {
                    $this->setError($result, 'Barcode ID does not exist');
                }
            }
            else {
                $this->setError($result, 'Barcode ID is not specified');
            }
        }
        return $result;
    }
    
    /* Check out current Person */
    public function CheckOut() {
        $this->setPersonIdentity(null);
    }
    
    /* Returns: List of available Projects in form: { SessionName, ProjectId } */
    public function GetAvalableProjectList() {
        
    }
    
    /* Returns: Project in form: {} */
    public function GetProject($project_id) {
        
    }
    
    /* Saves project
     * Project in form: { VideoFileId, ImageFileId, Text }
     */
    public function SaveProject($project) {
        
    }
    
    /* Save uploaded file to data base
     * Returns: fileId
     */
    public function SaveFile($file) {
        
    }
    
    /* Returns: List of available Surveys in form: { SurveyName, SurveyReplyId } */
    public function GetAvailableSurveyList () {
        
    }
    
    /* Returns: Survey in form: {} */
    public function GetSurvey($survey_id) {
        
    }
    
    /* Saves Survey
     * Survey in form: {  }
     */
    public function SaveSurvey($survey) {
        
    }
    
    private function getPersonIdentity() {
        return $_SESSION[self::PersonIdentityStore];
    }
    
    private function setPersonIdentity($person_identity) {
        $_SESSION[self::PersonIdentityStore] = $person_identity;
    }
    
    private function getServiceReuslt() {
        $result->Succeed = TRUE;
        $result->Error = '';
        return $result;
    }
    
    private function setError(&$result, $message) {
        $result->Succeed = FALSE;
        $result->Error = $message;
    }
}

?>
