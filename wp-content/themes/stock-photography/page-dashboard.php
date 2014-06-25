<?php
/*
 Template Name: Dashboard
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */


get_header(); ?>

		<div id="primary" class="content-area account-template">
			<div id="content" class="site-content" role="main">
				<?php if ( stock_photography_sell_media_check() ) { ?>

					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo get_edit_user_link(); ?>"><?php _e('Edit','stock_photography'); ?></a>
						<div id="customer-info">
							<h3><?php _e('Your Information: ', 'stock_photography'); ?><?php wp_get_current_user(); echo $current_user->user_firstname; ?> <?php echo $current_user->user_lastname; ?></h3>

							<ul class="customer-details">
								<?php
								echo '<li>' . __('Username: ', 'stock_photography') . $current_user->user_login . '</li>';
								echo '<li>' . __('Email: ', 'stock_photography') . $current_user->user_email . '</li>';
								echo '<li>' . __('First Name: ', 'stock_photography') . $current_user->user_firstname . '</li>';
								echo '<li>' . __('Last Name: ', 'stock_photography') . $current_user->user_lastname . '</li>';
								?>
							</ul>
						</div><!-- #customer-info -->

						<div id="purchase-history">
							<h3><?php _e('Purchase History: ', 'stock_photography'); ?></h3>
							<?php echo do_shortcode('[sell_media_download_list]'); ?>
						</div><!-- #purchase-history -->

					<?php else : ?>

						<p><?php _e( 'You must be logged into the view this page.', 'stock_photography' ); ?></p>
						<?php wp_login_form(); ?>
						<p><a href="<?php echo site_url( 'wp-login.php?action=lostpassword' ); ?>"><?php _e( 'Lost your password?', 'stock_photography' ); ?></a></p>

					<?php endif; ?>

				<?php } else { ?>
						<?php _e('Please activate Sell Media plugin to use this page.', 'stock_photography'); ?>
				<?php } ?>
			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php get_sidebar(); ?>
<?php endif; ?>
<?php get_footer(); ?>