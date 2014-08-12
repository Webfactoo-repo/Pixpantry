<?php
/**
 * This file is served from the htaccess rules, from here PHP
 * mimics a jpg file.
 *
 * @author Zane M. Kolnik
 * @since 1.0.0
 */
header("Content-type:image/jpeg");
require_once( '../../../wp-load.php' );
$wp_upload_dir = wp_upload_dir();
$image_array = explode('=', $_SERVER['QUERY_STRING'] );
$dir_and_image = $wp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_array[1];

header('Content-Disposition: inline; filename="' . $image_array[1] . '"');

$settings = sell_media_get_plugin_options();
if ( empty( $settings->watermark_attachment_url ) ){
    $watermark = SELL_MEDIA_WATERMARK_DEFAULT;
} else {

    /**
     * Ensure that the watermark is always based using the server path
     * and not the URL
     */
    $basename = basename( $settings->watermark_attachment_url );
    if ( $basename == 'watermark-default.png' ){
        $watermark = plugin_dir_path( __FILE__ ) . $basename;
    } elseif( ! empty( $settings->watermark_attachment_url ) ) {
        $watermark = $settings->watermark_attachment_url;
    } else {
        // use tha attachment id and get the server path
        $watermark = get_attached_file( $settings->watermark_attachment_id );
    }
}

/**
 * If the watermarked image already is created we load it from cache, if not
 * we create the watermarked image.
 */
$image = plugin_dir_path( __FILE__ ) . 'cache' . DIRECTORY_SEPARATOR . basename( $image_array[1] );

if ( file_exists( $image ) ){
    print file_get_contents( $image );
} else {
    sell_media_create_watermark( $dir_and_image, $watermark );
}