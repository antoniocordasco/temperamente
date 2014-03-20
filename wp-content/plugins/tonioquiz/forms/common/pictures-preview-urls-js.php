<script type="text/javascript">


	var img_preview_urls = new Array(); 
<?php	
	foreach($GLOBALS['tq']['image_posts_rows'] as $row)
	{
		echo "img_preview_urls[".$row->ID."] = '".str_replace("'","\\'",$row->guid)."'; \n";
	}
	
	
	
	
	
?>

</script>