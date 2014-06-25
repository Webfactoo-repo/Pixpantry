<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Stock Photography 1.0
 */
function stock_photography_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'stock_photography_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Stock Photography 1.0
 */
function stock_photography_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'stock_photography_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Stock Photography 1.0
 */
function stock_photography_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'stock_photography_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Stock Photography 1.1
 */
function stock_photography_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'stock_photography' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'stock_photography_wp_title', 10, 2 );


/**
 * Check if Sell Media is active plugin in options array
 *
 * @since Stock Photography 1.0
 */
function stock_photography_sell_media_check(){
	$plugins = get_option( 'active_plugins' );
	if ( in_array ( 'sell-media/sell-media.php', $plugins ) )
		return true;
}

/**
 * Check if item is part of a taxonomy
 */
function stock_photography_sell_media_item_has_taxonomy_terms( $post_id=null, $taxonomy=null ) {

    $terms = wp_get_post_terms( $post_id, $taxonomy );

    if ( empty ( $terms ) )
        return false;
    else
        return true;

}

/**
 * Print attached image keywords
 */
function stock_photography_sell_media_image_keywords( $post_id=null ) {

    $product_terms = wp_get_object_terms( $post_id, 'keywords' );
    if ( !empty( $product_terms ) ) {
        if ( !is_wp_error( $product_terms ) ) {
            foreach ( $product_terms as $term ) {
                echo '<a href="' . get_term_link( $term->slug, 'keywords' ) . '">' . $term->name . '</a> ';
            }
        }
    }
}

/**
* Get theme version number from WP_Theme object (cached)
*
* @since Stock Photography 1.0
*/
function stock_photography_get_theme_version() {
    $stock_photography_theme_file = get_template_directory() . '/style.css';
    $stock_photography_theme = new WP_Theme( basename( dirname( $stock_photography_theme_file ) ), dirname( dirname( $stock_photography_theme_file ) ) );
    return $stock_photography_theme->get( 'Version' );
}