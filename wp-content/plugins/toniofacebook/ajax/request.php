<?php


	if(isset($_GET['req_type'])) {	
		if($_GET['req_type'] == 'fetch_latest_posts') {
			echo 'fetching latest posts<br/>';
			tfb_fetch_latest_posts();
			
		} elseif($_GET['req_type'] == 'fetch_latest_images_5-buoni-motivi') { 
			echo 'fetching latest images<br/>';
			tfb_fetch_latest_images($GLOBALS['tfb']['albums']['5-buoni-motivi']['id']);
			
		}
	}