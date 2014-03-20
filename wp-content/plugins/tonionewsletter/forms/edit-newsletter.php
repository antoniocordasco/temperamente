<?
	
	$formErrors = array();
	$formIsValid = true;
	$saved = false;
	$imageUploaded = false;
	$formValues=array(
			'id'=>'',
			'title'=>'',
			'main_content'=>'',
		);
		
		
	if(isset($_GET['tn_item_id']) && intVal($_GET['tn_item_id'])>0){ 
		$item = tn_get_newsletter($_GET['tn_item_id']); 
		foreach($formValues as $key => $val){ $formValues[$key] = $item->$key; }
	}
	
	if(count($_POST)>0)
	{ 
		foreach($formValues as $key => $val)
		{
			if(isset($_POST[$key])) $formValues[$key] = $_POST[$key];
		}
		if($formValues['title']==''){ $formIsValid = false; $formErrors['title'] = 'Please enter a title';}
		if($formValues['main_content']==''){ $formIsValid = false; $formErrors['main_content'] = 'Please enter some content';}
		
		

		
		
		if($formIsValid)
		{
			if(intVal($formValues['id'])>0){ $saved = tn_update_newsletter($formValues); }
			else{ $saved = tn_create_newsletter($formValues); }
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
		<table class="tn_table">
		<thead><tr><th width="30%"></th><th width="70%"></th></tr><thead>
			<tbody>		
				<tr>
					<td><label for="title">Title: </label></td>
					<td><input id="title" value="<?php echo $formValues['title']; ?>" name="title" type="text" class="tn_textfield" /></td>
				</tr>		
				<tr>
					<td><label for="main_content">Main content: </label></td>
					<td><textarea class="tn_textarea" id="main_content" name="main_content" ><?php echo $formValues['main_content']; ?></textarea></td>
				</tr>			
			
				<tr><td></td><td><input type="submit" name="submitBtn" value="submit"/></td></tr>
			<tbody>
		</table>
	</form>
<? }else{ ?>
	
	<p><a href="<?php echo TONIONEWSLETTER_ADMIN_PAGE_URL; ?>">
	<img alt="return to listing page" src="<?php echo TONIONEWSLETTER_URL; ?>images/design/icons/folderparent.gif"/>
	Return to listing page</a></p>

<? } ?>

</div>