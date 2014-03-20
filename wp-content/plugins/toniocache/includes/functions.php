<?php


	function tc_get_cache_summary() {
		$sql = "SELECT namespace, COUNT(*) AS num, MAX(created) AS last_created, MIN(created) AS first_created FROM wp_toniocache_elements GROUP BY namespace ORDER BY namespace ASC;";
		return $GLOBALS['wpdb']->get_results($sql);	
	}
