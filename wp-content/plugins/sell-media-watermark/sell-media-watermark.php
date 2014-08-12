<?php
/**
 * Plugin Name: Sell Media Watermark
 * Plugin URI: http://graphpaperpress.com/?download=watermark
 * Description: Watermarks images that are marked "for sale" or all your images.
 * Version: 1.1.3
 * Author: Graph Paper Press
 * Author URI: http://graphpaperpress.com
 * Author Email: support@graphpaperpress.com
 * License: GPL
 */


define( 'SELL_MEDIA_WATERMARK_VERSION', '1.1.3' );
define( 'SELL_MEDIA_WATERMARK_OPTION', 'sell_media_watermark_version' );
define( 'SELL_MEDIA_WATERMARK_DEFAULT', plugin_dir_url( __FILE__ ) . 'watermark-default.png' );

function sell_media_watermark_init(){
    global $pagenow;

    if ( ! empty( $pagenow ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'sell_media_plugin_options' ){
        wp_enqueue_media();
        wp_enqueue_script( 'sell-media-watermark-js', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ) );
        wp_localize_script( 'sell-media-watermark-js', 'sell_media_watermark', array(
            'clearing_cache' => __('Clearing cache...','sell_media'),
            'cleared_cache' => __('Cleared Cache','sell_media')
            )
        );
    }
}
add_action('admin_init', 'sell_media_watermark_init');


/**
 * Add the version number to the options table when
 * the plugin is installed.
 *
 * @since 1.0.0
 */
function sell_media_watermark_activation() {

    if ( get_option( SELL_MEDIA_WATERMARK_OPTION ) &&
         get_option( SELL_MEDIA_WATERMARK_OPTION ) > SELL_MEDIA_WATERMARK_VERSION )
        return;

    //$path = plugin_dir_path( __FILE__ );
    $path = plugin_dir_url( __FILE__ );
    $contents  = "RewriteEngine on\n";
    $contents .= "RewriteRule ^(.*\.(jpg|jpeg))$ {$path}watermark.php?image=$1 [NC]";

    $wp_upload_dir = wp_upload_dir();
    $file = $wp_upload_dir['basedir'] . '/.htaccess';

    file_put_contents( $file, $contents );

    update_option( SELL_MEDIA_WATERMARK_OPTION, SELL_MEDIA_WATERMARK_VERSION );
}
register_activation_hook( __FILE__, 'sell_media_watermark_activation' );


/**
 * Delete our version number from the database
 *
 * @since 1.0.0
 */
function sell_media_watermark_deactivation(){

    delete_option( SELL_MEDIA_WATERMARK_OPTION );

    $wp_upload_dir = wp_upload_dir();
    $file = $wp_upload_dir['basedir'] . '/.htaccess';
    unlink( $file );
}
register_deactivation_hook( __FILE__, 'sell_media_watermark_deactivation' );


/**
 * Creates an array of settings for watermark
 *
 * @param $options (array) multi-dimensional array of current Sell Media settings.
 * @return The merged array of current Sell Media settings and watermark settings
 */
function sell_media_watermark_settings( $options ){

    $tab = 'misc_plugin_tab';
    $section = 'watermark_section';

    $count = count( glob( plugin_dir_path( __FILE__ ) . "cache/*.{jpg,jpeg,png}", GLOB_BRACE ) );
    if ( $count == 0 ) {
        $message = __( "Cache is currently empty", "sell_meda" );
    } else {
        $message = sprintf(
            "%s <span class='cache-count'><em>%s %d</em></span> %s",
            '<input type="button" value="Clear Cache" class="button" name="sell_media_watermark_clear_cache" id="sell_media_watermark_clear_cache_button" /><br />',
            __( "Cache Count", "sell_media" ),
            $count,
            wp_nonce_field('sell_media_watermark_clear_cache','security', true, false)
            );
    }

    $additional_options = array(
        "watermark_all" => array(
            "tab" => $tab,
            "name" => "watermark_all",
            "title" => __("Watermark all images","sell_media"),
            "description" => "",
            "section" => $section,
            "since" => "1.0",
            "id" => $section,
            "type" => "checkbox",
            "default" => 0,
            "valid_options" => array(
                "yes" => array(
                    "name" => "yes",
                    "title" => __("This does not save over existing image files, images are not permanently watermarked.","sell_media")
                    )
                )
            ),
        "watermark_attachment_url" => array(
            "tab" => $tab,
            "name" => "watermark_attachment_url",
            "title" => __("Current Watermark","sell_media"),
            "description" => __("This is the image that will show ontop of your existing image. For best results use a transparent PNG and make sure the watermark is smaller than your image.", "sell_media"),
            "section" => $section,
            "since" => "1.0",
            "id" => $section,
            "default" => "",
            "type" => "image"
            ),
        "cache" => array(
            "tab" => $tab,
            "name" => "cache",
            "title" => __("Cache Settings","sell_media"),
            "description" => __(" ","sell_media"),
            "section" => $section,
            "since" => "1.0",
            "id" => $section,
            "type" => "html",
            "default" => "",
            "valid_options" => $message
            )
        );

    return wp_parse_args( $options, $additional_options );
}
add_filter('sell_media_options','sell_media_watermark_settings');


function sell_media_watermark_misc_tab( $misc_tab ){
    $misc_tab['sections']['watermark_section'] = array(
        'name' => 'watermark_section',
        'title' => __('Watermark','sell_media'),
        'description' => ''
        );
    return $misc_tab;
}
add_filter('sell_media_misc_tab','sell_media_watermark_misc_tab');


/**
 * This function checks the passed in image against the images in the
 * database that are marked "for sale".
 *
 * @since 1.0.0
 * @author Zane M. Kolnik
 * @param $original_image
 * @return File name of file that is for sale, false if file is not for sale.
 */
function sell_media_watermark_file_for_sale( $original_image=null ){
    $file_name = basename( $original_image );

    global $wpdb;

    // retrieve all Items
    $sell_media_items = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_sell_media_for_sale_product_id';" );

    $item_ids = array();
    foreach( $sell_media_items as $item ){
        $item_ids[] = $item->post_id;
    }
    $item_ids_in = implode( ',', $item_ids );

    $files = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_wp_attached_file' AND ( post_id IN({$item_ids_in}) );" );

    $for_sale = false;
    foreach( $files as $file ){
        $mystring = $file_name;

        /**
         * Due to size variants we match part of the file name
         */
        $findme   = explode( '.', basename( $file->meta_value ) );
        $findme = $findme[0];
		$for_salee["files"][] = array($mystring, $findme."a");
        $pos = strpos($mystring, $findme);
        if ($pos !== false) {
            $for_sale = basename( $file->meta_value );
        }
    }
    return $for_sale;
}


/**
 * Create a new image using the original image and the watermarked image
 *
 * @since 1.0.0
 * @param $original_image Full server path to image
 * @param $original_watermark Full server path to image
 */
function sell_media_create_watermark( $original_image, $original_watermark ){
    $for_sale = sell_media_watermark_file_for_sale( $original_image );
    $settings = sell_media_get_plugin_options();
// Lordcase - manually remove cover from watermarking
    if ( $for_sale || ($settings->watermark_all[0] == 'yes' && basename($original_image)!="cropped-cover2.jpg")){

        $dir = dirname( $original_image );
        $image = imagecreatefromjpeg( $original_image );

        list( $imagewidth, $imageheight ) = getimagesize( $original_image );

        $mime_type = wp_check_filetype( $original_watermark );

        if ( $mime_type['type'] == 'image/jpeg' || $mime_type['type'] == 'image/jpg' ){
            $watermark = @imagecreatefromjpeg( $original_watermark );
        } else {
            $watermark = @imagecreatefrompng( $original_watermark );
        }

        /**
         * If for some reason creating the watermark fails lets just
         * load the original image.
         */
        if ( ! $watermark ){
            $image = imagecreatefromjpeg( $original_image );
            imagejpeg($image);
            return;
        }

        list( $watermarkwidth,$watermarkheight ) = getimagesize( $original_watermark );

        if ( $watermarkwidth > $imagewidth || $watermarkheight > $imageheight ){
            $water_resize_factor = $imagewidth / $watermarkwidth;
            $new_watermarkwidth  = $watermarkwidth * $water_resize_factor;
            $new_watermarkheight = $watermarkheight * $water_resize_factor;

            $new_watermark = imagecreatetruecolor($new_watermarkwidth , $new_watermarkheight);

            imagealphablending($new_watermark , false);
            imagecopyresampled($new_watermark , $watermark, 0, 0, 0, 0, $new_watermarkwidth, $new_watermarkheight, $watermarkwidth, $watermarkheight);

            $watermarkwidth  = $new_watermarkwidth;
            $watermarkheight = $new_watermarkheight;
            $watermark       = $new_watermark;
        }
        $startwidth     =   ($imagewidth    -   $watermarkwidth)  / 2;
        $startheight    =   ($imageheight   -   $watermarkheight) / 2;

        imagecopy($image, $watermark, $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);

        /**
         * Create our cache directory if it does not exists
         */
        $cache_dir = plugin_dir_path( __FILE__ ) . 'cache' . DIRECTORY_SEPARATOR;
        if ( ! file_exists( $cache_dir ) ){
            wp_mkdir_p( $cache_dir );
        }

        if ( file_exists( $cache_dir ) ){
            imagejpeg( $image, $cache_dir . basename( $original_image ) );
            imagejpeg( $image );
        } else {
            imagejpeg( $image );
        }
    } else {
        $image = imagecreatefromjpeg( $original_image );
        imagejpeg($image);
    }
}


/**
 * Removes images from the cache folder
 */
function sell_media_watermark_clear_cache(){

    check_ajax_referer('sell_media_watermark_clear_cache', 'security' );

    $files = glob( plugin_dir_path( __FILE__ ) . "cache/*.{jpg,jpeg,png}", GLOB_BRACE );
    foreach( $files as $file ){
        if ( is_file( $file ) ){
            @unlink( $file );
        }
    }

}
add_action( 'wp_ajax_sell_media_watermark_clear_cache','sell_media_watermark_clear_cache' );
