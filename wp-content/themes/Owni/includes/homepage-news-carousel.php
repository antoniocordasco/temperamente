	
	<div class="post_wide news_div" >
		<h3 class="title">In primo piano</h3>			
			
<?php
	
		query_posts(array('cat' => $GLOBALS['temperamente']['category_ids']['news'], 'orderby' => 'post_date', 'order' => 'DESC', 'numberposts' => $num_posts_per_cat));
		if ( have_posts() ) 
		{	
			for($j=0; $j<$num_posts_per_cat; $j++) 
			{
				the_post();	
				$post_permalink = get_permalink(get_the_ID());	
			
				$author_id_tmp = $post->post_author;			
				if(!isset($authors_data[$author_id_tmp]))
				{
					$tmp_pages = get_pages(array('meta_key'=>'tpi_posts_list_author','meta_value'=>$author_id_tmp,'hierarchical'=>0));
					if(count($tmp_pages)>0){ $authors_data[$author_id_tmp]->author_page_url = get_permalink($tmp_pages[0]->ID); }
				}
				
				$thumbnail_url = get_post_meta(get_the_ID(), 'thumbnail', $single = true);
?>				
			<div class="entry  no-image" style="background-image:url('<?php echo $thumbnail_url; ?>');">
				
				<div class="bottom_text">
					<h4><a href="<?php echo $post_permalink; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>				
				<p class="home_excerpt" ><?php echo get_the_excerpt(); ?></p>	
				</div>
			</div>
			
<?php				
			}
		}
?>
		</div>
			