<?php
/*
 Template Name: Collections
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

get_header(); ?>

<div id="primary" class="content-area collections-template">
	<div id="content" class="site-content" role="main">

		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->
		
		<?php if ( stock_photography_sell_media_check() ) : ?>
			<?php get_template_part('loop-sell-media'); ?>
		<?php else : ?>
			<?php _e( 'Please activate Sell Media plugin to use this page.', 'stock_photography' ); ?>
		<?php endif; ?>

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php get_sidebar(); ?>
<?php endif; ?>
<?php get_footer(); ?>