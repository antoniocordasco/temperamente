<?php


function tfb_fetch_post_coments()
{
	global $post;
	$comments = array();
	$rows = $GLOBALS['wpdb']->get_results("SELECT id FROM wp_toniofacebook_post_comment WHERE post_id='".$post->ID."'; #tf");
	
	$days_old = ceil((time() - strtotime($post->post_date)) / (24*60*60)); // this is done so that newer posts have their FB comments re-fetched more often
	$random = rand(0,ceil($days_old/2));
	
	if(count($rows)>0 AND $random>0){ return; } // only fetch comments from FB if they haven't been fetched already
	
	if(tfb_get_access_token() AND is_single())
	{	
		$GLOBALS['wpdb']->query("DELETE FROM wp_toniofacebook_post_comment WHERE post_id = '{$post->ID}'; #tf"); // deleting any stored comments because we are fetching them from FB graph
		
		$post_date = strtotime($post->post_date);
		$limit = intval( (time() - $post_date) / (24*60*60)) * 2; // setting the limit on an average of 2 links per day		
		
		$links_url = TFB_GRAPH_SECURE_URL . TFB_PAGE_ID. '/links?access_token=' . tfb_get_access_token();	
		$links_url .= '&since=' . ($post_date-2*24*60*60) . '&until=' . ($post_date+2*24*60*60) . '&limit=' . $limit;		
	//	echo $links_url; die;
		$json_links = json_decode(tfb_get_url($links_url));
		
		if($json_links )
		{		
			foreach($json_links->data as $curr_link)
			{
				if(strpos($curr_link->link, urldecode($post->post_name)))
				{
				
					$link_id = $curr_link->id;
					$comments_url = TFB_GRAPH_SECURE_URL . $link_id . '?access_token=' . tfb_get_access_token();
				//	echo $curr_link->link.' '.urldecode($post->post_name).' '.$comments_url.'<br>'; die;
					$json_link = json_decode(tfb_get_url($comments_url));					
					if($json_link AND $json_link->comments AND $json_link->comments->data)
					{
						foreach($json_link->comments->data as $json_comment)
						{						
							$comments[] = array(
							'post_id'=>$post->ID,
							'comment_id'=>$json_comment->id,
							'from_name'=>$json_comment->from->name,
							'from_id'=>$json_comment->from->id,
							'created'=>$json_comment->created_time,
							'message'=>$json_comment->message						
							);						
						}
						tfb_store_facebook_post_comments($comments);
						return;					
					}
				}
			}			
		}
		
		tfb_store_facebook_post_comments(array( 0 => array(
							'post_id'=>$post->ID,
							'comment_id'=>'0',
							'from_name'=>'',
							'from_id'=>'0',
							'created'=>'0',
							'message'=>'no comments on facebook for this post'						
							)
							));
	}
	
	
}