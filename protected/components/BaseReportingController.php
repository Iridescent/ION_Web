<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseReportingController
 *
 * @author dshyshen
 */
class BaseReportingController extends Controller {    
    public $layout='//layouts/layout_reporting';
    
    public function firstLevelNavigationType() {
        return NavigationType::REPORTING;
    }
}

?>
