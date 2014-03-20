	<div id="supheader">
<?php 
	
	if(is_home()) {
		$cache_id = 'home';
	} elseif(is_page()) {
		$cache_id = 'page-id-' . $post->ID;
	} else {
		$cache_id = 'general';
	}
	if($supheader_output = Tonio_Cache::get('supheader', $cache_id)) {
		echo $supheader_output;
	} else {
		$top_pages = get_pages('parent=0&sort_column=menu_order&depth=1&exclude=' . implode(',', $GLOBALS['temperamente']['hidden_pages']));
		if($post->post_parent>0){ $top_page_id = $post->post_parent; }else{ $top_page_id = $post->ID; }
		$second_lev_list = '';
		$top_list = '';
		foreach($top_pages as $current_top_page)
		{   
			$sub_pages = get_pages('child_of='.$current_top_page->ID.'&sort_column=menu_order&depth=1');	
			$sub_list='';
			if(count($sub_pages)>0)
			{	
				$class_sub = ' class="first" ';
				foreach($sub_pages as $current_sub_page)
				{
					$sub_list .= '<li'.$class_sub.'><a title="'.$current_sub_page->post_title.'" href="'.get_permalink($current_sub_page->ID).'">'.$current_sub_page->post_title.'</a></li>';
					$class_sub = '';
				}
				if($current_top_page->ID==$top_page_id ){ $class_tmp = ' current '; }else{ $class_tmp = ''; }
				$second_lev_list .= '<div id="sub_navigation_links'.$current_top_page->ID.'" class="sub_navigation_links '.$class_tmp.'" ><ul>'.$sub_list.'</ul></div>'; 
			}
			if($current_top_page->ID==$post->ID){ 
				$curr_class='current_page_item';
			}else{ 
				$curr_class=''; 
			}
			$top_list .= '<li class="page_item page-item-'.$current_top_page->ID.' '.$curr_class.'" id="top-page-'.$current_top_page->ID.'" ><a title="'.$current_top_page->post_title.'" href="'.get_permalink($current_top_page->ID).'">'.$current_top_page->post_title.'</a></li>';
		}
		if(is_home()){
			$home_class = ' page_item current_page_item ';
		}else{
			$home_class = ' page_item ';
		}
		$supheader_output = '<div id="navigation_links"><ul><li class="' . $home_class . '"><a href="/">Home</a></li>'.
		$top_list.
		'</ul></div>'.
		$second_lev_list; 
		
		Tonio_Cache::set('supheader', $cache_id, $supheader_output, 60*60*24*2);
		echo $supheader_output;
	}


?>		
	</div>