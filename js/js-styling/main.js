$(document).ready(function(){
	
	$('textarea').css({
		'-moz-user-select': 'text',
		'-khtml-user-select': 'text'
	});
	
	$('.error').parent().addClass('error-input-control');
	
	if($('.errorMessage').is(":contains('!')")){
		$('.errorMessage').parent().addClass('error-input-control-short');
	}
	
	$('#login-content').parent().removeClass('right-part-tabs').addClass('content-checkinPage');
	$('.error-page').parent().removeClass('right-part-tabs').addClass('content-checkinPage');
	
	$('#login-content').parent().css('overflow', 'hidden');
	$('#login-content').parent().parent().css('overflow', 'visible');
	
	$('.error-page').parent().css('overflow', 'hidden');
	$('.error-page').parent().parent().css('overflow', 'visible');
	
	$('#content-reporting #reportsheet:even').css('background','#ccc');
	
	//if($('body').has('#person-form') && $('body').has('.ui-datepicker-div')){
	//	$('.ui-datepicker-div').addClass('test');
	//}
	
    //$('#DomainProgram_ProgramType').selectbox_styled();
    //$('#User_Role').selectbox_styled();
    //$('#states').selectbox_styled();
    //$('#countries').selectbox_styled();
    //$('#sex-select').selectbox_styled();
    //$('#cities').selectbox_styled();
    //$('#Person_Household').selectbox_styled();
    //$('#Subtype').selectbox_styled();
    //$('#Person_School').selectbox_styled();
    //$('#Type').selectbox_styled();
    //$('#Person_GradeLevel').selectbox_styled();
    
    $('body').has('#Person_DateOfBirth').addClass('block-for-Person_DateOfBirth');
    
	$('.right-part-tabs').has('#household-form').addClass('styled-content-form');
    
    //$('#program').selectbox_styled();
    $('#PersonEditDialog').parent().addClass('width-755');
    
    $('#DomainProgram_StartDate').datepicker();
    $('#DomainProgram_EndDate').datepicker();
    $('#Person_DateOfBirth').datepicker({ changeYear: true, autoSize: true, yearRange: "1940:2012" });
    $('#StartDate').datepicker();
    $('#datepicker-left-reports-period').datepicker();
    $('#datepicker-right-reports-period').datepicker();
    
    $('#StartTime').timeEntry({'ampmPrefix':' '});
    
  	
  	  	
  	/*var imgAdd = document.createElement('img');
    imgAdd.setAttribute('src', 'images/add-icon.png');
    $("#addEntityButton span").before(imgAdd);
  	
  	var imgEdit = document.createElement('img');
    imgEdit.setAttribute('src', 'images/edit-icon.png');
    $("#editEntityButton span").before(imgEdit);
  	
  	var imgDelete = document.createElement('img');
    imgDelete.setAttribute('src', 'images/delete-icon.png');
    $("#deleteEntityButton span").before(imgDelete);*/
    
    

     	
  	
	
	/*$("table.grid tr").hover(
    function () {
        $(this).addClass("highlight");
    },
    function () {
        $(this).removeClass("highlight");
    });
	
	$('#delete-program').attr('disabled','disabled');
	$('#delete-program').addClass('disabled-bttn');
	
	$('#edit-program').attr('disabled','disabled');
	$('#edit-program').addClass('disabled-bttn');
	
	$('#delete-session').attr('disabled','disabled');
	$('#delete-session').addClass('disabled-bttn');
	
	$('#edit-session').attr('disabled','disabled');
	$('#edit-session').addClass('disabled-bttn');

    $('#delete-households').attr('disabled','disabled');
    $('#delete-households').addClass('disabled-bttn');

    $('#edit-households').attr('disabled','disabled');
    $('#edit-households').addClass('disabled-bttn');

    $('#add-households').removeAttr("disabled");
    $('#add-households').removeClass('disabled-bttn');

    $('#delete-household-persons').attr('disabled','disabled');
    $('#delete-household-persons').addClass('disabled-bttn');

    $('#edit-household-persons').attr('disabled','disabled');
    $('#edit-household-persons').addClass('disabled-bttn');

    //wrapper-grid-household-persons
	
	$("#popup-get-report table.grid tr").click(
	    function () {
	        $('#popup-get-report table.grid tr').removeClass("selected");
	        $(this).addClass("selected");
	    }
	);
	
	
	$("#wrapper-grid-manage-programs table.grid tr").click(
	    function () {
	        $('#wrapper-grid-manage-programs table.grid tr').removeClass("selected");
	        $(this).addClass("selected");
			
			if ($("#wrapper-grid-manage-programs table.grid tr").hasClass('selected')){
				$('#wrapper-grid-manage-programs .header-grid-content .controlls .add-edit-delete-bttn').addClass('active-bttn');
				
				$('#delete-program').removeAttr("disabled");
				$('#delete-program').removeClass('disabled-bttn');
				
				$('#edit-program').removeAttr("disabled");
				$('#edit-program').removeClass('disabled-bttn');
			}
	    }
	);

    $("#wrapper-grid-manage-households table.grid tr").click(
        function () {
            $('#wrapper-grid-manage-households table.grid tr').removeClass("selected");
            $(this).addClass("selected");

            if ($("#wrapper-grid-manage-households table.grid tr").hasClass('selected')){
                $('#wrapper-grid-manage-households .header-grid-content .controlls .add-edit-delete-bttn').addClass('active-bttn');

                $('#delete-households').removeAttr("disabled");
                $('#delete-households').removeClass('disabled-bttn');

                $('#edit-households').removeAttr("disabled");
                $('#edit-households').removeClass('disabled-bttn');

                $('#add-households').removeAttr("disabled");
                $('#add-households').removeClass('disabled-bttn');
            }
        }
    );
	
	//wrapper-grid-programs-sessions
	$("#wrapper-grid-programs-sessions table.grid tr").click(
	    function () {
	        $('#wrapper-grid-programs-sessions table.grid tr').removeClass("selected");
	        $(this).addClass("selected");
			
			if ($("#wrapper-grid-programs-sessions table.grid tr").hasClass('selected')){
				$('#wrapper-grid-programs-sessions .header-grid-content .controlls .add-edit-delete-bttn').addClass('active-bttn');
				
				$('#delete-session').removeAttr("disabled");
				$('#delete-session').removeClass('disabled-bttn');
				
				$('#edit-session').removeAttr("disabled");
				$('#edit-session').removeClass('disabled-bttn');
			}
	    }
	);
	*/
	
	
	//styled select
	//$('.sex-select').selectbox();
	/*$('.select-program-type').selectbox();
	$('.select-reports-school').selectbox();
	$('.select-reports-site').selectbox();
	$('.select-reports-program').selectbox();
	$('.select-reports-session').selectbox();
        $('.select-household-city').selectbox();
         $('.select-household-state').selectbox();
	*/
	//styled calendar
	/*$( "#datepicker-left-add-program-date" ).datepicker();
	$( "#datepicker-left-add-program-start" ).datepicker();
	$( "#datepicker-left-add-program-end" ).datepicker();
	$( "#datepicker-left-reports-period" ).datepicker();
	$( "#datepicker-right-reports-period" ).datepicker();
	*/
	
	/*$(".location-autocomplete").autocompleteArray([
			'alex',
			'alex1',
			'alex2',
			'alex3',
			'alex4',
			'alex5',
			'alex6',
			'alex7',
			'alex8',
			'alex9'
		],
		{
			delay:10,
			minChars:1,
			matchSubset:1,
			autoFill:true,
			maxItemsToShow:10
		}
	);
	
	$(".course-manager-autocomplete").autocompleteArray([
			'alex',
			'alex1',
			'alex2',
			'alex3',
			'alex4',
			'alex5',
			'alex6',
			'alex7',
			'alex8',
			'alex9'
		],
		{
			delay:10,
			minChars:1,
			matchSubset:1,
			autoFill:true,
			maxItemsToShow:10
		}
	);
	
	
	(function($){
	    $.fn.clearDefault = function(){
		    return this.each(function(){
			    var default_value = $(this).val();
			    $(this).focus(function(){
			    	if ($(this).val() == default_value) $(this).val("");
			    });
			    $(this).blur(function(){
			    	if ($(this).val() == "") $(this).val(default_value);
			    });
		    });
	    };
    })(jQuery);

    //clear input on FOCUS
    /*$('input.location-autocomplete').clearDefault();
    $('input.short-input-time').clearDefault();
    $('input.long-input-description').clearDefault();
    $('input.long-input-name').clearDefault();
    $('input.course-manager-autocomplete').clearDefault();
    $('input.long-input-name-programs-top').clearDefault();
    $('input.long-input-name-programs-top').clearDefault();
    */

});