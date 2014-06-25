<?php
/*
 Template Name: Contributors
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header><!-- .entry-header -->

				<div class="team-page-grid">
				<?php
				    $blogusers = get_users();
				    foreach ($blogusers as $user) {

						if ( $user->display_archive == '1' ) {

							$user_avatar = get_avatar($user->ID, 512);
							?>

							<div class="author-wrap third">
								<a href="<?php echo esc_url( home_url( '/?author=' ) ) . $user->ID; ?>" class="author-image"><?php echo $user_avatar; ?></a>
								<div class='author-info'>
	 								<ul class='author-details'>
										<li class='author-info-name'><h3><?php echo $user->display_name; ?></h3></li>
										<?php if ( ! empty($user->position)) { ?>
										<li class='author-info-position'><?php echo $user->position; ?></li>
										<?php } ?>
										<?php if ( ! empty($user->description)) { ?>
											<li class='author-info-bio'><?php echo $user->description; ?></li>
										<?php } ?>
										<?php if ( ! empty($user->user_url)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->user_url; ?>' target='_blank'><div class="genericon genericon-small genericon-link"></div></a>
											</li>
										<?php } ?>
										<?php if ( ! empty($user->twitter)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->twitter; ?>' target='_blank'><div class="genericon genericon-small genericon-twitter"></div></a>
											</li>
										<?php } ?>
										<?php if ( ! empty($user->facebook)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->facebook; ?>' target='_blank'><div class="genericon genericon-small genericon-facebook"></div></a>
											</li>
										<?php } ?>
										<?php if ( ! empty($user->googleplus)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->googleplus; ?>' target='_blank'><div class="genericon genericon-small genericon-googleplus"></div></a>
											</li>
										<?php } ?>
										<?php if ( ! empty($user->youtube)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->youtube; ?>' target='_blank'><div class="genericon genericon-small genericon-youtube"></div></a>
											</li>
										<?php } ?>
										<?php if ( ! empty($user->vimeo)) { ?>
											<li class="author-social">
												<a href='<?php echo $user->vimeo; ?>' target='_blank'><div class="genericon genericon-small genericon-vimeo"></div></a>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						<?php }
					}
				?>
				</div><!-- .author-grid -->
			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php get_sidebar(); ?>
<?php endif; ?>
<?php get_footer(); ?>