$(document).ready(function(){
//Manage page
    //$('.managePage .single-left-tab').hide();
    //$('.managePage .single-left-tab:first-child').show();
    //$('.managePage .left-tabs li:first-child').addClass('active-img');
    $('.left-tabs li:first-child').addClass('first-left-tab');
    
    var smallImgM = document.createElement('img');
    smallImgM.setAttribute('src', 'images/left-part-active-tab.png');
    var liM = $('.left-tabs li.active');
    liM.append(smallImgM);
    
    $('.left-tabs li:last-child.active img').remove();

    /*$('.left-tabs li a').click(function(){ 
        $('.left-tabs li').removeClass('active');
        $(this).parent().addClass('active'); 
        //var currentTabM = $(this).attr('href'); 
        //$('.managePage .single-left-tab').hide();
        //$(currentTabM).show();
        
        var liM = $('.left-tabs li.active');
        liM.append(smallImgM);
        
        $('.left-tabs li:last-child.active img').remove();
        

    });*/
});