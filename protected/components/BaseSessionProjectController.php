<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseTrackingController
 *
 * @author avasyl
 */
class BaseSessionProjectController extends Controller {
   
    public $layout='//layouts/layout_tracking';
    
    public function firstLevelNavigationType() {
        return NavigationType::SESSION_PROJECT;
    }
}

?>
