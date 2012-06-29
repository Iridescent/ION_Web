function GridQuestion(id, title, required) {
    GridQuestion.superClass.apply(this, [id, QuestionType.GRID, title, required]);
    
    this.getEditHtml = function() {
        //TODO implement
    };
    
    this.getPreviewEditHtml = function() {
        //TODO implement
    };
    
    this.getViewHtml = function() {
        //TODO implement
    };
    
    this.flush = function () {};
    
    this.variants = [];
}
GridQuestion.inheritsFrom(BaseQuestion);


