<?php
/**
 *Stock Photography functions and definitions
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

/**
 * Set the theme option variable for use throughout theme.
 *
 * @since full_frame 1.0
 */
if ( ! isset( $theme_options ) )
	$theme_options = get_option( 'stock_photography_options' );
global $theme_options;

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Stock Photography 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 940; /* pixels */

if ( ! function_exists( 'stock_photography_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Stock Photography 1.0
 */
function stock_photography_setup() {
	/**
	 * Actions for this theme.
	 */
	require( get_template_directory() . '/inc/actions.php' );
	/**
	 * Filters for this theme.
	 */
	require( get_template_directory() . '/inc/filters.php' );
	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );


	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Stock Photography, use a find and replace
	 * to change 'stock_photography' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'stock_photography', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Add custom background support
	 */
	$args = array(
		'default-color' => 'ffffff'
	);

	add_theme_support( 'custom-background', $args );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	/**
	 * Set Image sizes
	 */
	set_post_thumbnail_size( 50, 50, true ); // default thumbnail
	update_option( 'thumbnail_size_w', 50, true );
	update_option( 'thumbnail_size_h', 50, true );
	add_image_size( 'sell_media_item', 420, '', true ); // entry images

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'top' => __( 'Top Menu', 'stock_photography' ),
		'primary' => __( 'Primary Menu', 'stock_photography' )
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'image', 'video', 'gallery' ) );

	/**
	 * Remove Admin Bar
	 */
	$current_user = wp_get_current_user();
	if ( is_array( $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}
}
endif; // stock_photography_setup
add_action( 'after_setup_theme', 'stock_photography_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Stock Photography 1.0
 */
function stock_photography_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'stock_photography' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
	// check if sell media plugin is active
	if ( stock_photography_sell_media_check() ) {
		register_sidebar( array(
			'name' => __( 'Sidebar (Sell Media Single Items)', 'stock_photography' ),
			'id' => 'sell-media-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
	}

	register_sidebar( array(
		'name' => __( 'Homepage Top', 'stock_photography' ),
		'id' => 'homepage-top',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Homepage Bottom', 'stock_photography' ),
		'id' => 'homepage-bottom',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	$widgets = array( '1', '2', '3' );
	foreach ( $widgets as $i ) {
		register_sidebar(array(
			'name' => __( 'Footer Widget ', 'stock_photography' ) .$i,
			'id' => 'footer-widget-'.$i,
			'before_widget' => '<div class="widget">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>'
		) );
	} // end foreach
}
add_action( 'widgets_init', 'stock_photography_widgets_init' );


/**
 * Count the number of footer widgets to enable dynamic classes for the footer
 *
 * @since Stock Photography 1.0
 */
function stock_photography_footer_widget_class() {
    $count = 0;

    if ( is_active_sidebar( 'footer-widget-1' ) )
        $count++;

    if ( is_active_sidebar( 'footer-widget-2' ) )
        $count++;

    if ( is_active_sidebar( 'footer-widget-3' ) )
        $count++;

    $class = '';

    switch ( $count ) {
        case '1':
            $class = 'one';
            break;
        case '2':
            $class = 'two';
            break;
        case '3':
            $class = 'three';
            break;
    }

    if ( $class )
        echo 'class="' . $class . '"';
}

/**
 * Enqueue scripts and styles
 */
function stock_photography_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() , '', stock_photography_get_theme_version() );
	wp_enqueue_style( 'stock_photography-flexslider', get_template_directory_uri() . '/js/flexslider/flexslider.css', stock_photography_get_theme_version() );

	wp_enqueue_script( 'stock_photography-navigation', get_template_directory_uri() . '/js/navigation.js', array(), stock_photography_get_theme_version(), true );
	wp_enqueue_script( 'stock_photography-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), stock_photography_get_theme_version(), true );

	wp_enqueue_script( 'jcookie' );

	wp_enqueue_script( 'stock_photography-sharrre', get_template_directory_uri() . '/js/jquery.sharrre-1.3.4.min.js', array( 'jquery' ), stock_photography_get_theme_version() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'stock_photography-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), stock_photography_get_theme_version() );
	}

	// AJAX url variable
	wp_localize_script( 'stock_photography-scripts', 'stockphotography',
		array(
			'ajaxurl'=>admin_url('admin-ajax.php'),
			'ajaxnonce' => wp_create_nonce('ajax-nonce')
			)
		);

	wp_enqueue_script( 'stock_photography-flexslider', get_template_directory_uri() .'/js/flexslider/jquery.flexslider-min.js', array( 'jquery' ), stock_photography_get_theme_version() );
	wp_enqueue_script( 'stock_photography-flexslider-custom', get_template_directory_uri() .'/js/flexslider/flex_js.js', array( 'jquery' ), stock_photography_get_theme_version() );

}
add_action( 'wp_enqueue_scripts', 'stock_photography_scripts' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Implement Like feature
 */
require_once( get_template_directory() . '/inc/likes.php' );

/**
 * Implement Lightbox feature
 */
require_once( get_template_directory() . '/inc/lightbox.php' );

/**
 * Register Widgets
 */
require_once ( get_template_directory() . '/inc/widgets/sell-media-author.php'); // author media widget
require_once ( get_template_directory() . '/inc/widgets/sell-media-popular.php'); // popular media widget
require_once ( get_template_directory() . '/inc/widgets/sell-media-share.php'); // share media widget
require_once ( get_template_directory() . '/inc/widgets/sell-media-exif.php'); // exif widget
require_once ( get_template_directory() . '/inc/widgets/sell-media-author-info.php'); // exif widget

/**
 * Theme options
 */
if ( file_exists( get_template_directory() . '/options/options.php' ) )
	require( get_template_directory() . '/options/options.php' );
if ( file_exists( get_template_directory() . '/options/options.php' ) && file_exists( get_template_directory() . '/theme-options.php' ) )
	require( get_template_directory() . '/theme-options.php' );

/**
 * We do not want search results being appended to the header form,
 * so we filter the form id.
 */
function stock_photography_search_form_id( $form_id ){
	return;
}
add_filter('sell_media_advanced_search_form_id','stock_photography_search_form_id');

/**
 * Author archive is always set to sell_media_items
 */
function custom_author_archive( &$query ) {
    if ($query->is_author)
        $query->set( 'post_type', 'sell_media_item' );
}
add_action( 'pre_get_posts', 'custom_author_archive' );



/** changing default wordpres email settings */
 
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function new_mail_from($old) {
 return 'pix@pixpantry.com';
}
function new_mail_from_name($old) {
 return 'Pixpantry';
}