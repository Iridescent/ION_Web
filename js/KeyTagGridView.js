function KeyTagGridView(addEntityFormId, editEntityFormId, deleteEntityFormId, editEntityHiddenId, deleteEntityHiddenId, deleteConfirmation) {
    this.selectedEntityId = [];
    
    this.addEntityFormId = addEntityFormId;
    this.editEntityFormId = editEntityFormId;
    this.deleteEntityFormId = deleteEntityFormId;
    this.editEntityHiddenId = editEntityHiddenId;
    this.deleteEntityHiddenId = deleteEntityHiddenId;
    this.deleteConfirmation = deleteConfirmation;
       
    this.addEntity = function(){
        $('#'+this.addEntityFormId).submit();
    }
    
    this.editEntity = function(){
        if(this.selectedEntityId.length > 0){
            $('#'+this.editEntityHiddenId).val(this.selectedEntityId);
            $('#'+this.editEntityFormId).submit(); 
        }
    }
    
    this.deleteEntity = function(hiddenId, formId){
        if(this.selectedEntityId.length > 0){
            if (confirm(this.deleteConfirmation)){
                $('#'+hiddenId).val(this.selectedEntityId);
                $('#'+formId).submit(); 
            }
        }
    }
    
    this.selectionChanged = function(id){
        this.selectedEntityId = $.fn.yiiGridView.getSelection(id);
    }
}


