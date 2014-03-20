<?php 

/*
Template Name: Book authors list
*/
$alphabet = array('A'=>false,'B'=>false,'C'=>false,'D'=>false,'E'=>false,'F'=>false,'G'=>false,'H'=>false,'I'=>false,'J'=>false,'K'=>false,'L'=>false,'M'=>false,'N'=>false,'O'=>false,'P'=>false,'Q'=>false,'R'=>false,'S'=>false,'T'=>false,'U'=>false,'V'=>false,'W'=>false,'X'=>false,'Y'=>false,'Z'=>false);





get_header() ?>
	<div id="content">
	
	
<?php 
	$author = array('posts'=>array());
	if(isset($_GET['tpi_author']))
	{
		$author_posts = get_authors_posts($_GET['tpi_author']);		
	}
	
	if(count($author_posts )>0)
	{
		$tpi_author = tpi_get_book_author_by_folder($_GET['tpi_author']);
?>	

<div class="post single" id="post-11126"><div class="post-top"><div class="post-bottom"><div class="post-in">
			<h3 class="title"><?php echo $tpi_author->first_name.' '.$tpi_author->last_name; ?></h3>
			
			<div class="entry">
</div></div></div></div></div>

<?php	
		
		foreach($author_posts as $current_post)
		{
?>
			<div class="post single" id="post-<?php echo $current_post->ID; ?>">
				<h3 class=""><a title="<?php echo get_the_title($current_post->ID); ?>" href="<?php echo get_permalink($current_post->ID); ?>"><?php echo get_the_title($current_post->ID); ?></a></h3>
				<div class="meta">
					<span class="date-post">Postato il <?php echo get_the_time( 'd/m/Y', $current_post->ID); ?></span>
					<span class="categories-post"> in: <?php the_category(', ','',$current_post->ID) ?> </span>&nbsp;
					<a title="" href="<?php echo get_permalink($current_post->ID); ?>#more-<?php echo $current_post->ID; ?>" class="read-more">Continua a leggere</a>
				</div>				
			</div>

<?php		
		}
		
		
	}else{
		echo '<a name="alphabet"></a><ul class="authors_alphabet">';
		foreach($alphabet as $k => $v)
		{
			echo '<li><a href="#alphabet_'.$k.'">'.$k.'</a></li>';
		}
		echo '</ul>';
		
		$authors_list = get_authors_list();
		
		foreach($authors_list['authors'] as $current_author) 
		{
			
?>
			<div class="post single" >
<?php			
			if(!$alphabet[strtoupper(substr($current_author->last_name,0,1))])
			{ 
				$alphabet[strtoupper(substr($current_author->last_name,0,1))] = true;
				echo '<a name="alphabet_'.strtoupper(substr($current_author->last_name,0,1)).'"></a>'; 
				
			}
?>
				<h3 class="title"><a title="<?php echo $current_author->first_name.' '.$current_author->last_name; ?>" href="./?tpi_author=<?php echo $current_author->folder_name; ?>"><?php echo $current_author->first_name.' '.$current_author->last_name; ?></a></h3>
				<div class="meta">
				<p><a class="read-more author_article" href="./?tpi_author=<?php echo $current_author->folder_name; ?>"><?php 
				echo $current_author->auth_occurrencies.' articol'; 
				if($current_author->auth_occurrencies>1){ echo 'i'; }else{ echo 'o'; }
				?></a> - <a href="#alphabet" class="read-more ">Torna su</a></p>
				</div>
				<div class="author_posts_list">
				<?php 
				if(count($authors_list['posts'][$current_author->id])>0)
				{
					echo '<ul>';
					foreach($authors_list['posts'][$current_author->id] as $current_post)
					{
						$post_url = get_permalink($current_post->ID);
						echo '<li><h4><a href="'.$post_url.'">'.$current_post->post_title.'</a></h4>'.date('F d,Y',strtotime($current_post->post_date)).' <a title="" href="'.$post_url.'" class="read-more">Continua a leggere</a></li>';
					}
					echo '</ul>';
				}
				?>
				</div>				
			</div>

<?php		
		}
	}
	
	
		
		
		
		
?>
	
<?php 
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 

