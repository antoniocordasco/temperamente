<?php 
$posts_per_page = get_option('posts_per_page');
$is_first_page = false;
if (have_posts()) : 
	$even = false;  
	 
	$recent_posts = get_posts();
	$i=1;
	while (have_posts())
	{
		the_post(); 
		$even = !$even; 
		$first = (get_the_ID() == $recent_posts[0]->ID);
		if($i<$posts_per_page || !$is_first_page)
		{
?>
		<div id="post-<?php the_ID(); ?>" class="<?php
			if($first){ 
			$is_first_page = true;
				echo 'post_wide'; 
			}else{
				echo 'post';
				if($even){ echo ' even'; }
			}		
		?>">
			<div class="inner">
				<h3 class="title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<div class="meta">
					<span class="date-post"><?php the_time('F j, Y') ?></span>
					<span class="categories-post">Postato in: <?php the_category(', ') ?> </span>&nbsp;
<?php if(!$first){ ?><a class="read-more" href="<?php the_permalink() ?>#more-<?php the_ID(); ?>" title="">Continua a leggere</a><?php } ?>
				</div>
<?php 
	if($first){ 
		$len = 1300; $thclass = 'thumbnail big'; 
	}else{ 
		$len = 650; $thclass = 'thumbnail'; 
		if(strlen(the_title('','',false)) > 80){ $len = 200; }
		elseif(strlen(the_title('','',false)) > 58){ $len = 300; }
		elseif(strlen(the_title('','',false)) > 40){ $len = 500; }
	}
	$thumbnail = (get_post_meta($post->ID, 'thumbnail', $single = true)) ? '<img class="'.$thclass.'" src="'.get_post_meta($post->ID, 'thumbnail', $single = true).'" alt="'.get_the_title($post->ID).'" />' : ''; 
	echo $thumbnail 
?>
				<div class="entry<?php if (!$thumbnail): ?> no-image<?php endif ?>">
<?php 
	another_entry_post($len);  
					
if($first){ ?><p><a class="read-more" href="<?php the_permalink() ?>#more-<?php the_ID(); ?>" title="">Continua a leggere</a></p><?php } ?>


				</div>
				
<?php
if($first)
{
	echo '<div class="additional_info">';

	
	$first_post_comments = get_comments( array('post_id'=>get_the_ID(),'status'=>'approve','orderby' =>'comment_date','order' => 'ASC')); 
	if(count($first_post_comments)>0)
	{
		if(count($first_post_comments)>1){ $plural = 'i'; }else{ $plural = 'o'; }
		echo '<h4>'.count($first_post_comments).' comment'.$plural.'</h4>';
		echo '<p><a href="'.get_permalink(get_the_ID()).'#comment_form">Scrivi un commento</a></p>';
	}else{
		echo '<h4>0 commenti</h4>';
		echo '<p><a href="'.get_permalink(get_the_ID()).'#comment_form">Scrivi per primo un commento a questo post</a></p>';
	}
	echo '</div>';
}
?>				
				
				
				
			</div>
		</div>
<?php 
	}
	$i++;	
	}
?>
	<?php if(function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi('<span id="space"></span><div id="wp-pagenavi-wrapper">', '</div><!-- /wp-pagenavi-wrapper -->') ?>
	<?php else: ?>
		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Post precedenti') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	<?php endif ?>
<?php else: ?>
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
<?php endif ?>