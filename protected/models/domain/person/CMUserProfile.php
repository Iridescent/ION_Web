<?php

class CMUserProfile extends BaseModel {
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return 'cmuserprofile';
    }
    
    public function rules() {
        return array(
            array('CMUserId, PersonId, ProfileUrl, ProfilePictureUrl, Points, Experiments, Badges', 'safe'),
        );
    }
}

?>
