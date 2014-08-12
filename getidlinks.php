<?php
$queryArr[]='SELECT ID from wp_posts where post_name="" and post_type="sell_media_item"';

foreach ($queryArr as $v) {
	$id = $wpdb->get_var($v);
	echo '<a href="http://pixpantry.com/wp-admin/post.php?post=' . $id . '&action=edit">http://pixpantry.com/wp-admin/post.php?post=' . $id . '&action=edit</a><br />';
}
?>