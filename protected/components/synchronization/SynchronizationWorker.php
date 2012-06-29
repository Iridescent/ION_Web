<?php

class SynchronizationWorker {
    private static $instance;
    private static $is_running;
    private $job_id;
    private $job;
    private $cm_service_client;
    
    private function __construct() {
        self::$is_running = FALSE;
        $this->job_id = 1;
        $this->cm_service_client = new CuriosityMachineServiceClient(NULL, NULL, NULL);
    }
    
    public static function Instance() {
        if (!isset (self::$instance)) {
            self::$instance = new SynchronizationWorker();
        }
        return self::$instance;
    }
    
    public function Start(){
        if (self::$is_running) {
            return;
        }
        self::$is_running = TRUE;

        $this->job = Job::model()->findByPk($this->job_id);
        PersonSynchronizationResult::model()->deleteAll();
        $this->job->StartStop(TRUE);
        
        try {
            $person = $this->getNextPerson();
            while ($person != NULL) {
                $person_id = $person->ID;
                try {
                    $cm_user_profile = CMUserProfile::model()->findByPk($person_id);                
                    if($cm_user_profile) {
                        $related_info = $this->cm_service_client->GetCMUserById($cm_user_profile->CMUserId);
                        if ($related_info) {
                            $this->saveCMUserProfile($cm_user_profile, $related_info);
                            $this->saveSynchronizationResult($person_id, TRUE);
                        }
                        else {
                            $this->saveSynchronizationResult($person_id, FALSE, "Could not get Profile from Curiosity Machine");
                        }
                    }
                    else {
                        $cm_user_profile = new CMUserProfile();
                        $cm_user_profile->PersonId = $person_id;
                        $related_info = $this->cm_service_client->GetCMUserByInfo($person->FirstName, $person->LastName, $person->DateOfBirth);
                        if ($related_info) {
                            $this->saveCMUserProfile($cm_user_profile, $related_info);
                            $this->saveSynchronizationResult($person_id, TRUE);
                        }
                        else {
                            $related_info = $this->cm_service_client->SaveCMUser(NULL);//TODO pass filled user
                            if ($related_info) {
                                $this->saveCMUserProfile($cm_user_profile, $related_info);
                                $this->saveSynchronizationResult($person_id, TRUE);
                            }
                            else {
                                $this->saveSynchronizationResult($person_id, FALSE, "Could not save to Curiosity Machine");
                            }
                        }
                    }
                }
                catch (Exception $e) {//sync as many as possible
                    $this->saveSynchronizationResult($person_id, FALSE, "Exception occured: " . $e->getMessage());
                    Yii::log("Synchronization Failed. Participant ID: " . $person_id . ". Exception: " . $e->getMessage());
                }
                $person = $this->getNextPerson();
            }
        }
        catch (Exception $e) {
            $this->Stop();
        }
        $this->Stop(TRUE);
    }
    
    public function Stop($is_succeed = FALSE) {
        $this->job->StartStop(FALSE, $is_succeed);
        self::$is_running = FALSE;
        $this->job = NULL;
    }
    
    private function getNextPerson() {
        return QueryPerson::model()->GetNextForSynchronization();
    }
    
    private function saveCMUserProfile(&$cm_user_profile, &$related_info) {
        $cm_user_profile->CMUserId = $related_info->user_id;
        $cm_user_profile->ProfileUrl = $related_info->user_profile_link;
        $cm_user_profile->ProfilePictureUrl = $related_info->user_profile_picture_link;
        $cm_user_profile->Points = $related_info->user_points;
        $cm_user_profile->Experiments = CommonHelper::JoinWithVBar($related_info->user_experiments);
        $cm_user_profile->Badges = CommonHelper::JoinWithVBar($related_info->user_badges);
        $cm_user_profile->save();
    }
    
    private function saveSynchronizationResult($person_id, $is_succeed, $details = NULL) {
        $result = new PersonSynchronizationResult();
        $result->PersonId = $person_id;
        $result->IsSucceed = CommonHelper::BoolToInt($is_succeed);
        $result->Details = $details;
        $result->save();
    }
}

?>
