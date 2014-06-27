<?php

/**
 * Plugin Name: Sell Media Advanced Search
 * Plugin URI: http://graphpaperpress.com/plugins/sell-media-advanced-search
 * Description: Adds additional searching features such as; Keywords (using autocomplete), minimum and maximum price, Collection or License Type, aspect ratio (portrait or landscape) for the Sell Media Plugin.
 * Version: 1.0.4
 * Author: Graph Paper Press
 * Author URI: http://graphpaperpress.com
 * Author Email: support@graphpaperpress.com
 * License: GPL
 */

define( 'SELL_MEDIA_ADVANCED_SEARCH_VERSION', '1.0.4' );

require_once plugin_dir_path( __FILE__ ) . 'shortcodes.php';

/**
 * When plugin is installed add/update the version
 * number in the database.
 *
 * @since 1.0
 */
function sell_media_advanced_search(){
    $version = get_option( 'sell_media_advanced_search_version' );

    if ( $version && $version > SELL_MEDIA_ADVANCED_SEARCH_VERSION )
    return;

    update_option( 'sell_media_advanced_search_version', SELL_MEDIA_ADVANCED_SEARCH_VERSION );
}
register_activation_hook( __FILE__, 'sell_media_advanced_search');


/**
 * Builds WP_Query and prints the result
 *
 * @since 1.0
 */
function sell_media_advanced_search_submit(){

    check_ajax_referer( 'smas_nonce', 'security' );

    $query = New WP_Query( sell_media_advanced_search_build_query( $_POST ) );

    if ( $query->post_count == 0 ) {
        $html = __( sprintf( '<div class="smas-no-results">%s</div>', 'Sorry no results' ), 'sell_media' );
    } else {
        $final_post_ids = sell_media_advanced_search_aspect_size_post_ids( $query->posts, $_POST['aspect_ratio'] );
        $count = count( $final_post_ids );
        $html = __( sprintf("<div class='smas-main-title'> Search Results <span class='smas-count'>(%d)</span></div>", $count ), 'sell_media' );
        foreach( $final_post_ids as $post_id ){
            $html .= '<div class="sell-media-grid smas-item">';
            $html .= '<div class="smas-image-container">';
            $html .= '<a href="'. get_permalink( $post_id ) . '">';
            $html .= sell_media_item_icon( get_post_meta( $post_id, '_sell_media_attachment_id', true ), 'thumbnail', false );
            $html .= sell_media_item_buy_button( $post_id, 'text', __('Purchase','sell_media'), false );
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }

    print $html;
    die();
}
add_action( 'wp_ajax_nopriv_sell_media_advanced_search_submit', 'sell_media_advanced_search_submit' );
add_action( 'wp_ajax_sell_media_advanced_search_submit', 'sell_media_advanced_search_submit' );


function sell_media_advanced_search_aspect_size_post_ids( $posts=null, $aspect_ratio=null ){

    $final_post_ids = array();
    $wp_upload_dir = wp_upload_dir();

    if ( $aspect_ratio == 'any' ){
        foreach( $posts as $post ){
            setup_postdata( $post );
            $final_post_ids[] = $post->ID;
        }
    } else {

        $image_mimes = array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/tiff'
            );

        foreach( $posts as $post ) {
            setup_postdata( $post );
            $attach_id = get_post_meta( $post->ID, '_thumbnail_id', true );

            $attached_file = get_post_meta( get_post_meta( $post->ID, '_sell_media_attachment_id', true ), '_wp_attached_file', true );
            $filetype = wp_check_filetype( $wp_upload_dir['basedir'] . '/' . $attached_file );

            if ( ! in_array( $filetype['type'], $image_mimes ) )
                continue;

            list( $width, $height ) = getimagesize( $wp_upload_dir['basedir'] . '/' . $attached_file );

            if ( $aspect_ratio == 'landscape' && $height < $width ){
                $final_post_ids[] = $post->ID;
            }

            if ( $aspect_ratio == 'portrait' && $height > $width ){
                $final_post_ids[] = $post->ID;
            }

        }
    }

    return $final_post_ids;
}

function sell_media_advanced_search_build_query( $temp=array() ){

    /**
     * Remove any empty items or items that are in our array
     */
    foreach( $temp as $k => $v ){
        if ( empty( $v ) || in_array( $k, array( 'smas_security', 'security', 'action', 's' ) ) ){
            unset( $temp[ $k ] );
        }
    }

    $args = array();
    /**
     * Build our taxonomy query
     *
     * If our tax_query is greater than 1, i.e. has more than // [relation] => AND we add it to our
     * default arguments
     */
    foreach( $temp['tax_query'] as $k => $v ){

        /** Check if this is an array since 'realtion' => 'and|or' can be in $v as well. */
        if ( is_array( $v ) ){

            /** If it is we loop over each checking for keywords, collection, license, etc. */
            foreach( $v as $kk => $vv ){

                /**
                 * Keywords are a comma separated list, we explode on "," and also trim white space.
                 * From the keyword we derive the term_id if it exists. Then build our keyword query.
                 * Finally we replace the previous array with the new one.
                 */
                if ( $kk == 'keywords' ){
                    /**
                     * If we don't have any keywords we remove it from our array of taxonomies
                     */
                    if ( empty( $temp['tax_query'][ $k ][ $kk ] ) ){
                        unset( $temp['tax_query'][ $k ] );
                    } else {
                        $tmptmp_keywords = explode( ',', $vv );
                        $keyword_ids = null;
                        foreach( $tmptmp_keywords as $keywordss ){
	                        $tmp_keywords = explode( ' ', $keywordss );
	                        foreach( $tmp_keywords as $keyword ){
	                            $keyword = trim( $keyword );
	                            if ( ! empty( $keyword ) && $id = term_exists( $keyword ) ) $keyword_ids[] = $id;
	                        }
						}
                        if ( ! is_null( $keyword_ids ) ){
                            $temp['tax_query'][ $k ] = array(
                                'taxonomy' => $kk,
                                'field' => 'id',
                                'terms' => $keyword_ids,
                                'operator' => 'AND'
                            );
                        } else {
                            unset( $temp['tax_query'][ $k ] );
                        }
                    }
                }

                elseif ( $kk == 'collection' ){
                    /**
                     * If we don't have any collections we remove it from our array of taxonomies
                     */
                    if ( empty( $temp['tax_query'][ $k ][ $kk ] ) ){
                        unset( $temp['tax_query'][ $k ] );
                    } else {
                        $temp['tax_query'][ $k ] = array(
                            'taxonomy' => $kk,
                            'field' => 'id',
                            'terms' => $temp['tax_query'][ $k ][ $kk ],
                            'operator' => 'AND'
                        );
                    }
                }

                elseif ( $kk == 'licenses' ){
                    /**
                     * If we don't have any licenses we remove it from our array of taxonomies
                     */
                    if ( empty( $temp['tax_query'][ $k ][ $kk ] ) ){
                        unset( $temp['tax_query'][ $k ] );
                    } else {
                        $temp['tax_query'][ $k ] = array(
                            'taxonomy' => $kk,
                            'field' => 'id',
                            'terms' => $temp['tax_query'][ $k ][ $kk ],
                            'operator' => 'IN'
                        );
                    }
                }
            }
        }
    }
    if ( count( $temp['tax_query'] ) > 1 ) $args['tax_query'] = $temp['tax_query'];


    /**
     * Build out meta query
     *
     * If we have a meta value to query from we add it to our default arguments
     */
    foreach( $temp['meta_query'] as $k => $v ){
        foreach( $v as $kk => $vv ){
            if ( $kk == 'min_price'
                && ! empty( $temp['meta_query'][ $k ]['min_price'] ) ) {
                $value[] = $temp['meta_query'][ $k ]['min_price'];
                $compare = '>=';
            }

            if ( $kk == 'max_price'
                && ! empty($temp['meta_query'][ $k ]['max_price'] ) ) {
                $value[] = $temp['meta_query'][ $k ]['max_price'];
                $compare = '<=';
            }
        }
    }

    if ( ! empty( $value ) ){
        $args['meta_query'] = array(
            array(
                'key' => 'sell_media_price',
                'type' => 'NUMERIC',
                'value' => ( count( $value ) == 1 ) ? $value[0] : $value,
                'compare' => ( count( $value ) == 1 ) ? $compare : 'BETWEEN'
                )
            );
    }


    /**
     * If we have arguments to add, i.e., collection, keyword, license
     * we merge it with the default.
     */
        $defaults = array(
            'post_type' => 'sell_media_item',
            'post_status' => 'publish',
            'posts_per_page' => -1
            );
        $args = array_merge($defaults,$args);


    return $args;
}


/**
 * Builds the html for our search form and selects default values based on previous search
 * terms from $_GET
 *
 * @return HTML form
 */
function sell_media_advanced_search_form(){

    $form_id = apply_filters( 'sell_media_advanced_search_form_id', 'smas-ajax-submit' );
    $form_results_container = '<div class="smas-main-container"><div class="smas-loading-icon" style="display:none;">' . __('Loading items...', 'sell_media') .'</div><div class="smas-results-target-container"></div></div>';
    $form_results_container = apply_filters( 'sell_media_search_form_results', $form_results_container );

    $terms = get_terms('licenses' );

    /**
     * Build our array of default search values pulled if $_GET is set
     */
    $current = array(
        'keywords'=>null,
        'aspect_ratio' => empty( $_GET['aspect_ratio'] ) ? null : $_GET['aspect_ratio'],
        'license_id' => null,
        'collection_id' => null,
        'min_price' => null,
        'max_price' => null
        );

    if ( ! empty( $_GET['tax_query'] ) || ! empty( $_GET['meta_query'] ) ){
        foreach( $_GET['tax_query'] as $k => $v ){
            if ( ! is_array( $v ) ) continue;
            foreach( $v as $kk => $vv ){
                if ( $kk == 'licenses' ){
                    $current['license_id'] = $_GET['tax_query'][ $k ][ $kk ];
                } else if ( $kk == 'collection' ){
                    $current['collection_id'] = $_GET['tax_query'][ $k ][ $kk ];
                } else if ( $kk == 'keywords' ){
                    $current['keywords'] = rtrim( trim( $_GET['tax_query'][ $k ][ $kk ] ), ',');
                }
            }
        }
        $current['min_price'] = empty( $_GET['meta_query'][0]['min_price'] ) ? null : $_GET['meta_query'][0]['min_price'];
        $current['max_price'] = empty( $_GET['meta_query'][1]['max_price'] ) ? null : $_GET['meta_query'][1]['max_price'];
    }

    ob_start(); ?>
    <div class="smas-container">
        <div class="smas-sidebar-container">
            <form name="" class="smas-form" id="<?php print $form_id; ?>" action="<?php echo home_url(); ?>/" method="GET">

                <input type="hidden" value="<?php print wp_create_nonce("smas_nonce"); ?>" name="smas_security" id="smas_security" />
                <input type="hidden" name="s" value="search" />
                <input type="hidden" name="tax_query[relation]" value="AND" />
                <input type="hidden" name="post_type" value="sell_media_item" />
                <input type="hidden" name="sell_media_advanced_search_flag" value="true" />

                <!-- Keywords -->
                <div class="smas-fieldset" id="smas_keywords">
                    <div class="smas-legend-title"><?php _e( 'Keywords', 'sell_media' ); ?></div>
                    <div class="smas-row"><input type="text" id="smas_keywords_text" name="tax_query[][keywords]" value="<?php print $current['keywords']; ?>" /></div>
                </div>
                <!-- Keywords -->


                <!-- Price Range -->
                <div class="smas-fieldset" id="smas_price_range">
                    <div class="smas-legend-title"><?php _e( 'Price Range', 'sell_media' ); ?> (<?php echo sell_media_get_currency_symbol(); ?>)</div>
                    <div class="smas-row">
                        <input id="smas_price_range_text" class="smas-min-price" name="meta_query[][min_price]" value="<?php print $current['min_price']; ?>" /> to
                        <input id="smas_price_range_text" class="smas-max-price" name="meta_query[][max_price]" value="<?php print $current['max_price']; ?>" />
                    </div>
                </div>
                <!-- Price Range -->


                <!-- Only show on image mime types -->
                <div class="smas-fieldset" id="smas_image_orientation" style="display: none;">
                    <div class="smas-legend-title"><?php _e( 'Orientation', 'sell_media' ); ?></div>
                    <div class="smas-row"><input type="checkbox" name="horizontal" /><label><?php _e( 'Horizontal', 'sell_media' ); ?></label></div>
                    <div class="smas-row"><input type="checkbox" name="vertical" /><label><?php _e( 'Vertical', 'sell_media' ); ?></label></div>
                </div>
                <div class="smas-fieldset" id="smas_color_picker" style="display: none;">
                    <div class="smas-legend-title"><?php _e( 'Color', 'sell_media' ); ?></div>
                    <div class="smas-row"><input type="text" id="smas_color_pick_text" name="color" /></div>
                </div>
                <!-- Only show on image mime types -->


                <!-- Collections -->
                <div class="smas-fieldset" id="smas_collections">
                    <div class="smas-legend-title"><?php _e('Collection', 'sell_media'); ?></div>
                    <select name="tax_query[][collection]">
                        <option value=""><?php _e( 'All', 'sell_media' ); ?></option>
                    <?php foreach( get_terms('collection' ) as $term ) : ?>
                        <option value="<?php print $term->term_id; ?>" <?php selected( $term->term_id, $current['collection_id'] ); ?>><?php print $term->name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <!-- Collections -->


                <!-- License Types -->
                <div class="smas-fieldset" id="smas_licenses">
                    <div class="smas-legend-title"><?php _e('License', 'sell_media'); ?></div>
                    <select name="tax_query[][licenses]">
                        <option value=""><?php _e( 'All', 'sell_media' ); ?></option>
                    <?php foreach( $terms as $term ) : ?>
                        <option value="<?php print $term->term_id; ?>" <?php selected( $term->term_id, $current['license_id'] ); ?>><?php print $term->name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <!-- License Types -->


                <!-- Aspect Ratio -->
                <div class="smas-fieldset" id="smas_aspect_ratio">
                    <div class="smas-legend-title"><?php _e('Aspect Ratio', 'sell_media'); ?></div>
                    <select name="aspect_ratio">
                        <option value="any"><?php _e( 'All', 'sell_media' ); ?></option>
                        <?php foreach( array( 'landscape', 'portrait' ) as $orientation ) : ?>
                            <option value="<?php print $orientation; ?>" <?php selected( $orientation, $current['aspect_ratio'] ); ?>><?php _e( ucfirst( $orientation ), 'sell_media' ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Aspect Ratio -->


                <!-- Button Container -->
                <div class="smas-button-container">
                    <input type="submit" />
                </div>
                <!-- Button Container -->
            </form>
        </div>
        <?php print $form_results_container; ?>
    </div>
<?php return ob_get_clean();
}

function sell_media_advanced_search_filter( $query ){

    if ( $query->is_search
        && ! empty( $_GET['sell_media_advanced_search_flag'] ) // Flag to prevent this from firing on default search request
        && $query->query_vars['post_type'] == 'sell_media_item' ) {

        $args = sell_media_advanced_search_build_query( $_GET );

        if ( $_GET['aspect_ratio'] == 'landscape' || $_GET['aspect_ratio'] == 'portrait' ){
            $query = New WP_Query( $args );
            $post_ids = sell_media_advanced_search_aspect_size_post_ids( $query->posts, $_GET['aspect_ratio'] );
            $args['post__in'] = $post_ids;
        }

    }

    return ( empty( $args['tax_query'] ) ) && ( empty( $args['post__in'] ) ) ? $query : query_posts( $args );
}
add_action('pre_get_posts','sell_media_advanced_search_filter');