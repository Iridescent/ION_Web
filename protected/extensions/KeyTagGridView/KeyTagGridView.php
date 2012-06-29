<?php

/**
 * Description of KeyTagGridView
 *
 * Extends base Grid functionality with Title and action Buttons Add, Edit, Delete
 * 
 * @author dshyshen
 */

Yii::import('zii.widgets.grid.CGridView');
Yii::import('application.extensions.KeyTagGridView.KeyTagDataColumn');
Yii::import('application.extensions.KeyTagGridView.KeyTagLinkPager');

class KeyTagGridView extends CGridView{
    
    /* Grid Title */
    public $title="";
    
    /* Grid Title */
    public $titleCssClass="gridTitle";
    
    /* Button set */
    public $actionButtons=array("import", "add", "edit", "delete");
    
    /* Add button text */
    public $addButtonText="Add";
    
    /* Edit button text */
    public $editButtonText="Edit";
    
    /* Delete button text */
    public $deleteButtonText="Delete";
    
    /*Import button text */
    public $importButtonText = "Import";
    
    /* Call action when Add button click */
    public $addActionUrl="";
    
    /* Call action when Edit button click */
    public $editActionUrl="";
    
    /* Call action when Delete button click */
    public $deleteActionUrl="";
    
    /* If specified it is used as Add button handler */
    public $addActionHandler="";
    
    /* If specified it is used as Edit button handler */
    public $editActionHandler="";
    
    /*Import button text */
    public $importActionHandler="";
    
    /* Add button visibility */
    public $addButtonVisible=true;
    
    /* Edit button visibility */
    public $editButtonVisible=true;
    
    /* Delete button visibility */
    public $deleteButtonVisible=true;
    
    /*Import button text */
    public $importButtonVisisble=false;
    
    /* Action Method: POST or GET */
    public $actionMethod="POST";
    
    /* Name of Id parameter which will be send to edit/delete actions */
    public $idParameterName="entityId";
    
    /* Confirmation on delete */
    public $deleteConfirmation = "Are you sure you want to Delete?";
    
    /* Name of Id for add entity */
    public $addFormId = "addEntityForm";
    
     /* Name of Id for edit entity */
    public $editFormId = "editEntityForm";
    
     /* Name of Id for delete entity */
    public $deleteFormId = "deleteEntityForm";
    
        /* Name of Id for hidden input to add */
    public $addHiddenFormId = "addEntityIdHidden";
    
     /* Name of Id for hidden input to edit */
    public $editHiddenFormId = "editEntityIdHidden";
    
     /* Name of Id for hidden input to delete */
    public $deleteHiddenFormId = "deleteEntityIdHidden";
    
    /* Delete javascript for some exception*/
    public $deleteButtonSpecJavascript = false;

    public function init(){
        
        $this->selectionChanged = "function (id) { keyTagGridView.selectionChanged(id); }";
        $this->htmlOptions['class']='grid-view grid-styled-wrapper';
        $this->pager = 'KeyTagLinkPager';
        
        $result = array();
        foreach($this->columns as $column){
            
            if(is_string($column)){
                array_push($result, array('name'=>$column, 'class'=>'KeyTagDataColumn'));
            }
            else {
                if (!isset($column['class'])){
                    $column['class'] = 'KeyTagDataColumn';
                }
                array_push($result, $column);
            }
        }
        $this->columns = $result;
        
        parent::init();
    }
    
    public function renderItems(){
        echo "<div class=\"{$this->titleCssClass}\">";
        
        echo "<h2>";
        echo $this->title;
        echo "</h2>";
        
        echo "<div class=\"controlls\">";
        foreach($this->actionButtons as $button){
            if ($this->buttonVisible($button)){
                echo "<button class='add-edit-delete-bttn' id='" .$this->getButtonId($button).
                        "' onclick='" .$this->getButtonClickHandlerName($button). "'>".
                        "<img alt='' src=" . $this->getImageSrc($button) . " />".
                        "<span>".$this->getButtonText($button)."</span>"."</button>";
            }
        }        
        echo "</div>";
        
        //form Add
        echo "<form id='".$this->addFormId."' style='display: none;' action='" .$this->addActionUrl . "' method='" . $this->actionMethod . "' >";
        echo "</form>";
        
        //form Edit
        echo "<form id='".$this->editFormId."' style='display: none;' action='" .$this->editActionUrl . "' method='" . $this->actionMethod . "' >";
        echo "<input type='hidden' id='".$this->editHiddenFormId."' name='" . $this->idParameterName . "' />";
        echo "</form>";
        
        //form Edit
        echo "<form id='".$this->deleteFormId."' style='display: none;' action='" .$this->deleteActionUrl . "' method='" . $this->actionMethod . "' >";
        echo "<input type='hidden' id='".$this->deleteHiddenFormId."' name='" . $this->idParameterName . "' />";
        echo "</form>";
        
        echo "</div>";
         
        parent::renderItems();
    }
    
    public function registerClientScript(){        
        $actionButtonsScript = "var keyTagGridView = new KeyTagGridView('".$this->addFormId."', '".$this->editFormId."',".
                               " '".$this->deleteFormId."', '".$this->editHiddenFormId."', '".$this->deleteHiddenFormId."', '" .$this->deleteConfirmation. "');";
        $cs=Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/KeyTagGridView.js');
        $cs->registerScript("ActionButtonsScript", $actionButtonsScript, CClientScript::POS_HEAD);
        parent::registerClientScript();
    }
    
    private function getButtonText($key){
        switch ($key){
            case "add":{
                return $this->addButtonText;
            }
            
            case "edit":{
                return $this->editButtonText;
            }
            
            case "delete":{
                return $this->deleteButtonText;
            }
            
            case "import":{
                return $this->importButtonText;
            }
        }
    }
    
    private function getButtonId($key){
        switch ($key){
            case "add":{
                return "addEntityButton";
            }
            
            case "edit":{
                return "editEntityButton";
            }
            
            case "delete":{
                return "deleteEntityButton";
            }
            
            case "import":{
                return "importButton";
            }
        }
    }
 
    private function buttonVisible($key){
        switch ($key){
            case "add":{
                return $this->addButtonVisible;
            }
            
            case "edit":{
                return $this->editButtonVisible;
            }
            
            case "delete":{
                return $this->deleteButtonVisible;
            }
            
            case "import":{
                return $this->importButtonVisisble;
            }
        }
    }
    
    private function getButtonClickHandlerName($key){
        switch ($key){
            case "add":{
                return $this->addActionHandler != "" ? $this->addActionHandler : "keyTagGridView.addEntity()";
            }
            
            case "edit":{
                return $this->addActionHandler != "" ? $this->editActionHandler : "keyTagGridView.editEntity()";
            }
            
            case "delete":{
                return "keyTagGridView.deleteEntity(\"".$this->deleteHiddenFormId."\",\"".$this->deleteFormId."\")";
            }
            
            case "import":{
                return $this->importActionHandler != "" ? $this->importActionHandler : "";
            }
        }
    }
    
    private function getImageSrc($key){
        switch ($key){
            case "add":{
                return "images/add-icon.png";
            }
            
            case "edit":{
                return "images/edit-icon.png";
            }
            
            case "delete":{
                return "images/delete-icon.png";
            }
            
            case "import":{
                return "images/import-icon.png";
            }
        }
        return "";
    }
    
    /**
    * Renders the empty message when there is no data.
    */
    public function renderEmptyText()
    {
        $emptyText=$this->emptyText===null ? Yii::t('zii','No results found.') : $this->emptyText;
        echo CHtml::tag('span', array('class'=>'empty'), $emptyText);
    }
}

?>
