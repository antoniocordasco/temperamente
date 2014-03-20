<?php
	function tn_redirectClient ($uri){	header ("Location: $uri"); exit; }


	
	function tn_get_newsletters($search, $onlyApproved, $pageNum = null,$entriesPerPage = null)
	{
		$sql = "SELECT * FROM wp_tn_newsletter WHERE 1=1 ";			
		$res = $GLOBALS['wpdb']->query($sql);
		
		if($pageNum===null){ 
			$items =  $GLOBALS['wpdb']->last_result;
		}else{ 
			$items = array();
			for($i= ($pageNum-1) * $entriesPerPage; $i < ($pageNum) * $entriesPerPage; $i++)
			{ 
				if(isset($GLOBALS['wpdb']->last_result[$i]))
				{ 
					$items[] = $GLOBALS['wpdb']->last_result[$i];					
				}
			}
		}
		return $items;		
	}
	
	
	
	
	function tn_create_newsletter($formValues)
	{	
		global $wpdb;	
		unset($formValues['id']);
		$formValues['created_by'] = $GLOBALS['current_user']->ID;		
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;		
		$formValues['created'] = date('Y-m-d h:i:s');
		$formValues['updated'] = date('Y-m-d h:i:s');		
		
		$wpdb->insert('wp_tn_newsletter', stripslashes_deep($formValues));
		$qzid = $wpdb->insert_id;
		if($qzid>0){ return true; }
		return false;
	}
	
	function tn_update_newsletter($formValues)
	{	
		global $wpdb;	
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;	
		$formValues['updated'] = date('Y-m-d h:i:s');
		
		$where = array('id' => intVal($formValues['id']));
		$wpdb->update('wp_tn_newsletter', stripslashes_deep($formValues),$where);
		
		return true;		
	}
	
	
	
		
	
	
	function tn_get_newsletter($id)
	{
		global $wpdb;
		if(intVal($id)>0)
		{			
			$rows = $wpdb->get_results('SELECT * FROM wp_tn_newsletter WHERE id = '.intVal($id));
			if(isset($rows[0]))
			{
				return $rows[0];
			}
		}	
		return false;	
	}
	
		
	function tn_newsletter_preview($itemId)
	{	
		$curr_usr = wp_get_current_user();
		$newsletter = tn_get_newsletter($itemId);
		echo '<p>sending newsletter "'.$newsletter->title.'" to '.$curr_usr->user_email.'</p>';
		
		$headers = 'From: Temperamente <noreply@temperamente.it>' . "\r\n\\";
	
		wp_mail($curr_usr->user_email, $newsletter->title, $newsletter->main_content, $headers, null );
	}	
	function tn_newsletter_send($itemId)
	{	
		$curr_usr = wp_get_current_user();
		$newsletter = tn_get_newsletter($itemId);
		echo '<p>sending newsletter "'.$newsletter->title.'" to all users</p>';
		
		$headers = 'From: Temperamente <noreply@temperamente.it>' . "\r\n\\";
		$recipients = tn_get_recipients();
		
		foreach($recipients as $rec)
		{
			wp_mail($rec->user_email, $newsletter->title, $newsletter->main_content, $headers, null );
		}
		tn_update_newsletter(array('sent'=>date('Y-m-d H:i:s'),'id'=>$newsletter->id));
	}	
	
	function tn_publish_newsletter($itemId)
	{	
		$GLOBALS['wpdb']->query("UPDATE wp_tn_newsletter SET status='active' WHERE id = ".intVal($itemId));
		return true;
	}	
	function tn_unpublish_newsletter($itemId)
	{	
		$GLOBALS['wpdb']->query("UPDATE wp_tn_newsletter SET status='inactive' WHERE id = ".intVal($itemId));
		return true;
	}		
	function tn_delete_newsletter($itemId)
	{
		$GLOBALS['wpdb']->query('DELETE FROM wp_tn_newsletter WHERE id = '.intVal($itemId));
		return true;			
	}
	
	
	
	
	function tn_get_recipients()
	{
		$sql="SELECT * FROM wp_users JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE meta_key='wp_user_level' AND meta_value >0";
		return $GLOBALS['wpdb']->get_results($sql);
	}
	
	
	
	
	
	
	
	