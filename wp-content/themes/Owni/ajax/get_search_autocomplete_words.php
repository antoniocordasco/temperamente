<?php


$posts = get_posts(array(
	'exclude' => $GLOBALS['temperamente']['hidden_category_ids'],
	'numberposts' => 9999
	));
	
	
	$titles = array();
	foreach($posts as $tmp) {
		$titles[] = $tmp->post_title;
	}
	echo json_encode($titles);

