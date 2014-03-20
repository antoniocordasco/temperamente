<?php
		
	foreach($GLOBALS['tq']['image_posts_rows'] as $row)
	{
		echo '<option value="'.$row->ID.'">'.$row->post_title.'</option>'."\n";
	}