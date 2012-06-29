function verticalAlign (block){
	var bodyHeight = $('body').height();
    var bodyWidth = $('body').width();
	var blockHeight = $(block).height();
	var blockWidth = $(block).width();
	
	toTop = (bodyHeight - blockHeight)/2 - 25;
	toLeft = (bodyWidth - blockWidth)/2;
	$(block).css({
		'position': 'absolute',
		'top': toTop,
		'left':toLeft
	});
	
}

function heightPopUp(block){
    var bHeight = $('body').height();
    var blocksHeight = $(block).height() + 100;
    var blocksWidth = $(block).width() + 17;
    if(bHeight < blocksHeight){
        $(block).css({
            'height': bHeight - 200,
            'width': blocksWidth,
            'overflow-y': 'scroll'
        });
    }
}


$(document).ready(function () {
	
	var bodyHeight = $('body').height();
    var bodyWidth = $('body').width();
    
    $('.wrapper-for-popup').css({
        'height': bodyHeight,
        'width': bodyWidth
    });
	
    $('#popup-add-program-description').css('display', 'none');
    $('.wrapper-for-popup').css('display', 'none');
    $('.ui-datepicker').css('display', 'none');
    $('#popup-add-program-first').css('display','none');


    heightPopUp('#popup-add-program-first');
    heightPopUp('#popup-add-program-description');
    heightPopUp('#popup-add-household-first');

	verticalAlign('#popup-add-program-first');
	verticalAlign('#popup-add-program-description');
    verticalAlign('#popup-add-household-first');
    
    
     $('#edit-program, #add-program').click(function(){
     	
            $('.wrapper-for-popup').css({
                'display': 'block'
            });
            $('#popup-add-program-first').css({
            	'display': 'block'
           	});

            $('#popup-add-program-first .close-me, #popup-add-program-first .close-me-bttn').click(function(){
                $('.wrapper-for-popup').css('display', 'none');
                $('#popup-add-program-first').css('display', 'none');
            });
        
     });

    $('#add-households, #edit-households').click(function(){

        $('.wrapper-for-popup').css({
            'display': 'block'
        });
        $('#popup-add-household-first').css({
            'display': 'block'
        });

        $('#popup-add-household-first .close-me, #popup-add-household-first .close-me-bttn').click(function(){
            $('.wrapper-for-popup').css('display', 'none');
            $('#popup-add-household-first').css('display', 'none');
        });

    });
    
    
    $('#add-session, #edit-session').click(function(){
            $('.wrapper-for-popup').css({
                'display': 'block',
                'height': bodyHeight,
                'width': bodyWidth
            });
            $('#popup-add-program-first').css({
            	'display': 'none'
           	});
            $('#popup-add-program-description').css({
            	'display': 'block'
           	});

            $('#popup-add-program-description .close-me, #popup-add-program-description .close-me-bttn').click(function(){
                $('.wrapper-for-popup').css('display', 'block');
                $('#popup-add-program-first').css({
	            	'display': 'block'
	           	});
                $('#popup-add-program-description').css('display', 'none');
            });
            
    });
    
    
});
