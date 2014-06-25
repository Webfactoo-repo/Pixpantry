<?php
/**
 *Stock Photography Theme Customizer
 *
 * @package Stock Photography
 * @since Stock Photography 1.2
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since Stock Photography 1.2
 */
function stock_photography_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'stock_photography_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Stock Photography 1.2
 */
function stock_photography_customize_preview_js() {
	wp_enqueue_script( 'stock_photography_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'stock_photography_customize_preview_js' );
