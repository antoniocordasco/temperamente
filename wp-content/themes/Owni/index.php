<?php 
	if(owni_is_mobile()) {
		include('mobile/index.php');
		die;
	}

	
	get_header(); 
	
?>
<div id="content">
<?php

	include(TEMPLATEPATH.'/includes/homepage-content.php');	
	get_sidebar(); 

?>
</div>
<?php

	get_footer();


