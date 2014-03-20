<?php


	add_action('admin_head','tq_add_js');
	function tq_add_js()
	{ 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/js/tq_functions.php"></script>'; 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/js/lib/tablesorter/jquery.tablesorter.min.js"></script>'; 
	}
	add_action('wp_head','tq_add_frontend_js_css');
	function tq_add_frontend_js_css()
	{ 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/js/frontend/functions.js?v=2"></script>'; 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/js/frontend/jquery.magnifier.js"></script>'; 
		echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/css/frontend/main.css" type="text/css" media="all" />'."\n";
	}
	
	
	
	
	add_action('admin_head','tq_add_css');
	function tq_add_css()
	{ 
		echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/css/main.css" type="text/css" media="all" />'."\n";
		echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/tonioquiz/js/lib/tablesorter/themes/blue/style.css" type="text/css" media="all" />'."\n";
	}

	
	add_action('edit_page_form','tq_edit_page_form');
	function tq_edit_page_form(){  }
	
	
	add_action('edit_form_advanced','tq_edit_post_form');
	function tq_edit_post_form(){ include(TQ_PATH.'forms/edit-post.php'); }
	
	
	
	add_action('edit_post','tq_edit_post');
	function  tq_edit_post($vars)
	{  	
		if(isset($_POST['tq_posts_list_author']) )
		{ 				
			$ID=intVal($_POST['ID']);
			
			if($ID>0)
			{ 
		//		foreach($_POST as $key => $val){ echo $key.'<br>'; var_dump($val); echo '<br>'; }	 exit;
			
				$sql = "DELETE FROM {$GLOBALS['table_prefix']}postmeta WHERE post_id='{$ID}' AND ( meta_key LIKE 'tq_%' OR meta_key LIKE 'tquiz_%')";				
				$res = $GLOBALS['wpdb']->get_results($sql);

				foreach($GLOBALS['tq_fields'] as $key => $val)
				{ 
					if(isset($_POST[$key]))
					{				
						$sql = "INSERT INTO {$GLOBALS['table_prefix']}postmeta (post_id,meta_key,meta_value)VALUES('{$ID}','{$key}','{$_POST[$key]}')";							
						$res = $GLOBALS['wpdb']->get_results($sql);						
					}
				}  
			}
		}
		return $vars;
	}