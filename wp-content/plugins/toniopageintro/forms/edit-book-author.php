<?
	
	$formErrors = array();
	$formIsValid = true;
	$saved = false;
	$imageUploaded = false;
	$formValues=array(
			'id'=>'',
			'first_name'=>'',
			'last_name'=>'',
			'folder_name'=>'',
		);
		
		
	if(isset($_GET['tpi_authors_item_id']) && intVal($_GET['tpi_authors_item_id'])>0){ 
		$item = tpi_get_book_author($_GET['tpi_authors_item_id']); 
		foreach($formValues as $key => $val){ $formValues[$key] = $item->$key; }
	}
	
	if(count($_POST)>0)
	{ 
		foreach($formValues as $key => $val)
		{
			if(isset($_POST[$key])) $formValues[$key] = $_POST[$key];
		}
		if($formValues['first_name']=='' && $formValues['last_name']==''){ $formIsValid = false; $formErrors['first_name'] = 'Please enter the author\'s name';}
		if($formValues['folder_name']==''){ $formIsValid = false; $formErrors['folder_name'] = 'Please enter the folder name';}
		
		

		
		
		if($formIsValid)
		{
			if(intVal($formValues['id'])>0){ $saved = tpi_update_author($formValues); }
			else{ $saved = tpi_create_author($formValues); }
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
					<td><label for="first_name">First name: </label></td>
					<td><input id="first_name" value="<?php echo $formValues['first_name']; ?>" name="first_name" type="text"  /></td>
				</tr>		
				<tr>
					<td><label for="last_name">Last name: </label></td>
					<td><input id="last_name" value="<?php echo $formValues['last_name']; ?>" name="last_name" type="text"  /></td>
				</tr>				
				<tr>
					<td><label for="folder_name">Folder name: </label></td>
					<td><input id="folder_name" value="<?php echo $formValues['folder_name']; ?>" name="folder_name" type="text"  /></td>
				</tr>			
			
				<tr><td></td><td><input type="submit" name="submitBtn" value="submit"/></td></tr>
			<tbody>
		</table>
	</form>
<? }else{ ?>
	
	

<? } ?>

</div>