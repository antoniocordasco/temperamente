<?php 
	$sql= "SELECT * FROM {$GLOBALS['table_prefix']}postmeta WHERE (";
	foreach($GLOBALS['tpi_fields'] as $key => $val){ $sql  .= " meta_key='$key' OR "; }
	$sql  .= 'FALSE) AND post_id='.$GLOBALS['post_ID'];
		
	$res = $GLOBALS['wpdb']->get_results($sql);
	foreach($res as $row){ $GLOBALS['tpi_fields'][$row->meta_key] = $row->meta_value; }	
	
	
	
	
	

	
	
	$res = $GLOBALS['wpdb']->get_results("SELECT * FROM wp_tpi_book_author ORDER BY last_name ASC");
?>	
	<div class="postbox" id="tpi_contact_div"><h3 class="hndle"><span>Additional data</span></h3><div class="inside" >	
		<table>
		<tr>
			<td><label for="tpi_book_author_dropdown" >Book author:</label></td>
			<td><select  id="tpi_book_author_dropdown" name="tpi_book_author" >
				<option value="">No author selected</option>
<?php 	
foreach($res as $row)
{
	if($GLOBALS['tpi_fields']['tpi_book_author']==$row->id){ $sel=' selected="selected" '; }else{ $sel=''; }
	echo '<option '.$sel.'value="'.$row->id.'">'.$row->first_name.' '.$row->last_name.' </option>'; 	
}			
?>		
			</select></td>
		</tr>
		<tr>
			<td><label for="tpi_facebook_image_dropdown" >Facebook image:</label></td>
			<td><select  id="tpi_facebook_image_dropdown" name="tpi_facebook_image" >
				<option value="">No image selected</option>
<?php 	

$res = $GLOBALS['wpdb']->get_results("SELECT * FROM wp_toniofacebook_image WHERE name <>'' ORDER BY id DESC");
foreach($res as $row)
{
	if($GLOBALS['tpi_fields']['tpi_facebook_image']==$row->facebook_id){ $sel=' selected="selected" '; }else{ $sel=''; }
	
	echo '<option '.$sel.'value="'.$row->facebook_id.'">'.substr($row->name, 0, 80).' </option>'; 	
}			
?>		
			</select></td>
		</tr>	
		</table>
	</div></div>
	