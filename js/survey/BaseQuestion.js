function BaseQuestion(id, type, title, required) {
    this.id = id;
    this.type = type;
    this.title = title;
    this.required = required;
    
    this.getEditHtml = function() {}
    this.getPreviewEditHtml = function() {}
    this.getViewHtml = function() {}
    this.flush = function() {}
    
    this.DOMId = function() {
        return "question_" + this.id;
    }
    
    this.jQObject = function() {
        return $('#'+this.DOMId());
    }
    
    this.beginWrapperHtml = function (classes) {
        return '<div id="' + this.DOMId() + '" class="surveyQuestion ' + classes + '">';
    }
    
    this.getQuestionTypeDropdown = function() {
        var result = '<span class="surveyQuestionType"><select>'
            + '<option ' + this.getSelected(QuestionType.TEXT_INPUT) 
                + ' value="' + QuestionType.TEXT_INPUT + '" class="typeText">Text</option>'
            //+ '<option value="' + QuestionType.TEXT_AREA + '" class="typeText">Paragraphed Text</option>'
            + '<option ' + this.getSelected(QuestionType.SINGLE_CHOICE) 
                + ' value="' + QuestionType.SINGLE_CHOICE + '" class="multipleChoice">Single Choice</option>'
            + '<option ' + this.getSelected(QuestionType.MULTIPLE_CHOICE) 
                + ' value="' + QuestionType.MULTIPLE_CHOICE + '" class="checkBoxes">Multiple Choice</option>'
            + '<option ' + this.getSelected(QuestionType.DROPDOWN)
                + ' value="'+ QuestionType.DROPDOWN + '" class="selectList">Select List</option>'
            + '<option ' + this.getSelected(QuestionType.SCALE)
                + ' value="' + QuestionType.SCALE + '" class="scale">Scale</option>'
            //+ '<option value="' + QuestionType.GRID + '" class="typeText">Grid</option>'
            + '</select></span><div class="clear"></div>';
        return result;
    }
    
    this.getTopActionButtons = function(direction) {
        var result = '<div class="surveyControlButtons">'
            //+ '<a title="Add new question" href="#" class="addSurvey"></a>'
            + '<a title="Edit this question" href="javascript:window.survey.editQuestion(' + this.id + ');"'
                + ' class="editSurvey' + (direction ? ' activeBttnSurvey' : '') + '"></a>'
            //+ '<a title="Duplicate this question" href="#" class="duplicateSurvey"></a>'
            + '<a title="Delete this question" href="javascript:window.survey.removeQuestion(' + this.id + ');" class="deleteSurvey"></a>'
            + '</div>';
        return result;
    }
    
    this.getBottomActionButtons = function() {
        var result = '<div class="row surveyQuestionRow">'
            + '<label>&nbsp;</label>'
            + '<a href="javascript:window.survey.saveQuestion(' + this.id + ');" class="surveyQuestionDone">Done</a>'
            + '<input type="checkbox" class="surveyQuestionRequiredCheckbox">'
            + '<label class="label-for-checkbox surveyQuestionRequiredLabel">Make this question required</label>'
            + '</div><div class="clear"></div>';
        return result;
    }

    this.getSelected = function(type) {
        return type == this.type ? 'selected="selected"' : '';
    }
}