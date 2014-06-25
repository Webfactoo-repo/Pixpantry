<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Stock Photography
 * @since Stock Photography 1.0
 */
?>

	</div><!-- #main .site-main .container -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div id="footer-inner" class="container">

			 <?php if ( is_active_sidebar( 'footer-widget-1' ) || is_active_sidebar( 'footer-widget-2' ) || is_active_sidebar( 'footer-widget-3' ) ) : ?>
		            <div id="footer-widgets" <?php stock_photography_footer_widget_class(); ?>>
		                <?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
		                    <aside id="widget-1" class="widget-1">
		                        <?php dynamic_sidebar( 'footer-widget-1' ); ?>
		                    </aside>
		                <?php endif; ?>
		                <?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
		                    <aside id="widget-2" class="widget-2">
		                        <?php dynamic_sidebar( 'footer-widget-2' ); ?>
		                    </aside>
		                <?php endif; ?>
		                <?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
		                    <aside id="widget-3" class="widget-3">
		                        <?php dynamic_sidebar( 'footer-widget-3' ); ?>
		                    </aside>
		                <?php endif; ?>
		            </div><!-- end #footer-widgets -->
		        <?php endif; // end check if any footer widgets are active ?>

		</div><!-- #footer-inner .container -->
		<div id="site-info-wrap">
			<div class="site-info container">Pixpantry 2014 (c) All rights reserved | Proudly created by <a href="http://www.webfactoo.com/">Webfactoo</a>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>