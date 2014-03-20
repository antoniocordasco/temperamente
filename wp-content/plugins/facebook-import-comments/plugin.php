<?php
/*
Plugin Name: Facebook Comments to WordPress
Plugin URI: http://devcorner.georgievi.net/pages/wordpress/wp-plugins/facebook-import-comments
Description: Automatically and transparently import blog page comments from Facebook comments social plugin into WordPress comments.
Version: 1.4
Author: Ivan Georgiev
Author URI: http://devcorner.georgievi.net/pages/wordpress/wp-plugins
License: GPL2
*/
/*
Copyright 2011 Ivan Georgiev  (email : baobab@abv.bg)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/** Just a test */
include("classes/FacebookCommentImporter.php");
add_action('init', create_function('', 'new com_bul7_wp_plugin_FacebookCommentImporter();'));
if (false) {
  echo "Hi";
}
?>