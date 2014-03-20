<?php
/*
Plugin Name: fbLikeButton
Plugin Script: fblikebutton.php
Plugin URI: http://healyourchurchwebsite.com/2010/04/22/the-facebook-like-button-plugin-for-wordpress/
Description: Allows you to configure and display the FaceBook Like Button before and/or after each post and/or page
Version: 0.4a
License: GPL
Author: Dean Peters
Author URI: http://HealYourChurchWebsite.com/

=== RELEASE NOTES ===
2010-04-25 - version 0.4a =
* update help documentation, and also get SVN to load CSS for brand-new preview feature

2010-04-25 - version 0.4 =
* add a preview section to the admin page so you can see changes as they occur

2010-04-25 - version 0.3 =
*resolve a reported issue with the layout option
*modify, extend some of the help text

2010-04-24 - version 0.2f =
*removed uneeded stuff 
*updated screenshots
*renamed screenshot files
*updated readme.txt to comply with SVN

2010-04-24 - version 0.2 =

*added display on home/front page option (thanks http://blog.nazab.com)
*added display on individual page option
*added display on individual post option
*tweaked some code for speed
*changed line 9 of fblike_content_filter.php get_page_link with get_permalink (thanks http://www.hi-d.ch/)


2010-04-24 - v0.2

*added display on home/front page option (thanks http://blog.nazab.com)
*added display on individual page option
*added display on individual post option
*tweaked some code for speed
*changed line 9 of fblike_content_filter.php get_page_link with get_permalink (thanks http://www.hi-d.ch/)

2010-04-22 - v0.1 - first version
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Online: http://www.gnu.org/licenses/gpl.txt
*/

include_once dirname(__FILE__).'/fblike_admin_options.php';
include_once dirname(__FILE__).'/fblike_content_filter.php';


/* -- init stuff -- */
register_activation_hook( __FILE__, 'fblike_activate' ); 
function fblike_activate() {
	$fbLikeAdminOptions = fblike_get_default_admin_options();
	update_option('fbLikeAdminOptions', $fbLikeAdminOptions);
}

add_action( 'init', 'fblike_init_localize' );
function fblike_init_localize() {
	// http://weblogtoolscollection.com/archives/2007/08/27/localizing-a-wordpress-plugin-using-poedit/
	$plugin_path = plugin_basename( dirname( __FILE__ ) .'/language' );
	load_plugin_textdomain( 'fblike', '', $plugin_path );

}

/* -- admin stuff -- */

add_action('admin_menu', 'fblike_admin_actions');
function fblike_admin_actions() {  
	$fblike_hook = add_options_page("FBLike Button Plugin Options", "FBLikeButton", 1, "FBLikeButtonAdminPageID", "fblike_admin");
	add_action( 'admin_head-'.$fblike_hook , 'fblike_admin_add_css' ); 
	add_action( 'admin_print_scripts-'.$fblike_hook, 'fblike_admin_add_js');
}  

function fblike_admin() {  
     include('fblike_admin_form.php');  
}

function fblike_admin_add_css() {  
	echo '<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/fblikebutton/fblike_admin.css" type="text/css" />';
}


function fblike_admin_add_js() {  
	wp_enqueue_script('fblike_admin', 
			WP_PLUGIN_URL."/fblikebutton/fblike_admin.js", 
			array('jquery','jquery-ui-core'));
}



?>
