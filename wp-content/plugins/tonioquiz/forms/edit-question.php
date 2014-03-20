<?
	include TQ_PATH.'forms/common/pictures-preview-urls-js.php';
	
	$formErrors = array();
	$formIsValid = true;
	$saved = false;
	$imageUploaded = false;
	$formValues=array(
			'id'=>'',
			'title'=>'',
			'question_text'=>'',
			'right_id'=>0,
			'question_picture'=>0,
			'quiz_post_id'=>0
		);
	for($i=0;$i<TQ_NUM_CHOICES;$i++){ $formValues['choice_'.$i]=''; }	
		
	$choice_ids = array();
	
	if(isset($_GET['tq_item_id']) && intVal($_GET['tq_item_id'])>0){ 
		$item = tq_get_question($_GET['tq_item_id']); 
		foreach($formValues as $key => $val){ if(isset($item->$key)){ $formValues[$key] = $item->$key; }  }
		
		
		for($i=0;$i<TQ_NUM_CHOICES;$i++)
		{ 
			$formValues['choice_'.$i]=$item->{'choice_'.$i}->choice_text; 
			$choice_ids[$i] = $item->{'choice_'.$i}->id;
		}	
		
	}
	
	
	
	if(count($_POST)>0)
	{ 
		foreach($formValues as $key => $val)
		{
			if(isset($_POST[$key])) $formValues[$key] = $_POST[$key];
		}
		if($formValues['title']==''){ $formIsValid = false; $formErrors['title'] = 'Please enter a title';}
		if($formValues['question_text']==''){ $formIsValid = false; $formErrors['question_text'] = 'Please enter some content';}
		
		

		
		
		if($formIsValid)
		{
			if(intVal($formValues['id'])>0){ $saved = tq_update_question($formValues); }
			else{ $saved = tq_create_question($formValues); }
		}
	}
	
	
?>




<div class="wrap ">
<?



	$displayForm = true;
	if(count($_POST)>0)
	{
		if($formIsValid){
			$displayForm = false;
			if($saved){ ?>
			<p>The item has been saved successfully.</p>	
			<? }else{ ?>
			<p>An error occurred while saving the item.</p>	
			<? }
		}else{ ?>
		<p>Some of the data that you have entered hasn't been accepted by the system.</p>
		<? 
		foreach($formErrors as $error){ echo "<p class=\"errorMessage\">$error</p>\n"; } 	
		
		}
		
	}
	
	if($displayForm){
	
?>
	<form method="post" action="<? echo str_replace('&','&amp;',$_SERVER['REQUEST_URI']); ?>">		
		<table class="tq_table">
		<thead><tr><th width="30%"></th><th width="10%"></th><th width="60%"></th></tr><thead>
			<tbody>		
				<tr>
					<td><label for="title">Title: </label></td>
					<td colspan="2"><input id="title" value="<?php echo $formValues['title']; ?>" name="title" type="text" class="tq_textfield" /></td>
				</tr>		
				<tr>
					<td><label for="question_text">Main content: </label></td>
					<td colspan="2"><textarea class="tq_textarea" id="question_text" name="question_text" ><?php echo $formValues['question_text']; ?></textarea></td>
				</tr>		
				<tr>
					<td><label for="question_picture">Picture: </label></td>
					<td><img class="picture_selector_preview" src="<?php echo TQ_URL; ?>images/no-image.jpg" /></td>
					<td>
					<select id="question_picture" class="picture_selector">
						<option value="0">Select ... </option>
					<?php include TQ_PATH.'forms/common/pictures-options.php'; ?>	
					</select>
					<input type="hidden" class="hidden_picture_id" name="question_picture" value="<?php echo $formValues['question_picture']; ?>" />
					</td>
				</tr>		
				<tr>
					<td><label for="quiz_post_id">Quiz post: </label></td>
					<td colspan="2">
					<select id="quiz_post_id" name="quiz_post_id">
						<option value="0">Select ... </option>
					<?php 
					$quiz_posts = get_posts(array('category'=>$GLOBALS['tq']['quiz_category_id'],'numberposts'=>999,'post_status'=>'any'));
					foreach($quiz_posts as $tmp)
					{
						if($formValues['quiz_post_id']==$tmp->ID){ $checked=' selected="selected" '; }else{ $checked=''; }
						echo '<option value="'.$tmp->ID.'" '.$checked.'>'.$tmp->post_title.'</option>';
					}
					?>	
					</select>					
					</td>
				</tr>



				
<?php 
$tmp_right_id=0;
for($i=0;$i<TQ_NUM_CHOICES;$i++){  
	
?>
				<tr>
					<td><label for="choice_<?php echo $i; ?>">Choice <?php echo ($i+1); ?>: </label></td>
					<td><label for="right_answer_<?php echo $i; ?>" >correct</label> <input type="radio" class="right_answer" name="right_answer" id="right_answer_<?php echo $i; ?>" value="<?php echo $i; ?>"  <?php if($formValues['right_id']==$choice_ids[$i]){ echo ' checked="checked" '; $tmp_right_id=$i; } ?>/></td>
					<td><input id="choice_<?php echo $i; ?>" name="choice_<?php echo $i; ?>" value="<?php echo str_replace('"','&quot;',$formValues['choice_'.$i]); ?>" type="text" class="tq_textfield"  /></td>
				</tr>	
<?php } ?>			
				<tr><td><input type="hidden" name="right_id" id="right_id" value="<?php echo $tmp_right_id; ?>" /></td><td colspan="2"><input type="submit" name="submitBtn" value="submit" /></td></tr>
			<tbody>
		</table>
	</form>
<? }else{ ?>
	<p><a href="<?php echo TQ_QUESTIONS_ADMIN_PAGE_URL; ?>">
	<img alt="return to listing page" src="<?php echo TQ_URL; ?>images/design/icons/folderparent.gif"/>
	Return to listing page</a></p>		
	<p><a href="<?php echo TQ_QUESTIONS_ADMIN_PAGE_URL; ?>&amp;tq_item_id=0">
	<img alt="create new item" src="<?=TQ_URL; ?>images/design/icons/new.gif"/>Create new item
	</a></p>


<? } ?>

</div>