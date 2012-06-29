function ScaleQuestion(id, title, required) {
    ScaleQuestion.superClass.apply(this, [id, QuestionType.SCALE, title, required]);
    
    this.getEditHtml = function() {
        //TODO implement
    };
    
    this.getPreviewEditHtml = function() {
        var questionTypeScaleTitle = '<label>Question title</label>';
        var questionTypeScaleTitleValue = '<div style="" class="surveyStaticTitleText">' 
            + this.title 
            + '</div>';
        var questionTypeScaleTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeScaleTitle 
            + questionTypeScaleTitleValue 
            + '</div><div class="clear" />';
        
        var questionTypeScaleResult = this.beginWrapperHtml('surveyQuestionDropboxPreviewHtml')
            + this.getTopActionButtons()
            + questionTypeScaleTitleRow
            + '</div>';
        return questionTypeScaleResult;
    };
    
    this.getViewHtml = function() {
        //TODO implement
    };
    
    this.flush = function () {};
    
    this.variants = [];
}
ScaleQuestion.inheritsFrom(BaseQuestion);