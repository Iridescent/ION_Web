<?php

class AmfServerController extends Controller {
    public function accessRules(){
        return array(
            array('allow', 'users'=>array('*')),
            );
    }
    
    public function actionIndex() {
        $server = new Zend_Amf_Server();
        $server->setProduction(false);//on production change to true
        $server->setClass("KeyTagService");
        $server->setClass("SessionOutcomeService");
        $handle = $server->handle();
        echo $handle;
    }
}

?>
