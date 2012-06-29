function TextInputQuestion(id, title, required) {
    TextInputQuestion.superClass.apply(this, [id, QuestionType.TEXT_INPUT, title, required]);
    
    this.getEditHtml = function() {
        var questionTypeTextTitle = '<label>Question title</label>';
        var questionTypeTextTitleValue = '<span class="short-input"><input type="text" class="surveyStaticTitle" value="' 
            + this.title 
            + '"></span>';
        var questionTypeTextTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeTextTitle 
            + questionTypeTextTitleValue 
            + '</div><div class="clear" />';

        var questionTypeTextDropdownTitle = '<label>Question type</label>';
        var questionTypeTextDropdownTitleValue = this.getQuestionTypeDropdown();
        var questionTypeTextDropdownRow = '<div class="surveyQuestionRow surveyQuestionRowType">' 
            + questionTypeTextDropdownTitle 
            + questionTypeTextDropdownTitleValue 
            + '</div><div class="clear"></div>';

        var questionTypeTextAnswer = '<label>Example of answer</label>';
        var questionTypeTextAnswerValue = '<span class="short-textarea short-textarea-question-type">' 
            + '<textarea readonly="readonly">Answered text</textarea></span>';
        var questionTypeTextAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeTextAnswer 
            + questionTypeTextAnswerValue 
            + '</div><div class="clear"></div>';

        var questionTypeTextResult = this.beginWrapperHtml('surveyQuestionTextEditHtml')
            + this.getTopActionButtons(true)
            + questionTypeTextTitleRow
            + questionTypeTextDropdownRow
            + questionTypeTextAnswerRow
            + this.getBottomActionButtons()
            + '</div>';
        return questionTypeTextResult;
    };
    
    this.getPreviewEditHtml = function() {
        var questionTypeTextTitle = '<label>Question title</label>';
        var questionTypeTextTitleValue = '<div style="" class="surveyStaticTitleText">' + this.title + '</div>';
        var questionTypeTextTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeTextTitle 
            + questionTypeTextTitleValue 
            + '</div><div class="clear" />';

        var questionTypeTextAnswer = '<label>Example of answer</label>';
        var questionTypeTextAnswerValue = '<div class="exampleQuestionAnswer width-400">' 
            + '<span class="short-textarea short-textarea-question-type">'
            + '<textarea readonly="readonly">Answered text</textarea>'
            + '</span></div>';
        var questionTypeTextAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeTextAnswer 
            + questionTypeTextAnswerValue 
            + '</div><div class="clear"></div>';

        var questionTypeTextResult = this.beginWrapperHtml('surveyQuestionTextPreviewHtml')
            + this.getTopActionButtons()
            + questionTypeTextTitleRow
            + questionTypeTextAnswerRow
            + '</div>';
        return questionTypeTextResult;
    };
    
    this.getViewHtml = function() {
        //TODO implement
    };
    
    this.flush = function () {
        
    };
}
TextInputQuestion.inheritsFrom(BaseQuestion);