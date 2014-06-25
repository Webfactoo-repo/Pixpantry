jQuery(document).ready(function($){

	$('#masthead .smas-form').append('<div id="smas-advanced" style="display:none;"></div>');
	$('<a href="#" id="advanced-search-toggle">options <span class="advanced-search-toggle-icon genericon genericon-downarrow"></span></a>').appendTo('#masthead #smas_keywords');
	$('#masthead .smas-button-container').appendTo('#smas_keywords');

	$('#masthead #smas_price_range, #masthead #smas_image_orientation, #masthead #smas_color_picker, #masthead #smas_collections, #masthead #smas_licenses, #masthead #smas_aspect_ratio').appendTo('#smas-advanced');

	$('#masthead .smas-container .smas-button-container input[type="submit"]').attr('value', 'Search');
	$('#header-smas').show();
	$("#advanced-search-toggle").toggle(
	  function () {
		$('#smas-advanced').toggle();
		$('.advanced-search-toggle-icon').removeClass('genericon-downarrow');
		$('.advanced-search-toggle-icon').addClass('genericon-uparrow');
	  },
	  function () {
		$('#smas-advanced').toggle();
		$('.advanced-search-toggle-icon').removeClass('genericon-uparrow');
		$('.advanced-search-toggle-icon').addClass('genericon-downarrow');
	  }
	);
	// Set header max height
	var header_height = $('#header-inner').outerHeight();
	var menu_height = $('#main-nav-wrapper').outerHeight();
	var top_navigation = $('.top-navigation').outerHeight();
	var header_height_total = header_height + menu_height + top_navigation;

	$('.not-home .site-header').css('max-height', header_height_total + 'px');
	$('.not-home').show();
	$(window).resize(function(){

		var header_height = $('#header-inner').outerHeight();
		var menu_height = $('#main-nav-wrapper').outerHeight();
		var top_navigation = $('.top-navigation').outerHeight();
		var header_height_total = header_height + menu_height + top_navigation;
		$('.not-home .site-header').css('max-height', header_height_total + 'px');
		$('.not-home').show();
	});

	var $archive_entry = jQuery(".archive .hentry, .blog-grid .hentry, .hentry");

    if (!($archive_entry.length == 0)) {
		$archive_entry.each(function (index, domEle) {
		// domEle == this
		if ((index+1)%3 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
		});
	}

	var $author_grid = jQuery(".team-page-grid .author-wrap, #homewidgets .widget");

    if (!($author_grid.length == 0)) {
		$author_grid.each(function (index, domEle) {
		// domEle == this
		if ((index+1)%3 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
		});
	}

	// Like Icons
    if($('.like-count').length) {
    	$('.like-count').live('click',function() {
    		var id = $(this).attr('id');
    		id = id.split('like-');
    		$.ajax({
    			url: stockphotography.ajaxurl,
    			type: "POST",
    			dataType: 'json',
    			data: { action : 'stockphotography_liked_ajax', id : id[1] },
    			success:function(data) {
    				if(true==data.success) {
    					$('#like-'+data.postID).text(" " + data.count);
    					$('#like-'+data.postID).addClass('active');
    				}
    			}
    		});
    		return false;
    	});
    }

	// Like Active Class
	$('.like-count').each(function() {
	    var $like_count = 0;
		var $like_count = $(this).text();
		if($like_count != 0) {
	        $(this).addClass('active');
	    }
	});

	$('#twitter').sharrre({
	        share: {
	            twitter: true
	        },
	        template: '<a class="share" href="#"><div class="genericon genericon-twitter"></div></a>',
	        enableHover: false,
	        click: function(api, options){
	            api.simulateClick();
	            api.openPopup('twitter');
	        }
	    });

	    $('#facebook').sharrre({
	        share: {
	            facebook: true
	        },
	        template: '<a class="share" href="#"><div class="genericon genericon-facebook"></div></a>',
	        enableHover: false,
	        click: function(api, options){
	            api.simulateClick();
	            api.openPopup('facebook');
	        }
		});

	// Removing AJAX security field only when form is not used as a shortcode
	$('#header-inner #smas_security').remove();
});
