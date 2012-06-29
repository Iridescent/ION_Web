<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProgramType
 *
 * @author dshyshen
 */
class ProgramType extends CActiveRecord {
    
    public static function model($classname=__CLASS__){
        return parent::model($classname);
    }
    
    public function tableName(){
        return 'programtype';
    }
    
    public function attributeLabels(){
        return array(
            'ID' => 'Id',
            'Name' => 'Name',
        );
    }
}

?>
