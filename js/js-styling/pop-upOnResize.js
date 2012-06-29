$(window).resize(function() {
	

    bodyHeightResize = $('body').height();
    bodyWidthResize = $('body').width();

    heightPopUp('#popup-get-report');
    heightPopUp('#popup-add-program-first');
    heightPopUp('#popup-add-program-description');
    heightPopUp('#popup-add-household-first');
    
    verticalAlign('#popup-add-program-first');
	verticalAlign('#popup-add-program-description');
	verticalAlign('#popup-get-report');
    verticalAlign('#popup-add-household-first');
    
    $('.wrapper-for-popup').css({
    	'height': bodyHeightResize,
        'width': bodyWidthResize
    });
	
});