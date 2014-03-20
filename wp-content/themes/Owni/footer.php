
<div id="footer">
	<div class="clearfix" id="footer-block">


		
<?php	


	if($footer_output = Tonio_Cache::get('footer', '')) {
		echo $footer_output;
	} else {

		$footer_output = "\t<div class=\"block\" id=\"popular-posts\">\n\t\t<h2>Post pi&ugrave; discussi</h2>\n";

		$posts2 = get_posts('numberposts=5&orderby=comment_count&order=DESC&category=-'.implode(',-',$GLOBALS['temperamente']['nonreview_categories_ids'])  );
		$recent_posts = array();
		foreach($posts2 as $tmp){ if($tmp->comment_count>0){ $recent_posts[]=$tmp; }   }
		if(count($recent_posts)>0)
		{
			$footer_output .= "\t\t<ul>\n";
			foreach($recent_posts as $cur)
			{
				$comms = get_comments( array('post_id'=>$cur->ID) );
				$footer_output .=  "\t\t\t" . '<li><a rel="bookmark" href="'.get_permalink($cur->ID).'">'.$cur->post_title.' ('.count($comms).' commenti)</a></li>' . "\n";
			}
			$footer_output .= "\t\t</ul>\n";
		}
		
		$footer_output .= "\t</div>\n\t<div class=\"block last\" id=\"f-recent-comments\">\n\t\t<h2>Commenti recenti</h2>\n";

		$recent_posts = get_comments(array('number'=>5,'orderby'=>'comment_date','order'=>'DESC','status'=>'approve') );
		if(count($recent_posts)>0)
		{
			$footer_output .= "\t\t<ul>\n";
			foreach($recent_posts as $cur)
			{
				$tmp_str = substr(strip_tags($cur->comment_content),0,60);
				if(strlen(strip_tags($cur->comment_content)) > strlen($tmp_str)){ $tmp_str .= ' ...'; }
				$footer_output .= "\t\t\t" . '<li><a rel="bookmark" href="'.get_permalink($cur->comment_post_ID).'">['.mysql2date('d/m/y',$cur->comment_date).'] '. $tmp_str .'</a></li>' . "\n";
			}
			$footer_output .= "\t\t</ul>\n";
		}	
		$footer_output .= "\t\t</div>\n\t</div>\n";
		
	//	die;
		echo $footer_output;
		Tonio_Cache::set('footer', '', $footer_output, 60*60*24);
		
	}
	// echo $GLOBALS['wpdb']->num_queries;
?>				

	
	<div id="footer-content">
		<p><br/><br/>Collaborano con noi: 
		<a href="http://www.marcosymarcos.com" rel="nofollow"><img src="/wp-content/themes/Owni/images/sponsors/marcos-y-marcos.png" alt="Marcos y Marcos casa editrice" /></a>
		<a href="http://www.voland.it" rel="nofollow"><img src="/wp-content/themes/Owni/images/sponsors/voland.png" alt="Casa editrice Voland" /></a></p>
		<p>Copyright &copy; 2012 <a title="Temperamente" href="http://www.temperamente.it">Temperamente</a> - Blog di recensioni letterarie.</p>
	</div>
</div>
<?php
	
	if($GLOBALS['temperamente']['include_adsense'] AND $_SERVER['REMOTE_ADDR'] != '86.1.70.197' AND $_SERVER['REMOTE_ADDR'] != '109.231.230.98') {
		include(ABSPATH.'wp-content/themes/Owni/includes/ganalytics.php');
	}
?></body>
</html>
