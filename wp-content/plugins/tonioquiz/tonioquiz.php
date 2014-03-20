<?php
/*
Plugin Name: tonioquiz
Plugin URI: 
Version: 1.0
Author: Antonio Cordasco
Author URI: 
*/
	
	$GLOBALS['tq_allowed_doc_types']=array('application/pdf', 'application/msword', 'application/vnd.ms-powerpoint', 'application/vnd.ms-excel', 'application/zip',  );
	
	

	
	$GLOBALS['tq_fields'] = array(
	'tq_posts_list_author'=>'',
	
	
	);
	
	
	
	
	
	


	define('TQ_PATH',WP_PLUGIN_DIR.'/tonioquiz/');

	require_once(TQ_PATH.'functions.php');

	
	
	
	
	
	
	

	add_action('admin_menu','tq_add_admin_pages');
	
	
	function tq_add_admin_pages()
	{
	

		$curr_usr = wp_get_current_user();
		if(in_array('administrator',$curr_usr->roles))
		{			
			add_posts_page('Quiz questions', 'Quiz questions', 8, 'tq_questions_admin_page', 'tq_questions_admin_page');
			add_posts_page('Quizzes', 'Quizzes', 8, 'tq_quizzes_admin_page', 'tq_quizzes_admin_page');
		}
	}
	
	
	function tq_questions_admin_page()
	{
		if(isset($_GET['tq_item_id'])){ 
			include(TQ_PATH.'forms/edit-question.php'); 
		}else{
			include(TQ_PATH.'listings/questions.php'); 
		}
		
	}
	
	function tq_quizzes_admin_page()
	{
		if(isset($_GET['tq_quiz_id'])){ 
		
		}else{
			include(TQ_PATH.'listings/quizzes.php'); 
		}
		
	}


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	