<?php
/*
Template Name: Newsletter subscribe form
*/



$display_form = true;
$form_errors = array();


if(isset($_GET['unsubscribe']) && $_GET['unsubscribe'] != '') {
	$unsubscribe = 'fail';
	$tmp =  owni_tn2_get_subscriber_data(strtolower($_GET['unsubscribe']));
	if(count($tmp) > 0 ) {
		if(owni_tn2_unsubscribe(strtolower($_GET['unsubscribe']))) {
			$unsubscribe = 'success';
		}
	}
} elseif (count($_POST)>0 && isset($_POST['email']) ) {
	foreach($_POST as $key => $val){ $post_unescaped[$key] = stripslashes($val); }
		
	if($_POST['email']==''){ $form_errors[] = 'Inserire la propria email'; }
	elseif(!is_email($_POST['email'])){ $form_errors[] = "L'email non e' valida"; }
	else{
		$tmp =  owni_tn2_get_subscriber_data(strtolower($_POST['email']));
		if(count($tmp) > 0 ) {
			$form_errors[] = "L'email e' gia' registrata";
		}
	}		
	
	if(count($form_errors)==0)
	{
		$display_form = false;
		owni_tn2_subscribe(strtolower($_POST['email']));
	}
}







get_header() ?>
	<div id="content">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="post single"><div class="post-top"><div class="post-bottom"><div class="post-in">
			<h3 class="title"><?php the_title(); ?></h3>
			<!--div class="meta clearfix">
				<span class="date-post">Postato il <?php the_time('d/m/Y') ?></span> <span class="author-post">da <?php the_author() ?></span>
			</div-->
			<div class="entry">
				<?php 
				
	if($unsubscribe && $unsubscribe == 'fail') {
		echo "<p>Si e' verificato un errore nel cancellare la tua email dalla newsletter di Temperamente. Prova nuovamente e se il problema per siste contatta la redazione su: temperamente@libero.it</p>";
	} elseif($unsubscribe && $unsubscribe == 'success') {
		echo "<p>La tua email e' stata canncellata con successo dalla newsletter di Temperamente.</p>";
	} elseif ($display_form)	{		
		if(count($form_errors)<=0){
			the_content('');
		}else{ 
			echo '<div class="form_errors">';
			foreach($form_errors as $msg){ echo '<div class="error">'.$msg.'</div>'; }
			echo '</div>';
		}
	
				
		include('includes/forms/newsletter-subscribe.php');
	
	} else {
		echo '<p>Grazie per esserti registrato alla newsletter di Temperamente.</p>';
	}				
				
				
				
				
				
				
				
				
				?>
			</div>
			
		</div></div></div></div>
		<?php endwhile; ?>
		
	<?php endif ?>
<?php 
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 


