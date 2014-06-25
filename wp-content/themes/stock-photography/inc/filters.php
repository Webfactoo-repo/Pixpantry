<?php
/**
 * Stock Photography Filters
 * @package Stock Photography
 * @author Thad Allender
 */

/**
 * Filters the body_class and adds the css class
 */
function stock_photography_browser_class( $classes ) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	// Browser detection
	if($is_lynx) $classes[] = 'browser-lynx';
	elseif($is_gecko) $classes[] = 'browser-gecko';
	elseif($is_opera) $classes[] = 'browser-opera';
	elseif($is_NS4) $classes[] = 'browser-ns4';
	elseif($is_safari) $classes[] = 'browser-safari';
	elseif($is_chrome) $classes[] = 'browser-chrome';
	elseif($is_IE) $classes[] = 'browser-ie';
	elseif($is_iphone) $classes[] = 'browser-iphone';
	else $classes[] = '';
	// Check for non-multisite installs
	if ( ! is_multi_author() ) $classes[] = 'single-author';
	// Do we have a header image?
	$header_image = get_header_image();
    if ( $header_image ) $classes[] = 'has-header-image';
    // Is the sidebar enabled?
    if ( is_active_sidebar( 'sidebar-1' ) && !is_page_template( 'page-full.php' ) )
    	$classes[] = 'has-sidebar';
    else
    	$classes[] = 'no-sidebar';
    if ( ! is_home() )
    	$classes[] = 'not-home';

	return $classes;
}
// Filter body_class with the function above
add_filter( 'body_class','stock_photography_browser_class' );

// Filter excerpt length
function stock_photography_excerpt_length( $length ) {
	return 14;
}
add_filter( 'excerpt_length', 'stock_photography_excerpt_length', 999 );

function stock_photography_new_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'stock_photography_new_excerpt_more' );

 
// Add custom class to nav menu items
function stock_photography_custom_nav_class($classes, $item){
 
	if( $item->object == "page"){ 
		$template = get_post_meta( $item->object_id, '_wp_page_template', true );
		if ( $template == "page-lightbox.php" ) {
			$classes[] = "lightbox-menu";
		}
	}
	return $classes;
}
add_filter('nav_menu_css_class' , 'stock_photography_custom_nav_class' , 10 , 2);

/*
 * Filter the image size to use the larger thumbnail
 */
function stock_photography_sm_thumbnail(){
    return 'sell_media_item';
}
add_filter( 'sell_media_thumbnail', 'stock_photography_sm_thumbnail' );