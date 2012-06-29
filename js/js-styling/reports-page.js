$(document).ready(function(){
	$('.open-close-block span').click(function(){
		$('.open-close-block span').toggle();
		$('.toggle-reports-advanced-content').toggle();
	});


    heightPopUp('#popup-get-report');
	verticalAlign('#popup-get-report');
	
	$('#bttn-get-report').click(function (){
		$('.wrapper-for-popup').css({
        	'display': 'block'
        });
            
		$('#popup-get-report').css({
			'display': 'block'
		});
		
		$('#popup-get-report .close-me, #popup-get-report .close-me-bttn').click(function(){
        	$('.wrapper-for-popup').css('display', 'none');
        	$('#popup-get-report').css('display', 'none');
        });
		
	});

});