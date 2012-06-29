$(document).ready(function(){

//Reports page
    $('.reportsPage .single-left-tab').hide();
    $('.reportsPage .single-left-tab:first-child').show();
    $('.reportsPage .left-tabs li:first-child').addClass('active-img');
    $('.reportsPage .left-tabs li:first-child').addClass('first-left-tab');
    
    var smallImg = document.createElement('img');
    smallImg.setAttribute('src', 'images/left-part-active-tab.png');
    var li = $('.reportsPage .left-tabs li.active-img');
    li.append(smallImg);
    
    $('.reportsPage .left-tabs li:last-child.active-img img').remove();

    $('.reportsPage .left-tabs li a').click(function(){ 
        $('.reportsPage .left-tabs li').removeClass('active-img');
        $(this).parent().addClass('active-img');
        $(this).parent().append(smallImg);
        
        var currentTab = $(this).attr('href'); 
        $('.reportsPage .single-left-tab').hide();
        $(currentTab).show();
        
        if($(this).parent().last().hasClass('active-img')){
        	$('.reportsPage .left-tabs li:last-child.active-img img').remove();
        }
        //$('.reportsPage .left-tabs li:last.active-img img').remove();

    });   
    
});