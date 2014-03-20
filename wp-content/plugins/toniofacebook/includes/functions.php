<?php

function tfb_db_maintenance()
{	
	$GLOBALS['wpdb']->query("DELETE FROM wp_toniofacebook_user WHERE created < '" . date('Y-m-d H:i:s',time()-10*24*60*60) . "'; #tf ");
}



function tfb_get_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $tmp = curl_exec($ch);
    curl_close($ch);
    return $tmp;
}

function tfb_get_access_token()
{
    if(isset($_SESSION['tfb_access_token'])){ 
		return $_SESSION['tfb_access_token']; 
	}else{ 
		return false; 
	}
}



function tfb_get_stored_user($id_or_email, $id_type)
{
    if($id_type == 'email')
	{ 
		$rows = $GLOBALS['wpdb']->get_results("SELECT * FROM wp_toniofacebook_user WHERE email='" . mysql_real_escape_string($id_or_email) . "'; #tf");
		if(count($rows)>0){ return $rows[0]; }
	}	
	return false;
}


function tfb_store_user($data)
{
	$sql = "INSERT INTO wp_toniofacebook_user (email,created,fb_id)	VALUES ('" . mysql_real_escape_string( $data['email'] ) . "','" . date('Y-m-d H:i:s') . "','" . $data['id'] . "'); #tf";
    $GLOBALS['wpdb']->query( $sql );		
}
function tfb_log_authorization($ip,$authorized)
{
	$sql = "INSERT INTO wp_toniofacebook_app_authorization (ip,created,authorized)	VALUES ('" . mysql_real_escape_string( $ip ) . "','" . date('Y-m-d H:i:s') . "','" . $authorized . "'); #tf";
    $GLOBALS['wpdb']->query( $sql );		
}

function tfb_get_comments($post_id)
{
	$sql = "SELECT wp_toniofacebook_post_comment.* FROM wp_toniofacebook_post JOIN wp_toniofacebook_post_comment 
			ON wp_toniofacebook_post.facebook_id=wp_toniofacebook_post_comment.fb_post_id
			WHERE wp_toniofacebook_post.wp_post_id='$post_id' AND comment_id<>'0'; #tf ";	
	return $GLOBALS['wpdb']->get_results($sql);
}

function tfb_get_image_data($fb_image_id) {
	$sql = "SELECT * FROM wp_toniofacebook_image WHERE facebook_id = '".mysql_real_escape_string($fb_image_id)."'; ";
//	echo $sql;
	$rows = $GLOBALS['wpdb']->get_results($sql);
	if(isset($rows[0])) {
		$return = array('image' => $rows[0]);
		$sql = 'SELECT * FROM wp_toniofacebook_image_comment WHERE fb_image_id = '.$rows[0]->facebook_id;
		$return['comments'] = $GLOBALS['wpdb']->get_results($sql);
		return $return;
	}
	return false;
}




function tfb_store_facebook_post_comments($comments)
{
	foreach($comments as $comment)
	{
		$cols = array();
		$vals = array();	
		foreach($comment as $key => $val)
		{
			$cols[] = $key;
			$vals[] = mysql_real_escape_string($val);
		}
		$sql = "INSERT INTO wp_toniofacebook_post_comment (". implode(',',$cols) .") VALUES ('". implode("','",$vals) ."'); #tf";
		
		$GLOBALS['wpdb']->query($sql);
	}
}


function tfb_store_facebook_post($post_data)
{

		foreach($post_data as $key => $val)
		{
			$cols[] = $key;
			$vals[] = mysql_real_escape_string($val);
		}
	$sql = "INSERT INTO wp_toniofacebook_post (". implode(",",$cols) .") VALUES ('". implode("','",$vals) ."'); #tf";
	// echo $sql.'<br>';
	$GLOBALS['wpdb']->query($sql);
	
	
	
}




function tfb_fetch_latest_images($album_id) {
	// photos are sorted by date ascending, so we need to put a starting date, to get the latest photos
	$req_url = TFB_GRAPH_SECURE_URL . $album_id . '/photos?since='.date('Y-m-d', (time()-10*24*60*60)).'&access_token=' . tfb_get_access_token();	
	// echo 'Graph url: '.$req_url; //die;
	$images_json = json_decode(tfb_get_url($req_url));
	
	$images_data = array();
	$comments_data = array();
	$images_fb_ids = array();
	foreach($images_json->data as $image) {
		echo '<b>Image name:</b> '.substr($image->name,0,50).'<br/>';
		$images_fb_ids[] = $image->id;
		$images_data[] = array(
			'name' => $image->name,
			'link' => $image->link,
			'facebook_id' => $image->id,
			'picture' => $image->picture,
			'source' => $image->source,
			'created' => date('Y-m-d H:i:s', strtotime($image->created_time))
		);	
		if(isset($image->comments)) {
			foreach($image->comments->data as $comment) {
				$comments_data[] = array(
					'comment_id'=>$comment->id,
					'from_name'=>$comment->from->name,
					'from_id'=>$comment->from->id,
					'created'=>$comment->created_time,
					'message'=>$comment->message,
					'fb_image_id'=>$image->id
				);	
			}
		}
	}
	tfb_delete_facebook_images($images_fb_ids);
	
	tfb_store_facebook_images($images_data);
	tfb_store_facebook_image_comments($comments_data);
	
}

function tfb_store_facebook_images($images) {
	$inserts = array();
	foreach($images as $image)
	{
		$cols = array();
		$vals = array();	
		foreach($image as $key => $val){ $vals[] = mysql_real_escape_string($val); }
		$inserts[] = "('". implode("','",$vals) ."')";
	}
	$cols = array();
	foreach($images[0] as $k => $v){ $cols[] = $k; }	
	$sql = 'INSERT INTO wp_toniofacebook_image (' . implode(',', $cols) . ') VALUES ' . implode(",\n", $inserts) . '; #tf';
//	echo $sql.'<br/>';
	$GLOBALS['wpdb']->query($sql);	
}
function tfb_store_facebook_image_comments($comments) {
	$inserts = array();
	foreach($comments as $item)
	{
		$cols = array();
		$vals = array();	
		foreach($item as $key => $val){ $vals[] = mysql_real_escape_string($val); }
		$inserts[] = "('". implode("','",$vals) ."')";
	}
	$cols = array();
	foreach($comments[0] as $k => $v){ $cols[] = $k; }	
	$sql = 'INSERT INTO wp_toniofacebook_image_comment (' . implode(',', $cols) . ') VALUES ' . implode(",\n", $inserts) . '; #tf';
	
	$GLOBALS['wpdb']->query($sql);	
}


function tfb_fetch_latest_posts() {

	$req_url = TFB_GRAPH_SECURE_URL . TFB_PAGE_ID. '/posts?access_token=' . tfb_get_access_token();	
	// echo $req_url.'<br>'; 
	$posts_json = json_decode(tfb_get_url($req_url));
	
	
	// tfb_log_debug_ajax($output);
	$posts_data = array();		
	$comments_data = array();		
	$posts_fb_ids = array();
	foreach($posts_json->data as $post) {
	// var_dump($post); die;
		$wp_post_id = 0;
		if(isset($post->link)) { 
		//	echo $post->link.'<br>';
			$wp_post_id = tfb_get_wp_post_id_from_url($post->link); 
		} else {
		
		}
		echo '<b>Message:</b> '.substr($post->message,0,50).'<br>';
		$posts_data[] = array(
			'message' => $post->message,
			'facebook_id' => $post->id,
			'from_name' => $post->from->name,
			'from_id' => $post->from->id, 
			'created' => date('Y-m-d H:i:s', strtotime($post->created_time)), 
			'wp_post_id' => $wp_post_id
		);	
		$posts_fb_ids[] = $post->id;
		
		if(isset($post->comments) AND isset($post->comments->data)) {
			foreach($post->comments->data as $comment) {
				$comments_data[] = array(
					'comment_id'=>$comment->id,
					'from_name'=>$comment->from->name,
					'from_id'=>$comment->from->id,
					'created'=>$comment->created_time,
					'message'=>$comment->message,
					'fb_post_id'=>$post->id
				);	
			}
		}
	}
	
	tfb_delete_facebook_posts($posts_fb_ids);
	foreach($posts_data as $post_data) { 
		tfb_store_facebook_post($post_data);
	}
	tfb_store_facebook_post_comments($comments_data);
}

function tfb_delete_facebook_posts($ids) {
	$sql = 'DELETE FROM wp_toniofacebook_post WHERE facebook_id IN (\''.implode("','",$ids).'\'); #tf';
//	echo $sql;
	$GLOBALS['wpdb']->query($sql);
	$sql = 'DELETE FROM wp_toniofacebook_post_comment WHERE fb_post_id IN (\''.implode("','",$ids).'\'); #tf';
//	echo $sql;
	$GLOBALS['wpdb']->query($sql);
}

function tfb_delete_facebook_images($ids) {
	$sql = 'DELETE FROM wp_toniofacebook_image WHERE facebook_id IN (\''.implode("','",$ids).'\'); #tf';
	$GLOBALS['wpdb']->query($sql);
	$sql = 'DELETE FROM wp_toniofacebook_image_comment WHERE fb_image_id IN (\''.implode("','",$ids).'\'); #tf';
	$GLOBALS['wpdb']->query($sql);
}


function tfb_get_wp_post_id_from_url($url) {
	$split = explode('?', $url);
	$split = explode('/', $split[0]);
	if($split[count($split)-1] != '') {
		$post_name = $split[count($split)-1];
	} else {
		$post_name = $split[count($split)-2];
	}
	$sql = "SELECT ID FROM wp_posts WHERE post_name='$post_name' OR post_name='".urlencode($post_name)."'  OR post_name='".urldecode($post_name)."' LIMIT 1; #tf";
//	echo $sql.'<br>'; 
	$rows = $GLOBALS['wpdb']->get_results($sql);
	if(count($rows)==1) {
		return $rows[0]->ID;
	} else {
		
		return 0;
	}
}


