<?php
/**
 * Search form shortcode [sell_media_searchform_advanced]
 *
 * @since 1.0
 */
function sell_media_search_shortcode_function( $atts, $content = null ) {
    $terms = get_terms( 'keywords' );
    $count = count( $terms );
    $i = 0;
    wp_enqueue_style( 'sell-media-expander-style' );
    wp_enqueue_style( 'smas-style', plugin_dir_url( __FILE__ ) . 'css/smas-style.css' );
    ob_start(); ?>
    <script type="text/javascript">var _smas_keywords = [<?php foreach( $terms as $term ) : $i++; ?>"<?php print $term->name; ?>"<?php if ( $i != $count ) : ?>, <?php endif; ?><?php endforeach; ?>];</script>
    <?php wp_enqueue_script( 'smas-scripts', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'sell_media' ) ); ?>
    <?php print sell_media_advanced_search_form(); ?>
    <?php return ob_get_clean();
}
add_shortcode( 'sell_media_searchform_advanced', 'sell_media_search_shortcode_function' );