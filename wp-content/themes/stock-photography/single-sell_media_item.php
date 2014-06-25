<?php
/**
 * The Template for displaying all single sell media items.
 *
 * @package Stock Photography
 * @since 0.1
 */
get_header(); ?>

<div id="sell-media-single" class="sell-media">
	<div id="content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="sell-media-content">
			<?php sell_media_item_icon( get_post_meta( $post->ID, '_sell_media_attachment_id', true ), 'large' ); ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div><?php the_content(); ?></div>
			<?php do_action( 'sell_media_single_bottom_hook' ); ?>
		</div>

		<div class="sell-media-meta">
			<?php sell_media_item_buy_button( $post->ID, 'button sell-media-buy-button-success', 'Purchase' ); ?>
			<?php if ( function_exists('sell_media_item_min_price')) : ?>
				<?php $min_price = sell_media_item_min_price( $post->ID, false ); ?>
				<?php if ( ! empty( $min_price ) ) { ?>
					<div class="sell-media-meta-price">
						<?php _e( 'Starting at', 'sell_media' ); ?>:
						<?php echo sell_media_get_currency_symbol() . $min_price; ?>
					</div>
				<?php } ?>
			<?php endif; ?>
			<p class="sell-media-lightbox"><a href="javascript:void(0);" title="<?php _e( 'Lightbox', 'sell_media' ); ?>" class="add-to-lightbox" id="lightbox-<?php echo $post->ID; ?>" data-id="<?php echo $post->ID; ?>"><span class="genericon genericon-medium genericon-category"></span> <?php _e( 'Save to lightbox', 'sell_media' ); ?></a></p>
			<ul>
				<li class="filename"><span class="title"><?php _e( 'File ID', 'sell_media' ); ?>:</span> <?php echo get_the_id(); ?></li>
            <?php
			    $attachMeta = get_post_meta($post->ID);
				$imgDataa = getimagesize(WP_CONTENT_DIR . "/uploads/sell_media/" . $attachMeta[_sell_media_attached_file][0]) ;

//				$imgDatta = get_post_meta( $post->ID, '_sell_media_attachment_id', true );
//				$imgDattaa = wp_get_attachment_image_src( $imgDatta, 'full' );
//				print_r($imgDataa);
            ?>
                <li>
                    <span class="title">Width:</span> <?php echo $imgDataa[0]?> px
                <li>
                    <span class="title">Height:</span> <?php echo $imgDataa[1]?> px
				<li>
					<span class="title"><?php _e( 'Creator', 'sell_media' ); ?>:</span>
					<?php
						if( true == stock_photography_sell_media_item_has_taxonomy_terms( $post->ID, 'creator' ) ) {
							$creators = wp_get_post_terms( $post->ID, 'creator' );
							$creatorlist = "";
							foreach ( $creators as $creator ) {
								$creatorlist .= $creator->name . ", ";
							}
							echo rtrim( $creatorlist, ", " );
						} else {
					?>
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="collection"><?php echo get_the_author(); ?></a>
					<?php } ?>
				</li>

				<?php if ( true == stock_photography_sell_media_item_has_taxonomy_terms( $post->ID, 'collection' ) ) { ?>
					<li class="collections"><span class="title"><?php _e( 'Collections', 'sell_media' ); ?>:</span> <?php sell_media_collections( $post->ID ); ?></li>
				<?php } ?>


				<?php if ( true == stock_photography_sell_media_item_has_taxonomy_terms( $post->ID, 'keywords' ) ) { ?>
					<li class="keywords"><span class="title"><?php _e( 'Keywords', 'sell_media' ); ?>:</span> <?php stock_photography_sell_media_image_keywords( $post->ID ); ?></li>
				<?php } ?>
			</ul>

			<p class="like">
			<a id="like-<?php the_ID(); ?>" class="like-count genericon genericon-medium genericon-star" href="#" <?php stock_photography_liked_class(); ?>>
				<?php stock_photography_post_liked_count(); ?></a>
			<?php _e( ' people like this', 'sell_media' ); ?></p>


			<?php if ( is_active_sidebar( 'sell-media-sidebar' ) ) : ?>
				<aside id="sell-media-sidebar" class="sell-media-sidebar">
					<?php dynamic_sidebar( 'sell-media-sidebar' ); ?>
				</aside>
			<?php endif; ?>
		</div><!-- .sell-media-meta -->

	<?php endwhile; ?>
	</div><!-- #content -->
</div><!-- #sell_media-single .sell_media -->

<?php get_footer(); ?>