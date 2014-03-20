<?php

	
	if(isset($_GET['category_id'])) {
		$cat_id = intval($_GET['category_id']);
		
		if($dropdown_html = Tonio_Cache::get('header-catlink-dropdown', 'selected-category-'.$cat_id)) {
			echo $dropdown_html;
		} else {
			$dropdown_html = '';
			$category = get_category( $cat_id );
			
			$dropdown_html =  '<div class="dropdown-header" ><div class="dropdown-header-arrow"></div></div>'.		
			'<div class="dropdown-body" ><div><h4>Categoria: ' . $category->name . '</h4>' .
			'<p class="light">(' . $category->count . ' recensioni)</p><hr/>' .
			'<h4>Post recenti: </h4>';
			
			$comments = array();
			$posts = get_posts( array('category' => $cat_id, 'numberposts' => 10) );
			$i = 0;
			foreach($posts as $current) {
				$post_comments = get_comments(array('post_id' => $current->ID));
				$comments = array_merge($comments, $post_comments);
				if($i < 4) {
					$dropdown_html .= '<p>' . $current->post_title. ' </p>';
				}
				$i++;
			}
			$comments = array_slice($comments,0,4);
			if(count($comments) > 0) {
				$dropdown_html .= '<h4>Commenti recenti: </h4>';
				foreach($comments as $current) {
					$words = explode(' ', $current->comment_content);
					$words_display = array_slice($words,0,16);
					if(count($words) > count($words_display)){ 
						$tmp_str = ''; 
					}else{ 
						$tmp_str = ' ... '; 
					}
					$dropdown_html .= '<p>' . implode(' ', $words_display) . $tmp_str . ' </p>';
				}
			}
			$dropdown_html .= '</div></div>';
			echo $dropdown_html;
			Tonio_Cache::set('header-catlink-dropdown', 'selected-category-'.$cat_id, $dropdown_html, 60*60*24);
		}
	}
	
