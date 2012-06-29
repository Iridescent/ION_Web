<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseManageController
 *
 * @author dshyshen
 */
class BaseManageController extends Controller {    
    public $layout='//layouts/layout_manage';
    
    public function firstLevelNavigationType() {
        return NavigationType::MANAGE;
    }
}

?>
