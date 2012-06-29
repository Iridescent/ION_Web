function DropdownQuestion(id, title, required, listOfItems) {
    DropdownQuestion.superClass.apply(this, [id, QuestionType.DROPDOWN, title, required]);
    
    this.getEditHtml = function() {
        var questionTypeDropdownTitle = '<label>Question title</label>';
        var questionTypeDropdownTitleValue = '<span class="short-input"><input type="text" class="surveyStaticTitle" value="' 
            + this.title 
            + '"></span>';
        var questionTypeDropdownTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeDropdownTitle 
            + questionTypeDropdownTitleValue 
            + '</div><div class="clear" />'; 
        
        var questionTypeDropdownListTitle = '<label>Question type</label>';
        var questionTypeDropdownListTitleValue = this.getQuestionTypeDropdown();
        var questionTypeDropdownListRow = '<div class="surveyQuestionRow surveyQuestionRowType">' 
            + questionTypeDropdownListTitle 
            + questionTypeDropdownListTitleValue 
            + '</div><div class="clear"></div>';
        
        var questionTypeDropdownAnswer = '<label>Example of answer</label>';
        var addNewLine = '<a href="#" onclick="addNewSelectLine.call($(this)); return false;" class="multipleChoiceAddNewLine">Add new line</a>';
        var questionTypeDropdownAnswerValue = '<div class="exampleQuestionAnswer">' 
            + this.questionTypeDropdownAnswerValueEdit() 
            + addNewLine
            + '</div>';
        var questionTypeDropdownAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeDropdownAnswer 
            + questionTypeDropdownAnswerValue 
            + '</div><div class="clear"></div>';
        
        var questionTypeMultipleChoiceResult = this.beginWrapperHtml('surveyQuestionDropboxEditHtml')
            + this.getTopActionButtons(true)
            + questionTypeDropdownTitleRow
            + questionTypeDropdownListRow
            + questionTypeDropdownAnswerRow
            + this.getBottomActionButtons()
            + '</div>';

        return questionTypeMultipleChoiceResult;
    };
    
    this.getPreviewEditHtml = function() {
        var questionTypeDropdownTitle = '<label>Question title</label>';
        var questionTypeDropdownTitleValue = '<div style="" class="surveyStaticTitleText">' 
            + this.title 
            + '</div>';
        var questionTypeDropdownTitleRow = '<div class="surveyQuestionRow">' 
            + questionTypeDropdownTitle 
            + questionTypeDropdownTitleValue 
            + '</div><div class="clear" />';
        
        var questionTypeDropdownAnswer = '<label>Example of answer</label>';
        var questionTypeDropdownAnswerValue = '<div class="exampleQuestionAnswer">' 
            + this.questionTypeDropdownAnswerValuePreview()  
            + '</div>';
        var questionTypeDropdownAnswerRow = '<div class="row surveyQuestionRow">' 
            + questionTypeDropdownAnswer 
            + questionTypeDropdownAnswerValue
            + '</div><div class="clear"></div>';
        
        var questionTypeDropdownResult = this.beginWrapperHtml('surveyQuestionDropboxPreviewHtml')
            + this.getTopActionButtons()
            + questionTypeDropdownTitleRow
            + questionTypeDropdownAnswerRow
            + '</div>';
        return questionTypeDropdownResult;
    };
    
    this.getViewHtml = function() {
        //TODO implement
    };
    
    this.questionTypeDropdownAnswerValuePreview = function (){
        var answerValues = this.variants;
        var answerLenfth = answerValues.length;
        var answerItems = '';
        for (var i=0; i<answerLenfth; i++){
            var answerItem = '<option value=' + answerValues[i] + '>' + answerValues[i] + '</option>';;
            answerItems += answerItem;
        }
        var answerResult = '<span class="surveyQuestionType"><select class="selectListUISelect">' 
            + answerItems 
            + '</select></span><div class="clear"></div>';
        return answerResult;
    };
    
    this.questionTypeDropdownAnswerValueEdit = function (){
        var answerValues = this.variants;
        var answerLenfth = answerValues.length;
        var answerItems = '';
        for (var i=0; i<answerLenfth; i++){
            var answerItem = '<li class="multipleChoiceLi">'
            + '<span class="short-input short-input-multiple"><input type="text" class="selectlist" value='+ answerValues[i] +' /></span>'
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
DropdownQuestion.inheritsFrom(BaseQuestion);
