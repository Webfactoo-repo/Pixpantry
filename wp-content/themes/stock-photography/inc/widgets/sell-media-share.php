<?php class Sell_Media_Share_Widget extends WP_Widget
{
    function Sell_Media_Share_Widget(){
		$widget_ops = array('description' => 'Displays Sell Media Social Media Icons');
		$control_ops = array('width' => 200, 'height' => 200);
		parent::WP_Widget(false,$name='Sell Media Social Share',$widget_ops,$control_ops);
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
		<div class="sell-media-share-widget">

			<div id="twitter" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Tweet"></div>
			<div id="facebook" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Share"></div>

		</div><!-- .sell-media-share-widget -->

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
		$instance = wp_parse_args( (array) $instance, array('title'=>'Share') );
		$title = htmlspecialchars($instance['title']);

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';

	}

}

function Sell_Media_Share_WidgetInit() {
	register_widget('Sell_Media_Share_Widget');
}

add_action('widgets_init', 'Sell_Media_Share_WidgetInit');
?>