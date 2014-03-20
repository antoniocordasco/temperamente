<?php
/*
Plugin Name: tonionewsletter
Plugin URI: 
Description: 
Version: 1.0
Author: Antonio Cordasco
Author URI: 
*/


require_once(ABSPATH.'wp-content/plugins/tonionewsletter/globals.php');

require_once(TONIONEWSLETTER_PATH.'functions.php');




	add_action('admin_menu','tn_add_admin_pages');
	
	
	function tn_add_admin_pages()
	{
	

		$curr_usr = wp_get_current_user();
		if(in_array('administrator',$curr_usr->roles))
		{			
			add_posts_page('Newsletters', 'Newsletters', 8, 'tn_admin_page', 'tn_admin_page');
		}
	}


	function tn_admin_page()
	{
		if(isset($_GET['tn_item_id'])){ 
			include(TONIONEWSLETTER_PATH.'forms/edit-newsletter.php'); 
		}else{
			include(TONIONEWSLETTER_PATH.'listings/newsletters.php'); 
		}
		
	}

function tn_css() {
  echo '<link rel="stylesheet" href="'.TONIONEWSLETTER_URL.'css/main.css" type="text/css" />';
}

add_action('admin_head','tn_css');
