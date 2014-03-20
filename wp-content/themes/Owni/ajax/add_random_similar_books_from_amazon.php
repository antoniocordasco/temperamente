<?php
	
	if(isset($_GET['iterations'])) {
		$iterations = intval($_GET['iterations']);
		set_time_limit(30);
		if($iterations > 20) {
			$iterations = 20;
		}
	} else {
		$iterations = 1;
	}
	
	for($i=0; $i<$iterations; $i++) {
		$book1 = gr_get_random_book_from_db(true, 10);
		$book2 = gr_get_random_book_from_db(true);
		
		$items = af_get_similarity_lookup_items(array($book1->amazon_asin, $book2->amazon_asin));
		
		echo "starting book: {$book1->title} ({$book1->created}) <br/>";
		echo "starting book: {$book2->title} ({$book2->created}) <br/>";
		
		foreach($items as $item) {
			echo 'new book: ' . $item['title'] . ' ' . $item['isbn'];
			$dbsave = af_save_book_details($item);
			echo " ($dbsave)";
			
			echo  '<br/>';
		}
	}
	