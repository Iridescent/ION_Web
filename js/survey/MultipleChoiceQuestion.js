function MultipleChoiceQuestion(id, title, required, listOfItems) {
    MultipleChoiceQuestion.superClass.apply(this, [id, QuestionType.MULTIPLE_CHOICE, title, required]);
    
    this.getEditHtml = function() {
        var questionTypeMultipleChoiceTitle = '<label>Question title</label>';
        var questionTypeMultipleChoiceTitleValue = '<span class="short-input"><input type="text" class="surveyStaticTitle" value="' 
            + this.title 
            + '"></span>';
        var questionTypeMultipleChoiceTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeMultipleChoiceTitle 
            + questionTypeMultipleChoiceTitleValue 
            + '</div><div class="clear" />'; 
        
        var questionTypeMultipleChoiceDropdownTitle = '<label>Question type</label>';
        var questionTypeMultipleChoiceDropdownTitleValue = this.getQuestionTypeDropdown();
        var questionTypeMultipleChoiceDropdownRow = '<div class="surveyQuestionRow surveyQuestionRowType">' 
            + questionTypeMultipleChoiceDropdownTitle 
            + questionTypeMultipleChoiceDropdownTitleValue 
            + '</div><div class="clear"></div>';
        
        var questionTypeMultipleChoiceAnswer = '<label>Example of answer</label>';
        var addNewLine = '<a href="#" onclick="addNewCheckboxLine.call($(this)); return false;" class="multipleChoiceAddNewLine">Add new line</a>';
        var addOtherLine = '<a href="#" onclick="addAnotherCheckboxLine.call($(this)); return false;" class="multipleChoiceAddOther">Add other</a>';
        var questionTypeMultipleChoiceAnswerValue = '<div class="exampleQuestionAnswer">' 
            + this.questionTypeMultipleChoiceAnswerValueEdit() 
            + addNewLine 
            + addOtherLine 
            + '</div>';
        var questionTypeMultipleChoiceAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeMultipleChoiceAnswer 
            + questionTypeMultipleChoiceAnswerValue 
            + '</div><div class="clear"></div>';
        
        var questionTypeMultipleChoiceResult = this.beginWrapperHtml('surveyQuestionMultipleChoiceEditHtml')
            + this.getTopActionButtons(true)
            + questionTypeMultipleChoiceTitleRow
            + questionTypeMultipleChoiceDropdownRow
            + questionTypeMultipleChoiceAnswerRow
            + this.getBottomActionButtons()
            + '</div>';

        return questionTypeMultipleChoiceResult;
    };
    
    this.getPreviewEditHtml = function() {
        var questionTypeMultipleChoiceTitle = '<label>Question title</label>';
        var questionTypeMultipleChoiceTitleValue = '<div style="" class="surveyStaticTitleText">' 
            + this.title 
            + '</div>';
        var questionTypeMultipleChoiceTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeMultipleChoiceTitle 
            + questionTypeMultipleChoiceTitleValue 
            + '</div><div class="clear" />';
        
        var questionTypeMultipleChoiceAnswer = '<label>Example of answer</label>';
        var questionTypeMultipleChoiceAnswerValue = '<div class="exampleQuestionAnswer">' 
            + this.questionTypeMultipleChoiceAnswerValuePreview() 
            + '</div>';
        var questionTypeMultipleChoiceAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeMultipleChoiceAnswer 
            + questionTypeMultipleChoiceAnswerValue
            + '</div><div class="clear"></div>';
        
        var questionTypeMultipleChoiceResult = this.beginWrapperHtml('surveyQuestionSingleChoicePreviewHtml')
            + this.getTopActionButtons()
            + questionTypeMultipleChoiceTitleRow
            + questionTypeMultipleChoiceAnswerRow
            + '</div>';
        return questionTypeMultipleChoiceResult;
    };
    
    this.getViewHtml = function() {
        //TODO implement
    };
    
    this.questionTypeMultipleChoiceAnswerValuePreview = function (){
        var answerValues = this.variants;
        var answerLenfth = answerValues.length;
        var answerItems = '';
        for (var i=0; i<answerLenfth; i++){
            var answerItem = '<li>'
            + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeCheckbox" />'
            + '<span class="short-input short-input-multiple"><input type="text" readonly="readonly" value='+ answerValues[i] +' /></span>'
            + '</li>';
            answerItems += answerItem;
        }
        var answerResult = '<ul class="multipleChoiceUl">' + answerItems + '</ul><div class="clear"></div>';
        return answerResult;
    };
    
    this.questionTypeMultipleChoiceAnswerValueEdit = function (){
        var answerValues = this.variants;
        var answerLenfth = answerValues.length;
        var answerItems = '';
        for (var i=0; i<answerLenfth; i++){
            var answerItem = '<li class="multipleChoiceLi">'
            + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeCheckbox" />'
            + '<span class="short-input short-input-multiple"><input type="text" value='+ answerValues[i] +' /></span>'
            + '<a href="#" onclick="removeSingleLine.call($(this)); return false;" class="removeMultipleChoiceLine"></a>'
            + '</li>';
            answerItems += answerItem;
        }
        var answerResult = '<ul class="multipleChoiceUl">' + answerItems + '</ul><div class="clear"></div>';
        return answerResult;
    };
    
    this.flush = function () {};
    
    this.variants = listOfItems;
}
MultipleChoiceQuestion.inheritsFrom(BaseQuestion);