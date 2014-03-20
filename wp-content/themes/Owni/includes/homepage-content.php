<?php 


if($home_posts_output = Tonio_Cache::get('home-posts', '')) {
	echo $home_posts_output;
} else {
	$home_posts_output = '';

	$authors_data = array();
	$num_posts_per_cat = 5;
	
	$home_posts_output .= '<div class="post_wide news_div" >'."\n".
		'<h3 class="title">In primo piano</h3>'."\n";	
	
	query_posts(array('cat' => $GLOBALS['temperamente']['category_ids']['news'], 'orderby' => 'post_date', 'order' => 'DESC', 'numberposts' => $num_posts_per_cat));
	if ( have_posts() ) 
	{	
		for($j=0; $j<$num_posts_per_cat; $j++) 
		{
			the_post();	
			$post_permalink = get_permalink(get_the_ID());	
			
			$author_id_tmp = $post->post_author;			
			if(!isset($authors_data[$author_id_tmp]))
			{
				$authors_data[$author_id_tmp] = new StdClass();
				$tmp_pages = get_pages(array('meta_key'=>'tpi_posts_list_author','meta_value'=>$author_id_tmp,'hierarchical'=>0));
				if(count($tmp_pages)>0){ $authors_data[$author_id_tmp]->author_page_url = get_permalink($tmp_pages[0]->ID); }
			}
				
			$thumbnail_url = get_post_meta(get_the_ID(), 'thumbnail', $single = true);
			
			$home_posts_output .= '<div class="entry  no-image" ><img src="'.$thumbnail_url.'" alt="immagine '.get_the_title().'" />'."\n".
				'<div class="bottom_text">'."\n".
				'<h4><a href="'.$post_permalink.'" title="'.get_the_title().'">'.get_the_title().'</a></h4>'."\n".			
				'<p class="home_excerpt" >'.get_the_excerpt().'</p>'."\n".
				'</div>'."\n".
				'</div>'."\n";
		}
	}
	$home_posts_output .= '</div>'."\n";

	
	
	
	
	
	$cats = owni_get_recent_categories();
	
	for($i =0; $i < count($cats); $i++) 
	{
		
		$current_cat = $cats[$i];
		$current_cat_url = get_category_link( $current_cat->term_id ); 		
		
		$home_posts_output .= '<div class="';
		if($i<8) { 
			$home_posts_output .= ' post_wide '; 
		} else { 
			$home_posts_output .= ' more_reviews '; 
			if($i%2 == 0){ $home_posts_output .= ' left '; } else { $home_posts_output .= ' right '; } 
		} 
		$home_posts_output .= '" >	'."\n";	
		
		$home_posts_output .= '<h3 class="title"><a title="'.$current_cat->name.'" href="'.$current_cat_url.'">'.$current_cat->name.'</a></h3>'."\n".			
			'<div class="meta"><span class="categories-post"><a href="'.$current_cat_url.'">'.$current_cat->category_count.' recensioni</a></span></div>'."\n";

		if($i<8) 
		{
			query_posts(array('cat' => $current_cat->term_id, 'orderby' => 'post_date', 'order' => 'DESC', 'numberposts' => $num_posts_per_cat));
			
			for($j=0; $j<$num_posts_per_cat; $j++) 
			{
				if ( have_posts() ) 
				{	
					the_post();	
					$post_permalink = get_permalink(get_the_ID());	
				
					$author_id_tmp = $post->post_author;			
					if(!isset($authors_data[$author_id_tmp]))
					{
						$tmp_pages = get_pages(array('meta_key'=>'tpi_posts_list_author','meta_value'=>$author_id_tmp,'hierarchical'=>0));
						if(count($tmp_pages)>0){ 
						$authors_data[$author_id_tmp] = new stdClass();
						$authors_data[$author_id_tmp]->author_page_url = get_permalink($tmp_pages[0]->ID); 
						}
					}
					$thumbnail_guid = get_post_meta(get_the_ID(), 'thumbnail', true);
					$thumbnail = '';
					if($thumbnail_guid) {
						$thumbnail_guid = owni_get_thumbnail_url_from_guid($thumbnail_guid, 'medium');
						$thumbnail = '<div class="book_background_big"><a href="'.$post_permalink.'">
						<img src="'.$thumbnail_guid.'" alt="'.get_the_title(get_the_ID()).'" />
						</a></div>'; 
					}
					if($current_cat->term_id == $GLOBALS['temperamente']['category_ids']['libri-al-cinema'] 
					|| $current_cat->term_id == $GLOBALS['temperamente']['category_ids']['news'] 
					|| $current_cat->term_id == $GLOBALS['temperamente']['category_ids']['interviste'] 
					|| $current_cat->term_id == $GLOBALS['temperamente']['category_ids']['racconti_sgc']
					|| $current_cat->term_id == $GLOBALS['temperamente']['category_ids']['quiz']
					|| $current_cat->term_id == $GLOBALS['temperamente']['category_ids']['officina-dei-pensieri'])
					{
						$thumbnail = str_replace('book_background_big','nonbook_background_big',$thumbnail);
					}

					$home_posts_output .= '<div class="entry';
					if (!$thumbnail){ $home_posts_output .= ' no-image '; } 
					$home_posts_output .= '">';
					$home_posts_output .= $thumbnail; 
					$home_posts_output .= '<div class="right_text">'."\n";
					$home_posts_output .= '<h4><a href="'.$post_permalink.'" title="'.the_title('','',false).'">'.the_title('','',false).'</a></h4>'."\n".
						'<p>Recensione del '.get_the_time('d/m/Y').' di ';
					$author_page_exist = (isset($authors_data[$author_id_tmp]) && isset($authors_data[$author_id_tmp]->author_page_url));
					if($author_page_exist){ $home_posts_output .= '<a href="'.$authors_data[$author_id_tmp]->author_page_url.'">'; }		
					$home_posts_output .= get_the_author();
					if($author_page_exist){ $home_posts_output .= '</a>'; } 
					$home_posts_output .= '.';

					$tpi_book_author = get_post_meta($post->ID, 'tpi_book_author', true);
					if($tpi_book_author > 0)
					{
						$current_author = tpi_get_book_author($tpi_book_author);
						$home_posts_output .= ' Visualizza tutte le recensioni di opere di <a href="/autori-recensiti/?tpi_author='.$current_author->folder_name.'">'.$current_author->first_name.' '.$current_author->last_name.'</a>'."\n";
					}				

					$home_posts_output .= '</p>'."\n".
						'<p class="home_excerpt" >'.get_the_excerpt().'</p>'."\n".	
						'<p class="read-more"><a class="read-more" href="'.$post_permalink.'#more-'.get_the_ID().'" title="">Continua a leggere</a></p>'."\n".						
						'</div>'."\n".
						'</div>'."\n";
			
				}	
			}
		}

		$home_posts_output .= '</div>'."\n";
	}
	echo $home_posts_output;
	Tonio_Cache::set('home-posts', '', $home_posts_output, 60*60*10);
}
	
	
	
		
	
	
	