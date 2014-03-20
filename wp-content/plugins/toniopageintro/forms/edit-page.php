<?php 
	$sql= "SELECT * FROM {$GLOBALS['table_prefix']}postmeta WHERE (";
	foreach($GLOBALS['tpi_fields'] as $key => $val){ $sql  .= " meta_key='$key' OR "; }
	$sql  .= 'FALSE) AND post_id='.$GLOBALS['post_ID'];
		
	$res = $GLOBALS['wpdb']->get_results($sql);
	foreach($res as $row){ $GLOBALS['tpi_fields'][$row->meta_key] = $row->meta_value; }	
	
	
	
	
	

	
	
	$res = $GLOBALS['wpdb']->get_results("SELECT * FROM {$GLOBALS['table_prefix']}users ORDER BY user_login ASC");
?>	
	<div class="postbox" id="tpi_contact_div"><h3 class="hndle"><span>Additional data</span></h3><div class="inside" >	
			<label for="tpi_posts_list_author" >List posts by this author:</label>
			<select name="tpi_posts_list_author" id="tpi_posts_list_author" >
				<option value="">No author selected</option>
<?php 	
foreach($res as $row)
{
	if($GLOBALS['tpi_fields']['tpi_posts_list_author']==$row->ID){ $sel=' selected="selected" '; }else{ $sel=''; }
	echo '<option '.$sel.'value="'.$row->ID.'">'.$row->user_login.'</option>'; 	
}			
?>		
			</select>
	</div></div>
	