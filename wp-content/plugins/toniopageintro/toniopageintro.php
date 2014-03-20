<?php
/*
Plugin Name: toniopageintro
Plugin URI: 
Description: Adds some text fields to posts and pages
Version: 1.1
Author: Antonio Cordasco
Author URI: 
*/
	
	$GLOBALS['tpi_allowed_doc_types']=array('application/pdf', 'application/msword', 'application/vnd.ms-powerpoint', 'application/vnd.ms-excel', 'application/zip',  );
	
	

	
	$GLOBALS['tpi_fields'] = array(
	'tpi_posts_list_author'=>'',
	'tpi_book_author'=>'',
	'tpi_facebook_image'=>''
	
	);
	
	
	
	
	
	

	define('TPI_AUTHORS_ADMIN_PAGE_URL','/wp-admin/edit.php?page=tpi_authors_admin_page');
	define('TPI_PATH',WP_PLUGIN_DIR.'/toniopageintro/');
	define('TPI_RELATIVE_URL','/wp-content/plugins/toniopageintro/');

	require_once(TPI_PATH.'functions.php');


