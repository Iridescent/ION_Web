function TextAreaQuestion(id, title, required) {
    TextAreaQuestion.superClass.apply(this, [id, QuestionType.TEXT_AREA, title, required]);

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
}
TextAreaQuestion.inheritsFrom(BaseQuestion);