<?php
/*
Plugin Name: Tonio Cache
Plugin URI: 
Description: Database driven cache
Version: 
Author: Antonio Cordasco
Author URI: 
*/


include('includes/functions.php'); 
		
add_action('wp_set_comment_status','tc_expire_footer',10,2);

function tc_expire_footer($comment_id, $status) { 
	if($status == 'approve') {
		Tonio_Cache::expire('footer', false);
	}	
}

add_action('save_post','tc_expire_save_post',10,1);

function tc_expire_save_post($post) { 
	if (count($_POST)) {
		if ('page' == $_POST['post_type']) {
			Tonio_Cache::expire('supheader', false);
		} elseif ('post' == $_POST['post_type']) {
			Tonio_Cache::expire('home-posts', false);
			Tonio_Cache::expire('post-info', false);
		}
	}
}




class Tonio_Cache
{
    private static $caching_on = true;
	
	public static function get($namespace, $id)
	{
		if(!self::$caching_on) { return false; }
		
		$namespace = mysql_real_escape_string($namespace);
		$id = mysql_real_escape_string($id);
		$sql = "SELECT contents FROM wp_toniocache_elements WHERE namespace = '$namespace' AND id = '$id' AND expires > '" . date('Y-m-d H:i:s') . "' ORDER BY created DESC LIMIT 0,1; ";
		$res = $GLOBALS['wpdb']->get_results($sql);
		if($res) {
			return $res[0]->contents;
		} else {
			return false;
		}
	}
	
	public static function set($namespace, $id, $contents, $expiry)
	{
		if(!self::$caching_on) { return false; }
		
		$namespace = mysql_real_escape_string($namespace);
		$id = mysql_real_escape_string($id);
		$contents = mysql_real_escape_string($contents);
		$expires = date('Y-m-d H:i:s', time() + intval($expiry));
		$sql = "REPLACE INTO wp_toniocache_elements (namespace, id, contents, expires, created) VALUES ('$namespace',  '$id',  '$contents',  '$expires',  '" . date('Y-m-d H:i:s') . "'); ";
		return $GLOBALS['wpdb']->query($sql);
	}
	
	
	public static function expire($namespace, $id)
	{ 
		if(!self::$caching_on) { return false; }
		$sql = "DELETE FROM wp_toniocache_elements ";
		if($namespace) { 
			$sql .= " WHERE namespace = '".mysql_real_escape_string($namespace)."' "; 
			if($id) { $sql .= "AND id = '".mysql_real_escape_string($id)."' "; }
		}
		return $GLOBALS['wpdb']->query($sql);
	}
	
}


if(isset($_GET['tc_expire'])) {
	if($_GET['tc_expire'] == 'all') { // this will expire the whole cache and slow down the site for a while
		Tonio_Cache::expire(false, false);
	} elseif(in_array($_GET['tc_expire'], array('footer', 'header', 'home-posts', 'related-contents', 'supheader', 'post-info'))) {
		Tonio_Cache::expire($_GET['tc_expire'], false);
	}
}






	add_action('admin_menu','tc_add_admin_pages');
	
	
	function tc_add_admin_pages()
	{
		$curr_usr = wp_get_current_user();
		if(in_array('administrator',$curr_usr->roles))
		{			
			add_options_page('Cache admin', 'Cache admin', 8, 'tc_cache_admin_page', 'tc_cache_admin_page');
		}
	}
	function tc_cache_admin_page()
	{		
		include('includes/cache-admin.php'); 
	}

	
