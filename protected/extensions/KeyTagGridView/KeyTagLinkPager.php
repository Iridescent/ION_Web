<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KeyTagLinkPager
 *
 * @author dshyshen
 */
//Yii::import('web.widgets.pagers.CLinkPager');

class KeyTagLinkPager extends CLinkPager {
    public function init() {
        if($this->nextPageLabel===null)
                $this->nextPageLabel=Yii::t('yii','');
        if($this->prevPageLabel===null)
                $this->prevPageLabel=Yii::t('yii','');
        if($this->firstPageLabel===null)
                $this->firstPageLabel=Yii::t('yii','First');
        if($this->lastPageLabel===null)
                $this->lastPageLabel=Yii::t('yii','Last');
        if($this->header===null)
                $this->header=Yii::t('yii','<h5>Go to page: </h5>');

        if(!isset($this->htmlOptions['id']))
                $this->htmlOptions['id']=$this->getId();
        if(!isset($this->htmlOptions['class']))
                $this->htmlOptions['class']='yiiPager pagination-styled';
    }
    
    public function run() {
        $this->registerClientScript();
        $buttons=$this->createPageButtons();
        if(empty($buttons))
                return;
        echo $this->header;
        echo CHtml::tag('div',$this->htmlOptions,implode("\n",$buttons));
        echo $this->footer;
    }
    
    protected function createPageButton($label,$page,$class,$hidden,$selected){
        if($hidden || $selected)
                $class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
        return CHtml::link($label,$this->createPageUrl($page), array ('class'=>$class));
    }
}

?>
