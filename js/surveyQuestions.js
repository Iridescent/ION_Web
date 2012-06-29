$(document).ready(function(){
    //type "Text" for question
    var questionTypeText = '<span class="short-textarea short-textarea-question-type">'
        + '<textarea readonly="readonly">Answered text</textarea>'
        + '</span>';

    //type "Multiple choice" for question
    var questionTypeMultipleChoice = '<ul class="multipleChoiceUl">'
        + '<li>'
        + '<input class="short-input-multiple-checkbox" type="radio" name="TypeMultipleChoice" />'
        + '<span class="short-input short-input-multiple"><input type="text" /></span>'
        + '</li></ul>';

    //type "Checkbox" for question
    var questionTypeCheckbox = '<ul class="checkboxUl">'
        + '<li>'
        + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeCheckbox" />'
        + '<span class="short-input short-input-multiple"><input type="text" /></span>'
        + '</li></ul>';

    //type "Select" for question
    var questionTypeSelectList = '<ul class="selectListUl">'
        + '<li>'
        + '<span class="short-input short-input-multiple"><input type="text" class="selectlist" /></span>'
        + '</li></ul>';

    //type "Scale" for question
    var questionTypeScale ='<div class="scaleQuestionType"></div>';
    var questionTypeScaleMin = '<span class="scaleMin">'
        + '<select class="selectMin">'
        + '<option>0</option>'
        + '<option>1</option>'
        + '</select>'
        + '</span>';
    var questionTypeScaleMax = '<span class="scaleMax">'
        + '<select class="selectMax">'
        + '<option>3</option>'
        + '<option>4</option>'
        + '<option>5</option>'
        + '<option>6</option>'
        + '<option>7</option>'
        + '<option>8</option>'
        + '<option>9</option>'
        + '<option>10</option>'
        + '</select>'
        + '</span><div class="clear"></div>';
    var questionTypeScaleMinLabel = '<label class="scaleMinLabelVal">0</label>'
        + '<span class="scaleMinLabel"><input type="text" /></span><div class="clear"></div>';
    var questionTypeScaleMaxLabel = '<label class="scaleMaxLabelVal">3</label>'
        + '<span class="scaleMaxLabel"><input type="text" /></span><div class="clear"></div>';

    //type "Grid" for question
    var questionTypeScaleMaxLabel = '';

    /*-- method for EDIT TYPE of question --*/
    function editQuestionSurveyViewMethod(){
        var surveyQuestion = this.parents('.surveyQuestion');
        $('.surveyQuestion').removeClass('editQuestionSurveyView');
        $(surveyQuestion).addClass('editQuestionSurveyView');
        $('.surveyQuestion .surveyControlButtons .editSurvey').removeClass('activeBttnSurvey');
        $(surveyQuestion).find('.editSurvey').addClass('activeBttnSurvey');
        $('#syrveyCustomForm .surveyBodyQuestions .surveyQuestion .surveyQuestionDone').parents('.surveyQuestionRow').hide();
        $(surveyQuestion).find('.surveyQuestionDone').parents('.surveyQuestionRow').show();
        $(surveyQuestion).find('.surveyQuestionDone').parents('.surveyQuestionRow').show();
        $(surveyQuestion).find('.surveyStaticTitleText').hide();
        $(surveyQuestion).find('.surveyStaticTitle').parent().show();
        $(surveyQuestion).find('.surveyQuestionRowType').show();
        $(this).parents('.surveyQuestion').find('.exampleQuestionAnswer').removeClass('width-400');
        if($(surveyQuestion).find('.another-answer').val() == 'Your own answer'){
            $(surveyQuestion).find('.multipleChoiceAddOther').hide();
            $(surveyQuestion).find('.multipleChoiceAddNewLine').hide();
        } else {
            $(surveyQuestion).find('.multipleChoiceAddOther').show();
            $(surveyQuestion).find('.multipleChoiceAddNewLine').show();
        }
        $(surveyQuestion).find('.removeMultipleChoiceLine').show();
        $(surveyQuestion).find('.short-input input:text:not(".surveyStaticTitle")').each(function(){
            if($(this).val() == 'Undefined option'){
                $(this).val('');
            }
        });
        $(surveyQuestion).find('.short-input input:text').removeAttr('readonly');

        //for SELECTLIST
        if($(surveyQuestion).find('.selectListUl')){
            $(surveyQuestion).find('.selectListUl').show();
            $(surveyQuestion).find('.selectListUISelect').parent().hide();
        }

        //for SCALE
        if($(surveyQuestion).find('.scaleQuestionType')){
            $(surveyQuestion).find('.scaleQuestionType').show();
            $(surveyQuestion).find('.wrapperRadioScale .radioScaleBttn').remove();
            $(surveyQuestion).find('.wrapperRadioScale .radioVal').remove();
            $(surveyQuestion).find('.wrapperRadioScale .radioScaleRow').remove();
            $(surveyQuestion).find('.wrapperRadioScale .firstMin').remove();
            $(surveyQuestion).find('.wrapperRadioScale .firstMax').remove();
        }
    }

    /*-- method for HIDE TYPE of question (STANDARD VIEW OF QUESTION) --*/
    function hideQuestionSurveyViewMethod (){
        var surveyQuestion = this.parents('.surveyQuestion');
        $(surveyQuestion).find('.editSurvey').removeClass('activeBttnSurvey');
        $(surveyQuestion).removeClass('editQuestionSurveyView');
        $(surveyQuestion).addClass('standartQuestionSurveyView');
        $(surveyQuestion).find('.multipleChoiceAddOther').hide();
        $(surveyQuestion).find('.multipleChoiceAddNewLine').hide();
        $(surveyQuestion).find('.removeMultipleChoiceLine').hide();
        $(surveyQuestion).find('.short-input input:text:not(".surveyStaticTitle")').each(function(){
            if($(this).val() == ''){
                $(this).val('Undefined option');
            }
        });
        $(surveyQuestion).find('.short-input input:text').attr('readonly','readonly');
        $(surveyQuestion).find('.surveyQuestionDone').parents('.surveyQuestionRow').hide();
        var title = $(surveyQuestion).find('.surveyStaticTitle:text');
        var titleVal = $(title).val();
        if(titleVal == ''){
            titleVal = 'Question without title';
        }
        $(title).parent().hide();
        $(surveyQuestion).find('.surveyStaticTitleText').text(titleVal);
        $(surveyQuestion).find('.surveyStaticTitleText').show();
        $(surveyQuestion).find('.surveyQuestionRowType').hide();
        $(surveyQuestion).find('.exampleQuestionAnswer').addClass('width-400');

        //for SELECTLIST
        if($(surveyQuestion).find('.selectListUl:visible').length > 0){
            $(surveyQuestion).find('.selectListUl').hide();
            if($(surveyQuestion).find('.selectListUISelect:visible').length < 1){
                var selectList = '<span class="surveyQuestionType"><select class="selectListUISelect"></select></span>';
                var tmpList = '';
                $(surveyQuestion).find('.selectListUl li').each(function(){
                    var selectListValue = $(this).find('input.selectlist').val();
                    var selectListOption = '<option value='+ selectListValue +'>' + selectListValue + '</option>';
                    tmpList += selectListOption;
                });
                $(surveyQuestion).find('.exampleQuestionAnswer').append(selectList);
                $(surveyQuestion).find('.selectListUISelect').append(tmpList);
            }
            else {
                $(surveyQuestion).find('.selectListUISelect').parent().hide();
            }
        }

        //for SCALE
        if($(surveyQuestion).find('.scaleQuestionType:visible').length > 0){
            $(surveyQuestion).find('.scaleQuestionType').hide();
            var wrapperRadioScale = '<div class="wrapperRadioScale"></div>';
            var minScaleVal = parseInt($(surveyQuestion).find('.scaleMinLabelVal').text());
            var maxScaleVal = parseInt($(surveyQuestion).find('.scaleMaxLabelVal').text());
            var labelScaleMin = $(surveyQuestion).find('.scaleMinLabel input').val();
            var labelScaleMax = $(surveyQuestion).find('.scaleMaxLabel input').val();

            var addRadioBttns = function(){
                for( var i = minScaleVal; i < maxScaleVal + 1; i++){
                    $(surveyQuestion).find('.exampleQuestionAnswer').append('<input class="radioScaleBttn  radiowp'+ i +'" type="radio" name="radio" />');
                    $(surveyQuestion).find('.exampleQuestionAnswer').append('<span class="radioVal radiowp'+ i +'">' + i + '</span>');
                }
                $(surveyQuestion).find('.exampleQuestionAnswer').wrapInner(wrapperRadioScale);
                $(surveyQuestion).find('.radioScaleBttn:first').before('<span class="firstMin">' + labelScaleMin + '</span>');
                $(surveyQuestion).find('.radioVal:last').after('<span class="firstMax">'+ labelScaleMax + '</span>');
                $(surveyQuestion).find('.radioScaleBttn').each(function(){
                    $(this).next('.radioVal').andSelf().wrapAll('<div class="radioScaleRow"/>');
                });
                $(surveyQuestion).find('.radioScaleRow:first').css({'margin-left' : '5px'});
                $(surveyQuestion).find('.radioScaleRow:last').css({'margin-right' : '5px'});
            }

            if ($(surveyQuestion).find('.radioScaleBttn:visible').length > 0){
                $(surveyQuestion).find('.radioScaleBttn').remove();
                $(surveyQuestion).find('.radioVal').remove();
                $(surveyQuestion).find('.radioScaleRow').remove();
                $(surveyQuestion).find('.firstMin').remove();
                $(surveyQuestion).find('.firstMax').remove();
                addRadioBttns();
            } else {
                addRadioBttns();
            }
        }

    }

    //START
    //function for parse chosen select option
    function surveyQuestionTypeSelection (){
        $(this).find('option:selected').each(function(){
            var thisClass = $(this).attr('class');
            var thisExampleQuestionAnswer = $(this).parents(".surveyQuestion").find('.exampleQuestionAnswer');

            /*-- function for type "multipleChoice" --*/
            function surveyQuestionParseMultipleChoice (){
                $(thisExampleQuestionAnswer).text('');
                $(thisExampleQuestionAnswer).append(questionTypeMultipleChoice);
                var bttnAddLi = '<a class="multipleChoiceAddNewLine" href="#">Add new line</a>';
                var bttnAddLiOther = '<a class="multipleChoiceAddOther" href="#">Add other</a>';
                var questionTypeMultipleChoiceLi = '<li class="multipleChoiceLi">'
                    + '<input class="short-input-multiple-checkbox" type="radio" name="TypeMultipleChoice" />'
                    + '<span class="short-input"><input type="text" /></span>'
                    + '<a href="#" class="removeMultipleChoiceLine"></a>'
                    + '</li>';
                var questionTypeMultipleChoiceAnotherLi = '<li class="multipleChoiceLi">'
                    + '<input class="short-input-multiple-checkbox" type="radio" name="TypeMultipleChoice" />'
                    + '<span class="short-input"><input type="text" class="another-answer" value="Your own answer" readonly="readonly" /></span>'
                    + '<a href="#" class="removeMultipleChoiceLine"></a>'
                    + '</li>';
                $(thisExampleQuestionAnswer).find('ul').after(bttnAddLi);
                $(thisExampleQuestionAnswer).find('ul').after(bttnAddLiOther);
                //click to add new row
                $('.multipleChoiceAddNewLine').click(function(){
                    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeMultipleChoiceLi);
                    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
                        $(this).parent().remove();
                        return false;
                    });
                    return false;
                });
                //click to add your own answer
                $('.multipleChoiceAddOther').click(function(){
                    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeMultipleChoiceAnotherLi);
                    $(this).hide();
                    $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').hide();
                    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
                        $(this).parents('.surveyQuestion').find('.multipleChoiceAddOther').show();
                        $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
                        $(this).parent().remove();
                        return false;
                    });
                    return false;
                });
            }

            /*-- function for type "checkBoxes"  --*/
            function surveyQuestionParseCheckboxes (){
                $(thisExampleQuestionAnswer).text('');
                $(thisExampleQuestionAnswer).append(questionTypeCheckbox);
                var bttnAddLi = '<a class="multipleChoiceAddNewLine" href="#">Add new line</a>';
                var bttnAddLiOther = '<a class="multipleChoiceAddOther" href="#">Add other</a>';
                var questionTypeCheckboxLi = '<li class="multipleChoiceLi">'
                    + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeCheckbox" />'
                    + '<span class="short-input"><input type="text" /></span>'
                    + '<a href="#" class="removeMultipleChoiceLine"></a>'
                    + '</li>';
                var questionTypeCheckboxAnotherLi = '<li class="multipleChoiceLi">'
                    + '<input class="short-input-multiple-checkbox" type="checkbox" name="TypeCheckbox" />'
                    + '<span class="short-input"><input type="text" class="another-answer" value="Your own answer" readonly="readonly" /></span>'
                    + '<a href="#" class="removeMultipleChoiceLine"></a>'
                    + '</li>';
                $(thisExampleQuestionAnswer).find('ul').after(bttnAddLi);
                $(thisExampleQuestionAnswer).find('ul').after(bttnAddLiOther);
                //click to add new row
                $('.multipleChoiceAddNewLine').click(function(){
                    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeCheckboxLi);
                    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
                        $(this).parent().remove();
                        return false;
                    });
                    return false;
                });
                //click to add your own answer
                $('.multipleChoiceAddOther').click(function(){
                    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeCheckboxAnotherLi);
                    $(this).hide();
                    $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').hide();
                    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
                        $(this).parents('.surveyQuestion').find('.multipleChoiceAddOther').show();
                        $(this).parents('.surveyQuestion').find('.multipleChoiceAddNewLine').show();
                        $(this).parent().remove();
                        return false;
                    });
                    return false;
                });
            }

            /*-- function for type "selectList" --*/
            function surveyQuestionParseSelectList (){
                $(thisExampleQuestionAnswer).text('');
                $(thisExampleQuestionAnswer).append(questionTypeSelectList);
                var bttnAddLi = '<a class="multipleChoiceAddNewLine" href="#">Add new line</a>';
                var questionTypeSelectListLi = '<li class="multipleChoiceLi">'
                    + '<span class="short-input short-input-multiple"><input type="text" class="selectlist" /></span>'
                    + '<a href="#" class="removeMultipleChoiceLine"></a>'
                    + '</li>';
                $(thisExampleQuestionAnswer).find('ul').after(bttnAddLi);
                //click to add new row
                $('.multipleChoiceAddNewLine').click(function(){
                    $(thisExampleQuestionAnswer).find('li:last-child').after(questionTypeSelectListLi);
                    $('.multipleChoiceLi .removeMultipleChoiceLine').click(function(){
                        $(this).parent().remove();
                        return false;
                    });
                    return false;
                });
            }

            /*-- function for type "text" --*/
            function surveyQuestionParseText (){
                $(thisExampleQuestionAnswer).text('');
                $(thisExampleQuestionAnswer).append(questionTypeText);
            }

            /*-- function for type "scale" --*/
            function surveyQuestionParseScale (){
                $(thisExampleQuestionAnswer).text('');
                $(thisExampleQuestionAnswer).append(questionTypeScaleMin);
                $(thisExampleQuestionAnswer).append(questionTypeScaleMax);
                $(thisExampleQuestionAnswer).append(questionTypeScaleMinLabel);
                $(thisExampleQuestionAnswer).append(questionTypeScaleMaxLabel);
                $(thisExampleQuestionAnswer).wrapInner(questionTypeScale);

                $('.scaleMin .selectMin').change(function(){
                    $('.scaleMinLabelVal').text($(this).val());
                });

                $('.scaleMax .selectMax').change(function(){
                    $('.scaleMaxLabelVal').text($(this).val());
                });
            }

            /*Using parsing functions*/
            //type -> TEXT
            if (thisClass == 'typeText'){
                surveyQuestionParseText();
            }
            //type -> multipleChoice
            else if (thisClass == 'multipleChoice'){
                editQuestionSurveyViewMethod.call($(this));
                surveyQuestionParseMultipleChoice();
            }
            //type -> checkBoxes
            else if (thisClass == 'checkBoxes'){
                editQuestionSurveyViewMethod.call($(this));
                surveyQuestionParseCheckboxes();
            //type select
            } else if (thisClass == 'selectList'){
                editQuestionSurveyViewMethod.call($(this));
                surveyQuestionParseSelectList();
                //type select
            } else if (thisClass == 'scale'){
                editQuestionSurveyViewMethod.call($(this));
                surveyQuestionParseScale();
            } else {
                $(thisExampleQuestionAnswer).text('');
            }
        });
    }
    //END

    //add functions to DOM
    $('#syrveyCustomForm .surveyBodyQuestions .surveyQuestion .surveyQuestionType select').each(surveyQuestionTypeSelection);
    $('#syrveyCustomForm .surveyBodyQuestions .surveyQuestion').each(function(){
        $(this).find('.surveyQuestionType select').change(surveyQuestionTypeSelection);
    });
    $('#syrveyCustomForm .surveyBodyQuestions .surveyQuestion .surveyQuestionDone').click(surveyQuestionSaveDOM);

    //add border style to ALL surveys
    $('.surveyBodyQuestions h2:first').addClass('survey-border-bottom');
    $('.surveyBodyQuestions .surveyQuestion').addClass('survey-border-bottom');

    /*--HANDLING BUTTONS in SURVEYS--*/

    //function for DONE bttn
    function surveyQuestionSaveDOM (){
        hideQuestionSurveyViewMethod.call($(this));
        return false;
    }

    //Handler for ADD bttn
    $('.surveyControlButtons .addSurvey').click(function(){
        var newQuestionSurvey = $('.surveyQuestion:last').clone(true);
        $(newQuestionSurvey).find('.surveyStaticTitleText').hide();
        $(newQuestionSurvey).find('.surveyStaticTitle').val('');
        $(newQuestionSurvey).find('.surveyStaticTitle').parent().show();
        $(newQuestionSurvey).find('.surveyQuestionRowType').show();
        $(newQuestionSurvey).find('.exampleQuestionAnswer').removeClass('width-400');
        $(newQuestionSurvey).insertAfter('.surveyQuestion:last');
        $(newQuestionSurvey).find('.surveyQuestionType select').each(surveyQuestionTypeSelection);

        hideQuestionSurveyViewMethod.call($(this));

        return false;
    });

    //Handler for EDIT bttn
    $('.surveyControlButtons .editSurvey').click(function(){
        editQuestionSurveyViewMethod.call($(this));
        return false;
    });

    //Handler for DUPLICATE bttn
    $('.surveyControlButtons .duplicateSurvey').click(function(){
        return false;
    });

    //Handler for DELETE bttn
    $('.surveyControlButtons .deleteSurvey').click(function(){
        var surveyLength = $('.surveyQuestion').length;
        if(surveyLength > 1){
            if (confirm("Are you really want to delete the question")) {
                $(this).parents(".surveyQuestion").remove();
            }
        }
        return false;
    });

});