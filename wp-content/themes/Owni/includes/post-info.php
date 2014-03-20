<?php



		
	if($post_info_output = Tonio_Cache::get('post-info',  $post->ID)) {
		echo $post_info_output;
	} else {
		$post_info_output = '<div class="meta clearfix post_info">
		<div class=" post_info_left"><p>Recensione del '.get_the_time('d/m/Y').' di ';
					$tmp_pages = get_pages(array('meta_key'=>'tpi_posts_list_author','meta_value'=>$post->post_author,'hierarchical'=>0));
					if(count($tmp_pages) == 1) {
						$post_info_output .= '<a title="tutte le recensioni di '.get_the_author().'" href="'.get_permalink($tmp_pages[0]->ID).'" rel="author" >'.get_the_author().'</a>';
					} else {
						$post_info_output .= get_the_author();
					}
		$post_info_output .= '</p>';

			$tpi_book_author = get_post_meta($post->ID, 'tpi_book_author', true);
			if($tpi_book_author > 0)
			{
			  $current_author = tpi_get_book_author($tpi_book_author);
			  $post_info_output .= '<p >Visualizza tutte le recensioni di opere di <a href="/autori-recensiti/?tpi_author='.$current_author->folder_name.'">'.$current_author->first_name.' '.$current_author->last_name.'</a></p>';
			}

		$post_info_output .= '<p >Visualizza i <a href="#post_comments">commenti a '.get_the_title().'</a></p></div>
		<div class=" post_info_right">
		';		
		
		$search = preg_replace('/[^A-Za-z0-9\s]/', '', $post->post_title);
		
		
		$post_cats = wp_get_post_categories(get_the_ID());	
		$book = false;
		if(!in_array($post_cats[0], $GLOBALS['temperamente']['nonreview_categories_ids'])) {		
			if($book = af_get_book_details_by_search( $search )) { 
			
			} else {
				$a = array('-', '%e2%80%9c', '%e2%80%9d', '%e2%80%93');
				$b = array(' ', ' ', ' ', ' ');
				$search = str_replace($a, $b, $post->post_name);
				$book = af_get_book_details_by_search( $search );
			}
		}
		
		if($book) { 
		
			
			af_save_book_details($book, $post->ID);
			$book_data = af_get_book_by_post_id($post->ID);
			if(isset($book['release-date'])) {
				$post_info_output .= '<p>Data di pubblicazione: '.date('d/m/Y', strtotime($book['release-date'])).'</p>';
			}
			if($book_data && $book_data->isbn != '') {
				$post_info_output .= '<p>ISBN: '.$book_data->isbn.'</p>';
			}
			if($book_data && $book_data->isbn13 != '') {
				$post_info_output .= '<p>ISBN13: '.$book_data->isbn13.'</p>';
			}
			if(isset($book['price'])) {
				$post_info_output .= '<p>Prezzo di listino: <span>'.($book['price']/100).' euro</span></p>';
			}
			if(isset($book['url']) && isset($book['lowest-price'])) {
				$post_info_output .= '<p class="amazon-logo">Prezzo su <a class="amazon-link" href="'.$book['url'].'" rel="nofollow" ><img src="'.get_option('siteurl').'/wp-content/themes/Owni/images/amazon/amazon.it-logo-59x17.png" alt="logo amazon.it"/></a>: <span>'.($book['lowest-price']/100).' euro</span> (<a class="amazon-link" href="'.$book['url'].'" title="acquista '.$book['title'].'" >acquistalo subito</a>)</p>';
			}
			
		}
		if($current_author) {
			$search_url = 'http://www.amazon.it/gp/search?ie=UTF8&keywords='.$current_author->first_name.'%20'.$current_author->last_name.'&tag=temperamente-21&index=books&linkCode=ur2&camp=3370&creative=23322';
			$post_info_output .= '<p class="amazon-logo"><a class="amazon-link" href="'.$search_url.'" rel="nofollow" >Visualizza altre opere di '.$current_author->first_name.' '.$current_author->last_name.'</a><br/> su 
			<a class="amazon-link" href="'.$search_url.'" rel="nofollow" ><img src="'.get_option('siteurl').'/wp-content/themes/Owni/images/amazon/amazon.it-logo-59x17.png" alt="logo amazon.it"/></a> </p>';
		}
		
		
		
		$post_info_output .= '</div></div>';
		echo $post_info_output;
		Tonio_Cache::set('post-info', $post->ID, $post_info_output, 60*60*24*14);
	}
	
	
	
	
	
	