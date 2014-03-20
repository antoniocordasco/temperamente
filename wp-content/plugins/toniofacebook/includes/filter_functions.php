<?php

function tfb_get_avatar($avatar, $id_or_email, $size, $default, $alt)
{ 
	if(is_object($id_or_email) AND isset($id_or_email->facebook_id))
	{ 
		$fb_img = TFB_GRAPH_URL . $id_or_email->facebook_id . '/picture';
		return "<img alt='fb_avatar' src='$fb_img' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	} 
	
	if(!is_object($id_or_email) || ! isset($id_or_email->comment_ID)){ return $avatar; } 
	
	
	$stored_user = tfb_get_stored_user($id_or_email->comment_author_email, 'email');	
	if($stored_user)
	{
		if($stored_user->fb_id > 0)
		{
			$fb_img = TFB_GRAPH_URL . $stored_user->fb_id . '/picture';
			return "<img alt='fb_avatar' src='$fb_img' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}else{
			return $avatar;
		}
	}
	
	if(tfb_get_access_token()) // search for the user only if there is an access token in the session
	{
		$url = TFB_GRAPH_SECURE_URL . 'search?access_token=' . tfb_get_access_token() . '&q=' . $id_or_email->comment_author_email . '&type=user';
		
		$user_json = json_decode(tfb_get_url($url));
		
		if(!isset($user_json->data) || count($user_json->data)<=0)
		{ 
			tfb_store_user(array('email' => $id_or_email->comment_author_email, 'id' => 0));
			return $avatar; 
		}
		$fb_user_id = $user_json->data[0]->id;
		
		tfb_store_user(array('email' => $id_or_email->comment_author_email, 'id' => $fb_user_id));
		
		$fb_img = TFB_GRAPH_URL . $fb_user_id . '/picture';
		return "<img alt='fb_avatar' src='$fb_img' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	}	
	return $avatar; 
}







function tfb_add_facebok_post_comments($comments)
{ 
	global $post;
	$comments_not_sorted = array();
	$comments_facebook = tfb_get_comments($post->ID);
	if(count($comments_facebook )>0)
	{
		$return = array();
		foreach($comments as $tmp)
		{
			$comments_total[strtotime($tmp->comment_date)] = array('comment'=>$tmp, 'type' => 'wp');
		}
		foreach($comments_facebook as $tmp)
		{
			$comments_total[strtotime($tmp->created)] = array('comment'=>$tmp, 'type' => 'fb');
		}
		
		ksort($comments_total);
		foreach($comments_total as $k => $v)
		{	
			if($v['type']=='fb')
			{	
				$obj = new stdClass();
				$obj->comment_post_ID = $v['comment']->comment_id;
				$obj->comment_author = $v['comment']->from_name . ' (facebook)';
				$obj->comment_author_email='';
				$obj->comment_author_url = TFB_PAGE_URL;
				$obj->comment_author_IP='';
				$obj->comment_date = $v['comment']->created;
				$obj->comment_date_gmt = $v['comment']->created;
				$obj->comment_content = $v['comment']->message;
				$obj->comment_karma=0;
				$obj->comment_approved=1;
				$obj->comment_agent='';
				$obj->comment_parent=0;
				$obj->user_id=0;
				$obj->facebook_id=$v['comment']->from_id;
				$return[] = $obj;				
			}else{	
				$return[] = $v['comment'];				
			}			
		}		
		return $return;
	}
	
	
	return $comments;
}
