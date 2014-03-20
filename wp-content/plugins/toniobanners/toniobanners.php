<?php
/*
Plugin Name: toniobanners
Plugin URI: 
Description: 
Version: 
Author: Antonio Cordasco
Author URI: 
*/
	define('TONIOBANNERS_MODE','loggedin');
	define('TONIOBANNERS_SIDEBAR_BANNER_POSITION',1);
	define('TONIOBANNERS_ADS_PAGE_ID',13696);
	$GLOBALS['toniobanners'] = array();
	
	function toniobanners_show_banners() {
		return (is_user_logged_in() || TONIOBANNERS_MODE == 'all');
	}
	
	
	add_action( 'dynamic_sidebar','toniobanners_sidebar');	 
	add_action('owni_related_contents','toniobanners_related_contents');
	
	 
	 
	$GLOBALS['toniobanners']['sidebar_current_position'] = 0; 
	function toniobanners_sidebar($val) {
		if(toniobanners_show_banners()) {
			if($GLOBALS['toniobanners']['sidebar_current_position'] == TONIOBANNERS_SIDEBAR_BANNER_POSITION) {
				echo '<li><a href="'.get_permalink(TONIOBANNERS_ADS_PAGE_ID).'"><img src="/wp-content/plugins/toniobanners/images/Banner1 - 200x200.png" alt="sponsor temperamente"/></a></li>';
			}
			$GLOBALS['toniobanners']['sidebar_current_position']++;
		}
	}
	
	
	function toniobanners_related_contents() {
		if(toniobanners_show_banners()) {	
			echo '<p id="related_contents_ads"><a href="'.get_permalink(TONIOBANNERS_ADS_PAGE_ID).'"><img src="/wp-content/plugins/toniobanners/images/Banner2 - 860x100.png" /></a></p>';
		}
	}