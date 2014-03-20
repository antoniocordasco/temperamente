			<div id="subheader">
				<div id="subheader-internal">
<?php 
	$catsy = get_the_category();
	if(!is_home() && isset($catsy[0])) {
		$current_category_id = $catsy[0]->term_id;
	} else {
		$current_category_id = 0;
	}
	
	
	if($header_categories_output = Tonio_Cache::get('header', 'selected-category-'.$current_category_id)) {
		echo $header_categories_output;
	} else {
	
		$header_categories_output = '';
		$cats = get_categories(array('exclude' => implode(',', $GLOBALS['temperamente']['hidden_category_ids'])));
		$i=0;
		foreach($cats as $cat)
		{ 
			$posts_cat = get_posts( array('category'=>$cat->term_id, 'numberposts'=>9999, 'post_status'=>'publish'  ) );
			if($current_category_id == $cat->term_id){ $class='catcurrent'; }else{ $class=''; }
			$header_categories_output .= '<li class="catlink '.$class.'" id="catlink' . $cat->term_id . '"><a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a> ('.count($posts_cat).') <div class="cat-dropdown"></div></li> ';
			$i++;			
		}
		$header_categories_output = '<ul>'.$header_categories_output.'</ul>';
		echo $header_categories_output;
		Tonio_Cache::set('header', 'selected-category-'.$current_category_id, $header_categories_output, 60*60*24);
	}
	
//	include(ABSPATH.'wp-content/themes/Owni/includes/search-textbox.php');
?>
				</div>
			</div>