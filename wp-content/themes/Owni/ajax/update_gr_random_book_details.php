<?php


	for($i=0; $i<2; $i++) {
		$book = gr_get_random_book_from_db();
		
		echo "updating: {$book->title} - {$book->isbn} <br/>";
		if(gr_update_book_details($book->id)) {
			echo "updated. <br/>";
		}
		flush();
		sleep(1);
	}

	