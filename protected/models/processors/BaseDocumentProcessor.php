<?php

class BaseDocumentProcessor {
    protected function Load($path){
        $t = new PHPExcel();
        return PHPExcel_IOFactory::load($path);
    }
}

?>
