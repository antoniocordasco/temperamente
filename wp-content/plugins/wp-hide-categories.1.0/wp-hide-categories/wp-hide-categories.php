<?php
/*
Plugin Name: WP Hide Categories
Plugin URI: http://nxsn.com/
Description: Hides selected categories. You can manage this from your admin panel, so you don't need to open template.
Version: 1.0
Author: Huseyin Berberoglu
Author URI: http://nxsn.com
 */
add_filter('list_terms_exclusions', 'wphc_list_categories_excludes',1);
function wphc_list_categories_excludes( $exclude_array ) {
    global $is_wphc_admin;
    if (!is_admin()) {
        $wphc_options = get_option('wphc_options');
        $wphc_exclude = $wphc_options['excluded'];
        $wphc_array = ( $wphc_exclude ) ? explode(',', $wphc_exclude) : array();
        foreach ($wphc_array as $ex) {
            if ($ex)
            $exquery .= " AND t.term_id <> $ex ";
        }
        $exclude_array .= $exquery;
        //echo $exclude_array;
    }
    return $exclude_array;
}

/* for admin page */
function wphc_config() { include('wphc-admin.php'); }
function wphc_config_page() {
    if ( function_exists('add_submenu_page') )
        add_options_page(__('Hide Categories'), __('Hide Categories'), 'manage_options', 'wp-hide-categories', 'wphc_config');
}
add_action('admin_menu', 'wphc_config_page');
/* eof for admin page */
?>
