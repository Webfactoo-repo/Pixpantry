<?php
/*
 Template Name: Lightbox
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', 'page' ); ?>

                <?php if ( stock_photography_sell_media_check() ) { ?>
                    <?php echo do_shortcode( '[sell_media_lightbox]' ); ?>
                <?php } else { ?>
                    <?php _e( 'Please activate Sell Media plugin to use this page.', 'stock_photography' ); ?>
                <?php } ?>

                <?php comments_template( '', true ); ?>

            <?php endwhile; // end of the loop. ?>

        </div><!-- #content .site-content -->
    </div><!-- #primary .content-area -->

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <?php get_sidebar(); ?>
<?php endif; ?>
<?php get_footer(); ?>