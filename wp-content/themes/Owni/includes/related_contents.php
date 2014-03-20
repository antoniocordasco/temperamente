
	<div id="related_contents" >
<?php 

	
	if($related_contents_output = Tonio_Cache::get('related-contents', 'post-id-' . $post->ID)) {
		echo $related_contents_output;
	} else {
		$post_cats = wp_get_post_categories( $post->ID );	
		$related_posts = get_posts(array('exclude'=>$post->ID, 'category'=>$post_cats[0],'numberposts'=>6)); 
		$tmp_cat =  get_category($post_cats[0]);
		
		$related_contents_output = '';
		
		$post_cats = wp_get_post_categories(get_the_ID());	
	
		if(!in_array($post_cats[0], $GLOBALS['temperamente']['nonreview_categories_ids']) && count($related_posts)>0)
		{
			$related_contents_output .= "\t\t<p>Altre recensioni nella categoria \"".$tmp_cat->slug."\"</p>\n".
			"\t\t<ul id=\"related_contents_carousel\" class=\"jcarousel-skin-tango\">\n";

			foreach($related_posts as $tmp)
			{	
				$img_url = get_post_meta($tmp->ID, 'thumbnail',true);
				$img_url = owni_get_thumbnail_url_from_guid($img_url, 'medium');
				$author_data = get_userdata($tmp->post_author);
				$author_page = get_pages(array('meta_key'=>'tpi_posts_list_author','meta_value'=>$author_data->ID,'hierarchical'=>0));

				if(count($author_page)>0){ $author_link = '<a href="'.get_permalink($author_page[0]->ID).'">'.$author_data->display_name.'</a>'; }
				else{ $author_link = $author_data->display_name; }
				$tmp_url = get_permalink($tmp->ID);
				$related_contents_output .= "\t\t\t" . '<li class="book" ><a href="'.$tmp_url.'"><img src="'.$img_url.'" alt="" /></a><h5><a href="'.$tmp_url.'">'.$tmp->post_title.'</a></h5>' .
				'<p>Recensione di <br/>'.$author_link.'</p><p>del '.mysql2date('d/m/Y',$tmp->post_date).'</p></li>' . "\n";		
			}
			$related_contents_output .= "\t\t</ul>\n";
		}
		
		echo $related_contents_output;
		Tonio_Cache::set('related-contents', 'post-id-' . $post->ID, $related_contents_output, 60*60*24*3);		
	}
	
	
	
	
	do_action('owni_related_contents');	
	
?>
	</div>
	