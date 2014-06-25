<?php class Sell_Media_Author_Widget extends WP_Widget
{
    function Sell_Media_Author_Widget(){
		$widget_ops = array('description' => 'Displays more items by the same author');
		$control_ops = array('width' => 200, 'height' => 200);
		parent::WP_Widget(false,$name='Sell Media Items by Author',$widget_ops,$control_ops);
    }

	/* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		extract($args);
		echo $before_widget;

    	if ( $title )
    		echo $before_title . $title . get_the_author_meta( "display_name" ) . $after_title;

		global $post;

		$args = array( 'post_type' => 'sell_media_item', 'field'=>'slug', 'posts_per_page' => '6', 'author' => get_the_author_meta( 'ID' ), 'order' => 'DESC' ); ?>

		<div class="sell-media-author-widget">

			<?php
	        $type_posts = new WP_Query ( $args );
			?>
			<?php
			while ( $type_posts->have_posts() ) : $type_posts->the_post();

				global $post;
				?>
				<?php
				$sell_media_attachment_id = get_post_meta( $post->ID, '_sell_media_attachment_id', true );
				if ( $sell_media_attachment_id ){
					$attachment_id = $sell_media_attachment_id;
				} else {
					$attachment_id = get_post_thumbnail_id( $post->ID );
				} ?>
				<div class="sell-media-widget-item-warp third">
					<div class="sell-media-widget-thumb-wrap">
						<a href="<?php echo get_permalink(); ?>">
							<?php sell_media_item_icon( $attachment_id, 'sell_media_item' ); ?>
						</a>
					</div>
				</div> <!--  .sell-media-widget-item-warp  -->

			<?php endwhile; wp_reset_postdata(); ?>

		</div><!-- .sell-media-popular-widget -->

<?php

    	echo $after_widget;

}
	/*Saves the settings. */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = stripslashes($new_instance['title']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'More from ') );
		$title = htmlspecialchars($instance['title']);

		 # Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';


	}

}

function Sell_Media_Author_WidgettInit() {
	register_widget('Sell_Media_Author_Widget');
}

add_action('widgets_init', 'Sell_Media_Author_WidgettInit');
?>