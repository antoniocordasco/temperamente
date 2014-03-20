<?php

	if($book = ar_get_book_from_post_id($GLOBALS['post']->ID)) {	
		if($book->avg_rating > 0) {
			$rating_pixels = intval((100 / 5) * $book->avg_rating);
?>


<div id="goodreads-rating-etc">
	<div id="goodreads-rating">
		<span style="width:<?php echo $rating_pixels; ?>px;">&nbsp;</span>
	</div>
	<p><!-- voto medio: <?php echo $book->avg_rating; ?><br/>
	voti: <?php echo $book->tot_ratings; ?><br/> -->
	(<a href="<?php echo $book->goodreads_url; ?>" rel="nofollow" target="_blank">goodreads.com</a>)</p>
</div>



<?php
		}
	}