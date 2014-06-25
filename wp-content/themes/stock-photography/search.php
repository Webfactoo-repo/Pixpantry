<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

get_header(); ?>

		<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'stock_photography' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if ( 'sell_media_item' == get_post_type() ) { ?>
							<div class="sell-media-grid third sell-media-grid-search">
								<?php sell_media_item_buy_button( $post->ID, 'text', 'Buy' ); ?>
								<?php
								//Get Post Attachment ID
								$sell_media_attachment_id = get_post_meta( $post->ID, '_sell_media_attachment_id', true );
								if ( $sell_media_attachment_id ){
									$attachment_id = $sell_media_attachment_id;
								} else {
									$attachment_id = get_post_thumbnail_id( $post->ID );
								}
								?>
								<a href="<?php the_permalink(); ?>"><?php sell_media_item_icon( $attachment_id, 'sell_media_item' ); ?></a>

								<div class="sell-media-item-details">
									<div class="item-inner">
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<a id="like-<?php the_ID(); ?>" class="like-count genericon genericon-small genericon-star" href="#" <?php stock_photography_liked_class(); ?>>
											<?php stock_photography_post_liked_count(); ?>
								        </a>
									</div>
								</div>
							</div><!-- .sell-media-grid -->
						<?php } else { ?>


						<header class="entry-header">
							<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'stock_photography' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

							<?php if ( 'post' == get_post_type() ) : ?>
							<div class="entry-meta">
								<?php stock_photography_posted_on(); ?>
							</div><!-- .entry-meta -->
							<?php endif; ?>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<?php the_excerpt(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'stock_photography' ), 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
					<?php } ?>
					</article><!-- #post-<?php the_ID(); ?> -->

				<?php endwhile; ?>

				<?php stock_photography_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'search' ); ?>

			<?php endif; ?>

			</div><!-- #content .site-content -->
		</section><!-- #primary .content-area -->

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php get_sidebar(); ?>
<?php endif; ?>
<?php get_footer(); ?>