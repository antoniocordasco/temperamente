<?php

	function gr_get_random_book_from_db($with_asin=false, $within_the_latest=false) {
		$sql = 'SELECT MOD( (SUBSTR(updated,18,2) + post_id + id + NOW()),17) AS ord, b.* FROM wp_books b WHERE 1=1	';
		if($with_asin) {
			$sql .= " AND amazon_asin != '' ";
		}
		if($within_the_latest) {
			$within_the_latest = intval($within_the_latest);
			$sql .= " AND id > (SELECT MAX(id)-$within_the_latest FROM wp_books) ";
		}
		$sql .= ' ORDER BY ord ASC, updated ASC  LIMIT 0,1;';
		
		$rows = $GLOBALS['wpdb']->get_results($sql);
		if($rows && count($rows)==1) {
			return $rows[0];
		}
		return false;
	}


	function ar_get_book_from_post_id($post_id) {
		$sql = " SELECT b.*, SUBSTR(SUM(br.rating * br.ratings_num) / SUM(br.ratings_num),1,3) AS avg_rating, SUM(br.ratings_num) tot_ratings FROM wp_books b JOIN wp_book_ratings br ON b.id = br.book_id WHERE b.post_id = $post_id; ";
		$rows = $GLOBALS['wpdb']->get_results($sql);
		if($rows && count($rows)==1) {
			return $rows[0];
		}
		return false;
	}
	
	
	


	function gr_update_book_details($book_id) {	
		if(intval($book_id)>0) {	  
			$book_id = intval($book_id);
			
			$rows = $GLOBALS['wpdb']->get_results("SELECT * FROM wp_books WHERE id = '$book_id'");
			if(count($rows) > 0) {
				if($data = gr_get_book_details_by_isbn($rows[0]->isbn)) {
				//	var_dump($data);
					foreach($data as $k => $v) { 
						if(!is_array( $data[$k])) { $data[$k] = mysql_real_escape_string($data[$k]); }
					}					
					$sql = "UPDATE wp_books SET
					title = '{$data['title']}', isbn13 = '{$data['isbn13']}', pages = '{$data['pages']}', goodreads_url = '{$data['url']}', reviews_widget = '{$data['reviews_widget']}', updated = '".date('Y-m-d H:i:s')."'
					WHERE id = '{$rows[0]->id}'; ";
					$res = $GLOBALS['wpdb']->query($sql);
					$book_id = $rows[0]->id;
					if($data['ratings'] && is_array($data['ratings'] )) {
						$GLOBALS['wpdb']->query('DELETE FROM wp_book_ratings WHERE book_id = '.$book_id);
						$tmp = array();
						foreach($data['ratings'] as $k => $v) {
							if(intval($k)>0) {
								$tmp[] = "('".$book_id."', '".intval($k)."', '".intval($v)."')";
							}
						}
						if(count($tmp) > 0) {
							$sql = 'INSERT INTO wp_book_ratings (book_id,rating,ratings_num) VALUES '.implode(',', $tmp).';';
							$res = $GLOBALS['wpdb']->query($sql);
						}
					}
					return true;
				}
			}
		}
		return false;
	}

	function gr_get_book_details_by_isbn($isbn) {	
		$book_url = $GLOBALS['temperamente']['goodreads']['api_url'] . 'book/isbn/' . $isbn . '?format=xml&key=' . $GLOBALS['temperamente']['goodreads']['api_key'];
		echo $book_url;
		if($contents = @file_get_contents($book_url)) {
			if($doc = DOMDocument::loadXML( $contents )) {				
				$book_data = array();
				$book = $doc->getElementsByTagName('book')->item(0);
				if($title = $book->getElementsByTagName('title')->item(0)) {
					$book_data['title'] = $title->nodeValue;
				}
				if($url = $book->getElementsByTagName('url')->item(0)) {
					$book_data['url'] = $url->nodeValue;
				}
				if($isbn13 = $book->getElementsByTagName('isbn13')->item(0)) {
					$book_data['isbn13'] = $isbn13->nodeValue;
				}
				if($pages = $book->getElementsByTagName('num_pages')->item(0)) {
					$book_data['pages'] = $pages->nodeValue;
				}
				if($work = $book->getElementsByTagName('work')->item(0)) {
					if($rating_dist = $work->getElementsByTagName('rating_dist')->item(0)) {
						$book_data['ratings'] = array();
						$rating_dist = explode('|', $rating_dist->nodeValue);
						foreach($rating_dist as $tmp) {
							$tmp2 = explode(':', $tmp);
							$book_data['ratings'][$tmp2[0]] = $tmp2[1];					
						}							
					}
				}		
				if($reviews_widget = $book->getElementsByTagName('reviews_widget')->item(0)) {
					$book_data['reviews_widget'] = $reviews_widget->nodeValue;
				}
				return $book_data;
			}
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	