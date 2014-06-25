<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php global $theme_options; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php if ( ! empty( $theme_options['favicon'] ) ) { ?>
	<link rel="shortcut icon" href="<?php echo esc_url( $theme_options['favicon'] ); ?>" />
<?php } ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner"<?php stock_photography_header_image(); ?>>
		<div id="nav-wrapper">
			<nav role="navigation" class="top-navigation container">
				<h1 class="assistive-text"><span class="genericon genericon-menu"></span> <?php _e( 'Top Menu', 'stock_photography' ); ?></h1>
				<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'stock_photography' ); ?>"><?php _e( 'Skip to content', 'stock_photography' ); ?></a></div>
				<?php wp_nav_menu( array( 'theme_location' => 'top' ) ); ?>
			</nav><!-- .top-navigation -->
		</div><!-- #nav-wrapper -->
		<div id="header-inner" class="container">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			    	<?php if ( ! empty( $theme_options['logo'] ) ) : ?>
			    	<img class="sitetitle" src="<?php echo esc_url( $theme_options['logo'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
			    	<?php else : ?>
			    		<?php bloginfo( 'name' ); ?>
			    	<?php endif; ?>
		    	</a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<?php
			if ( class_exists( 'SellMedia' ) && function_exists( 'sell_media_advanced_search' ) ) {
				echo '<div id="header-smas" style="display:none">';
				echo do_shortcode( '[sell_media_searchform_advanced]' );
				echo '</div>';
			} elseif ( class_exists( 'SellMediaSearch' ) ) {
				$settings = sell_media_get_plugin_options();
				echo Sell_Media()->search->form( get_permalink( $settings->search_page ) );
			} else {
				get_search_form();
			} ?>
		</div>
		<div id="main-nav-wrapper" class="site-nav">
			<nav id="site-navigation" role="navigation" class="site-navigation-main main-navigation container">
				<h1 class="menu-toggle"><span class="genericon genericon-menu"></span> <?php _e( 'Menu', 'stock_photography' ); ?></h1>
				<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'stock_photography' ); ?>"><?php _e( 'Skip to content', 'stock_photography' ); ?></a></div>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
				<?php if ( stock_photography_sell_media_check() ) : ?>
					<span class="menu-cart-info" style="display:none;">
						&#40;<span class='menu-cart-items sellMediaCart_quantity'></span>&#41;
						<span class="menu-cart-total-wrap">
							<span class='menu-cart-total sellMediaCart_total'></span>
						</span>
					</span>
				<?php endif; ?>
			</nav><!-- .site-navigation .main-navigation -->
		</div><!-- #main-nav-wrapper -->
	</header><!-- #masthead .site-header -->

	<div id="main" class="site-main container">