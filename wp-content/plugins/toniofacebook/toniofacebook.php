<?php
/*
Plugin Name: toniofacebook
Plugin URI: 
Version: 1.0
Author: Antonio Cordasco
Author URI: 
*/

// if($_SERVER['REMOTE_ADDR'] != '86.1.70.197'){ $_SERVER['HTTP_REFERER']=''; unset($_GET['connect_to_facebook']); }

include(ABSPATH . 'wp-content/plugins/toniofacebook/settings.php');
include(ABSPATH . 'wp-content/plugins/toniofacebook/includes/functions.php');
include(ABSPATH . 'wp-content/plugins/toniofacebook/includes/hook_functions.php');
include(ABSPATH . 'wp-content/plugins/toniofacebook/includes/filter_functions.php');
include(ABSPATH . 'wp-content/plugins/toniofacebook/includes/debug_functions.php');
include(ABSPATH . 'wp-content/plugins/toniofacebook/includes/deprecated_functions.php');

// require_once ABSPATH . 'wp-content/plugins/toniofacebook/includes/facebook-php-sdk/src/facebook.php';


if($_SESSION == null){ session_start(); }  // $_SESSION=array(); die; // var_dump($_SERVER['HTTP_REFERER']); var_dump($_SESSION); var_dump($_GET); die;
// tfb_log_debug_info();


if(isset($_GET['error']) && $_GET['error']=='access_denied'){ 
	$_SESSION['tfb_acess_denied'] = true;
	setcookie('tfb_acess_denied', 'true');
	tfb_log_authorization($_SERVER['REMOTE_ADDR'],'false');
}elseif(
	!isset($_SESSION['tfb_acess_denied']) 
	&& !isset($_COOKIE['tfb_acess_denied']) 
	&& (($_SERVER['HTTP_REFERER'] && strpos($_SERVER['HTTP_REFERER'], 'facebook.com')) || isset($_GET['connect_to_facebook']) || isset($_GET['code']))
)
{  // echo $_SERVER['HTTP_REFERER']; die;
	add_action('wp_head', 'tfb_connect');		
}
// add_action('wp_head', 'tfb_connect');	// uncomment to force connection to the facebook app


add_action('wp_head', 'tfb_add_js');
add_action('wp_head', 'tfb_check_ajax_request');




// add_action('wp_head', 'tfb_fetch_post_coments');	// deprecated

add_filter('get_avatar', 'tfb_get_avatar',10,5);
add_filter('comments_array','tfb_add_facebok_post_comments'); 

