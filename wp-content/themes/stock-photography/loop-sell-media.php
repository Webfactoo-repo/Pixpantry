<?php
    global $theme_options;

    if ( stock_photography_sell_media_check() ) {
        $settings = sell_media_get_plugin_options();
        $price = $settings->default_price;
    } else {
        $price = "0.00";
    }

?>
<div class="sell-media">
    <div id="main-collections" class="sell-media-grid-container">
        <?php

        $taxonomy = 'collection';
        $term_ids = array();
        foreach( get_terms( $taxonomy ) as $term_obj ){
            $password = sell_media_get_term_meta( $term_obj->term_id, 'collection_password', true );
            if ( $password ) $term_ids[] = $term_obj->term_id;
        }

        $args = array(
            'orderby' => 'name',
            'hide_empty' => true,
            'number' => get_option('posts_per_page '),
            'parent' => 0,
            'exclude' => $term_ids
        );

        $terms = get_terms( $taxonomy, $args );

        // Randomize Taxonomies
        shuffle( $terms );

        if ( ! empty( $terms ) ) :
            $i = 0;
            $count = count( $terms );

            foreach( $terms as $term ) :
                $args = array(
                    'post_status' => 'publish',
                    'taxonomy' => 'collection',
                    'field' => 'slug',
                    'term' => $term->slug,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'collection',
                            'field' => 'id',
                            'terms' => $term_ids,
                            'operator' => 'NOT IN'
                            )
                        )
                    );
                $posts = New WP_Query( $args );
                $post_count = $posts->found_posts;

                if ( $post_count != 0 ) : $i++; ?>
                    <div class="sell-media-grid third<?php if ( $i %3 == 0 ) echo ' end'; ?>">
                        <div class="item-inner sell-media-collection">
                            <a href="<?php echo get_term_link( $term->slug, $taxonomy ); ?>" class="collection">

                                <div class="item-overlay">
                                    <div class="collection-details">
                                        <span class="collection-count"><span class="count"><?php echo $post_count; ?></span><?php _e( ' images in ', 'stock_photography' ); ?><span class="collection"><?php echo $term->name; ?></span><?php _e(' collection', 'stock_photography'); ?></span>
                                        <span class="collection-price"><?php _e( 'Starting at', 'stock_photography' ); ?> <span class="price"><?php echo sell_media_get_currency_symbol(); ?><?php echo $price; ?></span></span>
                                    </div>
                                </div>
                                <?php
                                $args = array(
                                    'posts_per_page' => 1,
                                    'taxonomy' => 'collection',
                                    'field' => 'slug',
                                    'term' => $term->slug
                                );
                                $posts = New WP_Query( $args );
                                ?>

                                <?php foreach( $posts->posts as $post ) : ?>
                                    <?php
                                        $collection_attachment_id = sell_media_get_term_meta( $term->term_id, 'collection_icon_id', true );
                                        if ( ! empty ( $collection_attachment_id ) ) {
                                            echo wp_get_attachment_image( $collection_attachment_id, 'sell_media_item' );
                                        } else {
                                            sell_media_item_icon( $post->ID, 'sell_media_item' );
                                        }
                                    ?>
                                    <h3 class="collection-title"><?php echo $term->name; ?></h3>
                                <?php endforeach; ?>
                            </a>
                        </div><!-- .item-inner -->
                    </div><!-- .sell-media-grid -->
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; $i = 0; ?>
    </div><!-- .sell-media-grid-container -->
</div><!-- .sell-media -->
