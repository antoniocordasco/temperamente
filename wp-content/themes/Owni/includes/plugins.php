<?php
/*
Plugin Name: Simple Recent Comments
Plugin URI: http://www.g-loaded.eu/2006/01/15/simple-recent-comments-wordpress-plugin/
Description: Shows a list of recent comments.
Version: 0.1.2
Author: GNot
Author URI: http://www.g-loaded.eu/
*/

/*
License: GPL
Compatibility: All
*/
function src_simple_recent_comments($src_count = 8, $src_length=35, $pre_HTML='', $post_HTML='') {
	global $wpdb;
	
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, 
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt 
		FROM $wpdb->comments 
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) 
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
		ORDER BY comment_date_gmt DESC 
		LIMIT $src_count";
	$comments = $wpdb->get_results($sql);

	$output = $pre_HTML;
	foreach ($comments as $comment) {
		$output .= "\n\t<li><span>" . $comment->comment_author . ':</span> <a href="' . get_permalink($comment->ID) . '#comment-' . $comment->comment_ID  . '" title="on ' . get_the_title($comment->ID) . '">' . strip_tags($comment->com_excerpt) . '...</a></li>';
	}
	$output .= $post_HTML;
	
	echo $output;

}

/*
Plugin Name: Recent Posts
Plugin URI: http://mtdewvirus.com/code/wordpress-plugins/
Description: Returns a list of the most recent posts.
Version: 1.1.3
Author: Nick Momrik
Author URI: http://mtdewvirus.com/
*/

function mdv_recent_posts($no_posts = 8, $before = '<li>', $after = "</li>\n", $hide_pass_post = true, $skip_posts = 0) {
	global $wpdb;
	$time_difference = get_settings('gmt_offset');
	$now = gmdate("Y-m-d H:i:s",time());
	$request = "SELECT ID, post_title, DATE_FORMAT(post_date_gmt, '%d %M %Y') as my_date FROM $wpdb->posts WHERE post_status = 'publish' ";
	if($hide_pass_post) $request .= "AND post_password ='' ";
	$request .= "AND post_type='post' ";
	$request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
	$posts = $wpdb->get_results($request);
	$output = '';
	if($posts) {
		foreach ($posts as $post) {
			$permalink = get_permalink($post->ID);
			//$output .= $before .'<span>' .$post->my_date. '</span> - <a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . htmlspecialchars($post_title, ENT_COMPAT) . '">' . htmlspecialchars($post_title). '</a>';
			$output .= $before .'<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . htmlspecialchars($post_title, ENT_COMPAT) . '">' . get_the_title($post->ID). '</a>';
			$output .= $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	echo $output;
}
?>