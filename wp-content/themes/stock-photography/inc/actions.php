<?php
/**
 * Stock Photography Actions
 * @package Stock Photography
 * @author Thad Allender
 */

// Add custom meta fields on user profile page
function gpp_add_custom_user_profile_fields( $user ) { ?>

	<h3><?php _e( 'Profile Information', 'stock_photography' ); ?></h3>
	<table class="form-table">
	<?php if ( current_user_can( 'manage_options' ) ) { ?>
		<tr>
			<th>
				<label for="contributor"><?php _e( 'Contributor', 'stock_photography' ); ?>
			</label></th>
			<td>
				<fieldset>
					<label for="contributor">
						<input type="checkbox" name="contributor" id="contributor" value="1" <?php if (esc_attr( get_the_author_meta( "contributor", $user->ID )) == "1") echo "checked"; ?> >
						<?php _e( 'Check to mark as contributor', 'stock_photography' ); ?></label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th>
				<label for="display_archive"><?php _e( 'Display on contributor page', 'stock_photography' ); ?>
			</label></th>
			<td>
				<fieldset>
					<label for="display_archive">
						<input type="checkbox" name="display_archive" id="display_archive" value="1" <?php if (esc_attr( get_the_author_meta( "display_archive", $user->ID )) == "1") echo "checked"; ?> />
						<?php _e( 'Check to display on contributor page', 'stock_photography' ); ?></label>
				</fieldset>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<th>
				<label for="position"><?php _e( 'Position', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your position.', 'stock_photography' ); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="twitter"><?php _e( 'Twitter', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your Twitter url', 'stock_photography' ); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="facebook"><?php _e( 'Facebook', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your Facebook url', 'stock_photography' ); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="googleplus"><?php _e( 'Google +', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="googleplus" id="googleplus" value="<?php echo esc_attr( get_the_author_meta( 'googleplus', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your Google + url', 'stock_photography' ); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="youtube"><?php _e( 'YouTube', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="youtube" id="youtube" value="<?php echo esc_attr( get_the_author_meta( 'youtube', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your YouTube url', 'stock_photography' ); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="vimeo"><?php _e( 'Vimeo', 'stock_photography' ); ?>
			</label></th>
			<td>
				<input type="text" name="vimeo" id="vimeo" value="<?php echo esc_attr( get_the_author_meta( 'vimeo', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Enter your Vimeo url', 'stock_photography' ); ?></span>
			</td>
		</tr>

	</table>
<?php }

function gpp_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;

	if ( current_user_can( 'manage_options' ) ) {
		update_user_meta( $user_id, 'contributor', isset( $_POST['contributor'] ) );
		update_user_meta( $user_id, 'display_archive', isset( $_POST['display_archive'] ) );
	}
	update_user_meta( $user_id, 'position', $_POST['position'] );
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
	update_user_meta( $user_id, 'googleplus', $_POST['googleplus'] );
	update_user_meta( $user_id, 'youtube', $_POST['youtube'] );
	update_user_meta( $user_id, 'vimeo', $_POST['vimeo'] );
	//update_user_meta( $user_id, 'display_home', isset( $_POST['display_home'] ) );
}

add_action( 'show_user_profile', 'gpp_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'gpp_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'gpp_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'gpp_save_custom_user_profile_fields' );


// WordPress 3.5 Gallery support
function gpp_gallery($attr) {

	global $theme_options;

    $post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery gpp-gallery flexslider galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	$output .= "<ul class='slides'>";
	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		$output .= "<li>";
		$output .= "$link";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<p class='flex-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</p>";

		}
		$output .= "</li>";
	}
	$output .= "</ul> <!-- .slides -->";

	if ( $theme_options['slideshow_thumbs'] == "yes" ) {

		$output .= "<ul class='thumbs-nav'>";

        foreach ( $attachments as $id => $attachment ) {
			$output .= "<li><a href='#'>" . wp_get_attachment_image( $id, 'thumbnail') . "</a></li>";
        }
		$output .= "</ul>";
	}
	$output .= "</div>\n";

	return $output;

}

/*
 * Replace the core WP gallery shortcode with our own
 */
function gpp_replace_gallery_shortcode() {

	remove_shortcode( 'gallery' );
	add_shortcode( 'gallery' , 'gpp_gallery' );

}
add_action( 'init', 'gpp_replace_gallery_shortcode' );

/*
 * Add Likes to Sell Media item overlay
 */
function stock_photography_sell_media_item_overlay(){
	echo '<a id="like-' . get_the_ID() . '" class="like-count genericon genericon-small genericon-star" href="#" ' . stock_photography_liked_class() . '>';
	stock_photography_post_liked_count();
	echo '</a>';
}
add_action( 'sell_media_item_overlay', 'stock_photography_sell_media_item_overlay' );

/*
 * Add Sidebar if active
 */
function stock_photography_sm_before_footer(){
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		get_sidebar();
	}
}
add_action( 'sell_media_before_footer', 'stock_photography_sm_before_footer' );