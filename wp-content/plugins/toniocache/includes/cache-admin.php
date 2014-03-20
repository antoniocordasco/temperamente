<?php


	$summary = tc_get_cache_summary();
	
	
	
	
	?>
	<div class="wrap">
		<p>Website cache (<?php echo count($summary); ?> namespaces)</p>
	<table class="widefat">
		<thead><tr><th width="150">Namespace</th><th width="150">Elements</th><th width="150">First created</th><th width="150">Last created</th><th width="40">Expire</th></thead>
		<tbody> <?php
	foreach($summary as $item)
	{ 
		echo '<tr><td>'.$item->namespace.'</td><td>'.$item->num.'</td><td>'.$item->first_created.'</td><td>'.$item->last_created.'</td>
		<td><a href="/wp-admin/options-general.php?page=tc_cache_admin_page&tc_expire='.$item->namespace.'">expire this namespace</a></td></tr>';
	}
	
	
	?>
		</tbody>
	</table>

</div>