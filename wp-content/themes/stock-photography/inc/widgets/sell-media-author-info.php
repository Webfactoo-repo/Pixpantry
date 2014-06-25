<?php class Sell_Media_Author_Info_Widget extends WP_Widget
{
    function Sell_Media_Author_Info_Widget(){
		$widget_ops = array('description' => 'Displays Sell Media Author Info');
		$control_ops = array('width' => 200, 'height' => 200);
		parent::WP_Widget(false,$name='Sell Media Author Info',$widget_ops,$control_ops);
    }

	/* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		extract($args);
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;
?>
		<div class="sell-media-authorinfo-widget">

			<?php
			$author_id = get_the_author_meta( "ID" );
			$user = get_userdata(intval($author_id));
			$user_avatar = get_avatar($user->ID, 512);
			?>

			<div class="author-wrap">
				<a href="<?php echo esc_url( home_url( '/?post_type=sell_media_item&author=' ) ) . $user->ID; ?>" class="author-image"><?php echo $user_avatar; ?></a>
				<div class='author-info'>
					<ul class='author-details'>
						<li class='author-info-name'><h3><?php echo $user->display_name; ?></h3></li>
						<?php if ( ! empty($user->position)) { ?>
						<li class='author-info-position'><?php echo $user->position; ?></li>
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

		</div><!-- .sell-media-authorinfo-widget -->

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
		$instance = wp_parse_args( (array) $instance, array('title'=>'About Author') );
		$title = htmlspecialchars($instance['title']);

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';

	}

}

function Sell_Media_Author_Info_WidgetInit() {
	register_widget('Sell_Media_Author_Info_Widget');
}

add_action('widgets_init', 'Sell_Media_Author_Info_WidgetInit');
?>