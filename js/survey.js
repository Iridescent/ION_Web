var QuestionType = {TEXT_INPUT: 1, TEXT_AREA: 2, SINGLE_CHOICE: 3, MULTIPLE_CHOICE: 4, DROPDOWN: 5, SCALE: 6, GRID: 7};

function removeSingleLine (){
    var lineLength= $(this).parents('.multipleChoiceUl').children('li.multipleChoiceLi').length;
    if(lineLength > 1){
        $(this).parent().remove();
            if($(this).parent().find('.another-answer:visible').length < 1){
                $(this).parents('.surveyQuestion').find('.multipleChoiceAddOther').show();
                $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
            }
        return false;
    } else if (lineLength == 1) {
        if($(this).parent().find('.another-answer:visible').length < 1){
            $(this).parents('.surveyQuestion').find('.multipleChoiceAddOther').show();
            $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
        }
        return false;
    } else {
        return false;
    }
}

function addNewLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeMultipleChoiceLi = '<li class="multipleChoiceLi">'
        + '<input class="short-input-multiple-checkbox" type="radio" name="TypeSingleChoice" />'
        + '<span class="short-input"><input type="text" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeMultipleChoiceLi);
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(removeSingleLine);
    return false;
}

function addNewCheckboxLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeCheckboxLi = '<li class="multipleChoiceLi">'
        + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeSingleChoice" />'
        + '<span class="short-input"><input type="text" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeCheckboxLi);
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(removeSingleLine);
    return false;
}

function addNewSelectLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeSelectLi = '<li class="multipleChoiceLi">'
        + '<span class="short-input"><input type="text" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeSelectLi);
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(removeSingleLine);
    return false;
}

function addAnotherLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeMultipleChoiceAnotherLi = '<li class="multipleChoiceLi">'
        + '<input class="short-input-multiple-checkbox" type="radio" name="TypeMultipleChoice" />'
        + '<span class="short-input"><input type="text" class="another-answer" value="Your own answer" readonly="readonly" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeMultipleChoiceAnotherLi);
    $(this).hide();
    $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').hide();
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
        if($(this).parent().find('.another-answer:visible').length > 0){
            $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
            removeSingleLine();
        }
        return false;
    });
    return false;
}

function addAnotherCheckboxLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeCheckboxAnotherLi = '<li class="multipleChoiceLi">'
        + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeMultipleChoice" />'
        + '<span class="short-input"><input type="text" class="another-answer" value="Your own answer" readonly="readonly" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeCheckboxAnotherLi);
    $(this).hide();
    $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').hide();
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
        if($(this).parent().find('.another-answer:visible').length > 0){
            $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
            removeSingleLine();
        }
        return false;
    });
    return false;
}

function addAnotherSelectLine (){
    var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');
    var questionTypeSelectAnotherLi = '<li class="multipleChoiceLi">'
        + '<span class="short-input"><input type="text" class="another-answer" value="Your own answer" readonly="readonly" /></span>'
        + '<a href="#" class="removeMultipleChoiceLine"></a>'
        + '</li>';
    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeSelectAnotherLi);
    $(this).hide();
    $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').hide();
    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
        if($(this).parent().find('.another-answer:visible').length > 0){
            $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
            removeSingleLine();
        }
        return false;
    });
    return false;
}

function Survey() {
    
    this.questionsContainer = {};
    
    this.init = function(json) {
        this.questionsContainer = $('div#syrveyCustomForm .surveyBodyQuestions');
        this.Questions = JSON.parse(json);
        
        if (!this.Questions) {
            this.Questions = [];
        }
        
        this.Questions = [
            new TextInputQuestion(0, "Is it Text?", false),
            new SingleChoiceQuestion(1, 'SingleChoiceQuestion', false,['one','two']), 
            new MultipleChoiceQuestion(2, 'MultipleChoiceQuestion', false,['one','two']),
            new DropdownQuestion(3, 'DropBox', false, ['one', 'two', 'three'])
        ];
    }
    
    this.draw = function() {
        for (var i=0; i < this.Questions.length; i++) {
            this.questionsContainer.append(this.Questions[i].getPreviewEditHtml());
        }
    }

    this.editQuestion = function (id) {
        var question = this.getQuestion(id);
        if (question) {
            question.jQObject().replaceWith(question.getEditHtml());
        }
    }
    
    this.addQuestion = function() {
        var newId = 0;
        if (this.Questions.length > 0) {
            newId = this.Questions[this.Questions.length - 1].id;
        }
        var newQuestion = new TextInputQuestion(newId + 1, "Question Title", false);
        this.Questions.push(newQuestion);
        this.questionsContainer.append(newQuestion.getEditHtml());
    }
    
    this.removeQuestion = function(id) {
        var surveyLength = $('.surveyQuestion').length;
        if(surveyLength > 1){
            if (confirm("Are you really want to delete the question?")) {
                var idx = this.indexOf(id);
                if (idx > -1) {
                    this.Questions[idx].jQObject().remove();
                    this.Questions.splice(idx, 1);
                    this.reorderQuestions();
                }
            }
        }
    }
    
    this.saveQuestion = function (id) {
        var question = this.getQuestion(id);
        if (question) {
            question.flush();
        }
    }
    
    this.onTypeChanged = function (question) {
        //TODO replace in this.Questions appropriate question
    }

    this.reorderQuestions = function () {
        for (var i=0; i<this.Questions.length; i++) {
            this.Questions[i].id = i+1;
        }
    }
    
    this.indexOf = function(id) {
        var result = -1;
        for (var i=0; i<this.Questions.length; i++) {
            if (this.Questions[i].id == id) {
                result = i;
                break;
            }
        }
        return result;
    }
    
    this.getQuestion = function(id) {
        var result;
        var idx = this.indexOf(id)
        if (idx > -1) {
            result = this.Questions[idx];
        }
        return result;
    }
    
    this.getJSON = function() {
        return JSON.stringify(this.Questions);
    }
}

window.survey = new Survey();
