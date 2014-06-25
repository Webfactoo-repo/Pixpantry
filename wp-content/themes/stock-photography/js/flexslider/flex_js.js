jQuery(document).ready(function($){
	
	$i = 1;
	
	$(".flexslider").each(function(){
		
		// Get control nav
		$nav_menu = $(this).find("ul.thumbs-nav");
		
		// Add unique control nav class	
		new_menu = "thumbns-nav-"+$i;
		$nav_menu.addClass(new_menu);
		new_menu_item = "." + new_menu + " li";
		
		
		//	Homepage Slider
		$(this).flexslider({
			controlNav: true,
			directionNav: true,
			slideshow: false,
			manualControls: new_menu_item,
			prevText: "",
			nextText: "",
			start: function(){
		      $('.loader').hide();
		  }
		});
		$i++;
	});
});