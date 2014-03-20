<?php


	define('TQ_PATH',ABSPATH.'wp-content/plugins/tonioquiz/');
	define('TQ_URL','/wp-content/plugins/tonioquiz/');
	define('TQ_QUESTIONS_ADMIN_PAGE_URL','/wp-admin/edit.php?page=tq_questions_admin_page');
	define('TQ_QUIZZES_ADMIN_PAGE_URL','/wp-admin/edit.php?page=tq_quizzes_admin_page');
	
	define('TQ_NUM_CHOICES',4);
	
	
	$GLOBALS['tq'] = array();
	
	$GLOBALS['tq']['image_posts_rows'] = $GLOBALS['wpdb']->get_results("SELECT * FROM wp_posts WHERE (post_mime_type='image/jpeg' OR post_mime_type='image/pjpeg') AND post_status='inherit' ORDER BY post_title ASC");
		
	$GLOBALS['tq']['quiz_category_id'] = 753;