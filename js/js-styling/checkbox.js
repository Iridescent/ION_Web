jQuery(document).ready(function(){
	 
	jQuery(".niceCheck").mousedown(
	function() {
	 
	     changeCheck(jQuery(this));
	      
	});
	 
	 
	jQuery(".niceCheck").each(
	function() {
	      
	     changeCheckStart(jQuery(this));
	      
	});
	 
	                                        });
	 
	function changeCheck(el)
	{
	     var el = el,
	          input = el.find("input").eq(0);
	     if(!input.attr("checked")) {
	        el.css("background-position","0 0");  
	        el.addClass('selected-checkbox'); 
	        input.attr("checked", true)
	    } else {
	        el.css("background-position","0 -15px");
	        el.removeClass('selected-checkbox');    
	        input.attr("checked", false)
	    }
	     return true;
	}
	 
	function changeCheckStart(el)
	{
	var el = el,
	        input = el.find("input").eq(0);
	      if(input.attr("checked")) {
	        el.css("background-position","0 0");   
	        el.addClass('selected-checkbox');
	        }
	     return true;
	}