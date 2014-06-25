<?php
/**
 * Homepage template file.
 *
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php if ( is_active_sidebar( 'homepage-top' ) ) : ?>
			<section id="homewidgets-top" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'homepage-top' ); ?>
			</section><!-- #homewidgets-top .widget-area -->
		<?php endif; ?>

		<?php if ( stock_photography_sell_media_check() ) { ?>
			<div class="products-wrap">
				<h2 class="widget-title center"><?php _e( 'Collections', 'stock_photography' ); ?></h2>
				<?php get_template_part('loop-sell-media'); ?>
			</div>
		<?php } else { ?>
			<?php get_template_part('loop-blog'); ?>
		<?php } ?>

		<?php if ( is_active_sidebar( 'homepage-bottom' ) ) : ?>
			<section id="homewidgets-bottom" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'homepage-bottom' ); ?>
			</section><!-- #homewidgets-bottom .widget-area -->
		<?php endif; ?>

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>