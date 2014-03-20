<?php



	add_action('admin_menu','tpi_add_admin_pages');	
	
	function tpi_add_admin_pages()
	{
	

		$curr_usr = wp_get_current_user();
		if(in_array('administrator',$curr_usr->roles))
		{			
			add_posts_page('Book authors', 'Book authors', 8, 'tpi_authors_admin_page', 'tpi_authors_admin_page');
		}
	}

	
	
	function tpi_get_book_author($id)
	{
		global $wpdb;
		if(intVal($id)>0)
		{			
			$rows = $wpdb->get_results('SELECT * FROM wp_tpi_book_author WHERE id = '.intVal($id));
			if(isset($rows[0]))
			{
				return $rows[0];
			}
		}	
		return false;	
	}
	function tpi_get_book_author_by_folder($folder_name)
	{
		global $wpdb;
			
		$rows = $wpdb->get_results("SELECT * FROM wp_tpi_book_author WHERE folder_name = '".mysql_real_escape_string($folder_name)."'; ");
		if(isset($rows[0]))
		{
			return $rows[0];
		}			
		return false;	
	}
	
	
	function tpi_create_author($formValues)
	{	
		global $wpdb;	
		unset($formValues['id']);
		$formValues['created_by'] = $GLOBALS['current_user']->ID;		
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;		
		$formValues['created'] = date('Y-m-d h:i:s');
		$formValues['updated'] = date('Y-m-d h:i:s');		
		
		$wpdb->insert('wp_tpi_book_author', stripslashes_deep($formValues));
		$qzid = $wpdb->insert_id;
		if($qzid>0){ return true; }
		return false;
	}
	
	function tpi_update_author($formValues)
	{	
		global $wpdb;	
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;	
		$formValues['updated'] = date('Y-m-d h:i:s');
		
		$where = array('id' => intVal($formValues['id']));
		$wpdb->update('wp_tpi_book_author', stripslashes_deep($formValues),$where);
		
		return true;		
	}
	
	
	
	
	
	
	

	function tpi_authors_admin_page()
	{
		if(isset($_GET['tpi_authors_item_id'])){ 
			include(TPI_PATH.'forms/edit-book-author.php'); 
		}else{
			include(TPI_PATH.'listings/book-authors.php'); 
		}
		
	}













	add_action('admin_head','tpi_add_js');
	function tpi_add_js()
	{ 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/toniopageintro/js/tpi_functions.js"></script>'; 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/toniopageintro/js/jquery.datePicker.js"></script>'; 
		echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/toniopageintro/js/date.js"></script>'; 
	}
	
	add_action('admin_head','tpi_add_css');
	function tpi_add_css()
	{ 
		echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/toniopageintro/css/tpi.css" type="text/css" media="all" />'."\n";
		echo '<link rel="stylesheet" href="'.get_bloginfo('url').'/wp-content/plugins/toniopageintro/css/datePicker.css" type="text/css" media="all" />'."\n";
	}

	
	add_action('edit_page_form','tpi_edit_page_form');
	function tpi_edit_page_form(){ include(TPI_PATH.'forms/edit-page.php'); }
	
	
	add_action('edit_form_advanced','tpi_edit_post_form');
	function tpi_edit_post_form(){ include(TPI_PATH.'forms/edit-post.php'); }
	
	
	
	
	
	
	add_action('edit_post','tpi_edit_post');
	function  tpi_edit_post($vars)
	{ 
		if(isset($_POST['tpi_posts_list_author']) ||  isset($_POST['tpi_book_author']))
		{ 		
			
			$ID=intVal($_POST['ID']);
			
			if($ID>0)
			{ 
		//		foreach($_POST as $key => $val){ echo $key.'<br>'; var_dump($val); echo '<br>'; }	 exit;
			
				$sql = "DELETE FROM {$GLOBALS['table_prefix']}postmeta WHERE post_id='{$ID}' AND ( meta_key LIKE 'tpi_%' OR meta_key LIKE 'tquiz_%')";				
				$res = $GLOBALS['wpdb']->get_results($sql);

				foreach($GLOBALS['tpi_fields'] as $key => $val)
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
	
	
	
	
	
	
	
	
	
	
	
	add_action('wp_head', 'tpi_meta_tags');
	function tpi_meta_tags()
	{
		global $post;
		if(is_single() || is_page()) // this should be anywhere except the homepage
		{
			$description = $post->excerpt;
			if(isset($post->tpi_meta_description) && $post->tpi_meta_description!=''){ $description = $post->tpi_meta_description; }
			if($description != '')
			{
				echo "\n".'<meta name="description" content="'.$description.'" />'."\n\n";
			}
			$article_tags = wp_get_object_terms($post->ID, 'post_tag');
			$keywords = array();
			foreach($article_tags as $tmp)
      { 
        if(!isset($keywords[str_replace('-',' ',$tmp->slug)]) && strlen(str_replace('-',' ',$tmp->slug))>2 ){ $keywords[str_replace('-',' ',$tmp->slug)] = str_replace('-',' ',$tmp->slug); }      
      }
      
      $title_tmp = preg_replace('/[^a-z\s\-]*/','', strtolower(the_title('','',false)) );
      $split1 = explode('-',$title_tmp);
      foreach($split1 as $tmp1)
      {
        if(!isset($keywords[$tmp1]) && strlen($tmp1)>2){ $keywords[$tmp1] = $tmp1; }  
        
        $split2 = explode(' ',$tmp1);
        foreach($split2 as $tmp2)
        { 
          if(!isset($keywords[$tmp2]) && strlen($tmp2)>2){ $keywords[$tmp2] = $tmp2; }
         
        }
      }
      
     
      
			if(count($keywords) > 0)
			{
				echo "\n".'<meta name="keywords" content="'.implode(', ',$keywords).'" />'."\n\n";
			}		
		
		
		}else{
	
	
		}
	}
	
	
	
	function tpi_geocode($q)
	{
		$return = array('lng'=>0,'lat'=>0);
		$url = "http://maps.google.com/maps/geo?q=".urlencode($q);
		
		$tempStr = file_get_contents($url); 
		$split = split('"coordinates":',$tempStr);
		if(count($split)>=2)
		{
			$tempStr = $split[1];
			$tempStr = preg_replace('/[^0-9,\.\-]/','',$tempStr);
			$split = split(',',$tempStr);
			
			if(count($split)>=2)
			{
				$return['lng'] = floatVal($split[0]);
				$return['lat'] = floatVal($split[1]);				
				return $return;
			}
		}
		return false;
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	add_action('profile_update','chelmsford_profile_update');	
	function chelmsford_profile_update($id)
	{ 
		$sql = "DELETE FROM wp_usermeta WHERE user_id='$id' AND ( meta_key='tpi_is_consultant'); ";
		$GLOBALS['wpdb']->query($sql);		
		
		
		if(isset($_POST['tpi_is_consultant'])){ $ins_val = $_POST['tpi_is_consultant']; }else{ $ins_val='no'; }
		
	//	die("INSERT INTO wp_usermeta (user_id,meta_key,meta_value) VALUES ('$id','tpi_is_consultant','$ins_val'); ");
		
		$GLOBALS['wpdb']->query("INSERT INTO wp_usermeta (user_id,meta_key,meta_value) VALUES ('$id','tpi_is_consultant','$ins_val'); ");
		
		return $id;
	}
	
	
	add_action('user_register','chelmsford_user_register');
	function chelmsford_user_register($id)
	{ 
	
		if(isset($_POST['tpi_is_consultant'])){ $ins_val = $_POST['tpi_is_consultant']; }else{ $ins_val='no'; }	
		$GLOBALS['wpdb']->query("INSERT INTO wp_usermeta (user_id,meta_key,meta_value) VALUES ('$id','tpi_is_consultant','$ins_val'); ");
		
		return $id;
	}	
	
	
	
	
	
	
	
	
	
	
	add_action('edit_user_profile','chelmsford_edit_user_form');
	function chelmsford_edit_user_form($user)
	{ 
		echo '<script type="text/javascript">'."\n";
		echo "jQuery(document).ready(function($){ \n";
		
		if(isset($user->data->tpi_is_consultant) && $user->data->tpi_is_consultant=='yes')
		{			
			echo "	$('#tpi_is_consultant').attr('checked','checked'); \n";
		}			
		
		echo '});'."\n";	
		echo '</script>'."\n";		
	}
	