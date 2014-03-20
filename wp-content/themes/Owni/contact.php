<?php
/*
Template Name: Contact form
*/



$display_form = true;
$form_errors = array();
if(count($_POST)>0 && isset($_POST['cname'])  )
{
	foreach($_POST as $key => $val){ $post_unescaped[$key] = stripslashes($val); }
		
	if($_POST['cemail']==''){ $form_errors[] = 'Please provide your email'; }
	elseif(!is_email($_POST['cemail'])){ $form_errors[] = 'The email address you have provided is not in a valid format'; }
		
	if($_POST['cphone']==''){ $form_errors[] = 'Please provide your telephone number'; }
	
	if($_POST['cname']==''){ $form_errors[] = 'Please provide your  name'; }
	if($_POST['cmessage']==''){ $form_errors[] = 'Please provide a message'; }
	if(count($form_errors)==0)
	{
		$headers = 'From: ' .$post_unescaped['cemail']. "\r\n" .'X-Mailer: PHP/' . phpversion();
		$message = "Name: {$post_unescaped['cname']}\n\nEmail address: {$post_unescaped['cemail']}\n\nTelephone: {$post_unescaped['cphone']}\n\nI am a: {$post_unescaped['i_am_a']}\n\nMessage: {$post_unescaped['cmessage']}";
		
		
		$tmp =get_post_meta($post->ID,'tpi_contact_email',true);
		
		
		if($tmp && $tmp !=''){ $to_email = $tmp; }else{ $to_email = CONTACT_RECIPIENT; }
		$mail_sent = mail($to_email,'Contact message from: '.$post_unescaped['cemail']  , $message , $headers);
		wp_redirect(get_permalink($GLOBALS['chelmsford']['page_ids']['contact-us-confirmation']));
		$display_form = false;	
	}
}









get_header();

include(ABSPATH.'wp-content/themes/chelmsford/include/breadcrumbs.php');
include(ABSPATH.'wp-content/themes/chelmsford/include/left-nav.php');



	echo '<div id="content">';
	the_title('<h1>','</h1>');
	
	edit_post_link( __( 'Edit this entry', 'twentyten' ), '<span class="edit-link">', '</span>' );
	
	if($display_form)
	{
		the_title('<h2>',' form</h2>');
		if(count($form_errors)<=0){	
			
?>								
							
	
<?php }else{ 

		echo '<div class="form_errors">';
		foreach($form_errors as $msg){ echo '<div class="error">'.$msg.'</div>'; }
		echo '</div>';

	}
	
	include(CHELMSFORDTHEMEPATH.'include/forms/contact.php');
	
	}else{
		if($mail_sent)
		{
			if (have_posts()): while (have_posts()): the_post(); 			
		
			the_content(); 
			endwhile; endif; 	
		}else{
			echo '<p>The message has not been sent due to an internal error. Please try again shortly.</p>';
		}
	}
	echo '</div>';


include(ABSPATH.'wp-content/themes/chelmsford/include/top-right-tabs.php');

get_footer();


