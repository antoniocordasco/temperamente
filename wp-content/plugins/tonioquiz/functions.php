<?php
	include 'globals.php';
	include 'includes/frontend-functions.php';
	include 'includes/hooks.php';
	
	
	
	function delete_all_responses($quiz_id)
	{ 
		$result_sets_ids = array();
		$rows = $GLOBALS['wpdb']->get_results("SELECT id FROM wp_tq_answers_set WHERE quiz_id ='$quiz_id'; ");
		foreach($rows as $row){ $result_sets_ids[] = $row->id; }
		$sql1 = 'DELETE FROM wp_tq_answer WHERE answers_set_id IN ('.implode(',',$result_sets_ids).'); ';
		$sql2 = "DELETE FROM wp_tq_answers_set WHERE quiz_id ='$quiz_id'; ";
		
	//	echo $sql1.'<br>'.$sql2.'<br>';
		
		$GLOBALS['wpdb']->query($sql1);
		$GLOBALS['wpdb']->query($sql2);
		
	}
	
	
	
	
	
	function tq_get_questions($pageNum = null,$entriesPerPage = null,$quiz_id = null)
	{
		$sql = "SELECT * FROM wp_tq_question WHERE 1=1 ";	
    if(intval($quiz_id)>0){ $sql .= ' AND quiz_post_id = '.intval($quiz_id); }
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
	
	
	function tq_get_quizzes($pageNum = null,$entriesPerPage = null)
	{
		$posts = get_posts(array('category'=>$GLOBALS['tq']['quiz_category_id'],'post_status'=>'any', 'orderby'=>'modified', 'order'=>'DESC'));
			
		$sql = "SELECT * FROM wp_tq_question WHERE 1=1 ";			
		$res = $GLOBALS['wpdb']->query($sql);		
		if($pageNum===null){ 
			$items =  $posts;
		}else{ 
			$items = array();
			for($i= ($pageNum-1) * $entriesPerPage; $i < ($pageNum) * $entriesPerPage; $i++)
			{ 
				if(isset($posts[$i]))
				{ 
					$items[] = $posts[$i];					
				}
			}
		}
		return $items;		
	}
	
	
	
	
	
	
	
	
	
		
	
	function tq_create_question($formValues)
	{	
		global $wpdb;	
		unset($formValues['id']);
		$formValues['created_by'] = $GLOBALS['current_user']->ID;		
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;		
		$formValues['created'] = date('Y-m-d h:i:s');
		$formValues['updated'] = date('Y-m-d h:i:s');		
		$choices = array();
		for($i=0;$i<TQ_NUM_CHOICES;$i++)
		{ 
			$choices[$i] = $formValues['choice_'.$i]; 
			unset($formValues['choice_'.$i]); 
		}
		$right_id = $formValues['right_id'];
		unset($formValues['right_id']);
	//	var_dump($formValues); die;
		$wpdb->insert('wp_tq_question', stripslashes_deep($formValues));		
		$qzid = $wpdb->insert_id;
		
		foreach($choices as $key => $val)
		{  
			
			if($key==$right_id){ $right = 1; }else{ $right = 0; }
			
			$wpdb->insert('wp_tq_choice', 
			stripslashes_deep(array('choice_text'=>$val,'question_id'=>$qzid,'right'=>$right))
			);				
		}
		
		if($qzid>0){ return true; }
		return false;
	}
	

	
		
	
	function tq_get_question($id)
	{
		global $wpdb;
		if(intVal($id)>0)
		{			
			$rows = $wpdb->get_results('SELECT wp_tq_question.*,wp_posts.guid AS image_url FROM wp_tq_question LEFT JOIN wp_posts ON wp_tq_question.question_picture=wp_posts.ID  WHERE wp_tq_question.id = '.intVal($id));
			if(isset($rows[0]))
			{
				$return = $rows[0];
				$return->right_id=0;
				$rows = $wpdb->get_results('SELECT * FROM wp_tq_choice WHERE question_id = '.intVal($id).' ORDER BY id ASC');
				foreach($rows as $key => $val)
				{
					$return->{'choice_'.$key} = $val;
					if($val->right==1){ $return->right_id=$val->id; }
				}
				return $return;
			}
		}	
		return false;	
	}

	
	
	
	
		
	function tq_update_question($formValues)
	{	
	//	var_dump($formValues); echo '<br><br>';
		global $wpdb;	
		$formValues['updated_by'] = $GLOBALS['current_user']->ID;	
		$formValues['updated'] = date('Y-m-d h:i:s');
		$choices = array();
		for($i=0;$i<TQ_NUM_CHOICES;$i++)
		{ 
			$choices[$i] = $formValues['choice_'.$i]; 
			unset($formValues['choice_'.$i]); 
		}
		$where = array('id' => intVal($formValues['id']));
		
		$right_id = $formValues['right_id'];
		unset($formValues['right_id']);
		
		$wpdb->update('wp_tq_question', stripslashes_deep($formValues),$where);
		
		
		$wpdb->get_results('DELETE FROM wp_tq_choice WHERE question_id='.intVal($formValues['id']));
		foreach($choices as $key => $val)
		{  
			
			if($key==$right_id){ $right = 1;  }else{ $right = 0; }
			
			$wpdb->insert('wp_tq_choice', 
			stripslashes_deep(array('choice_text'=>$val,'question_id'=>$formValues['id'],'right'=>$right))
			);				
		}
		return true;		
	}
	
	
	function tq_delete_question($item_id)
	{
		global $wpdb;	
		$wpdb->get_results('DELETE FROM wp_tq_question WHERE id='.intval($item_id));	
		$wpdb->get_results('DELETE FROM wp_tq_choice WHERE question_id='.intval($item_id));	
	}
	
	
	
	
	
// sql to get all answers of a certain set	
// SELECT wp_tq_answer.*,wp_tq_choice.RIGHT,wp_tq_question.question_text FROM wp_tq_answer JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id JOIN wp_tq_question ON wp_tq_choice.question_id=wp_tq_question.id WHERE  answers_set_id='147'	
	
	function tq_get_quiz_responses_list($quiz_id, $only_completed)
	{ 
		$return = array();
		$sql = "SELECT answers_set_id, COUNT(wp_tq_answer.id) AS num_right FROM wp_tq_answers_set JOIN wp_tq_answer ON wp_tq_answers_set.id=wp_tq_answer.answers_set_id  JOIN wp_tq_choice ON wp_tq_answer.choice_id=wp_tq_choice.id WHERE ";
		if(intVal($quiz_id)>0) {
			$sql .= " wp_tq_answers_set.quiz_id='$quiz_id' AND ";
		}
		$sql .= " wp_tq_choice.RIGHT='1' GROUP BY answers_set_id";		
	//	echo $sql;
		
		$return['right_answers'] = $GLOBALS['wpdb']->get_results($sql,ARRAY_A);
		$cols = 'wp_tq_answers_set.id,wp_tq_answers_set.created,wp_tq_answers_set.updated,wp_tq_answers_set.question_ids,wp_tq_answers_set.ip,wp_tq_answers_set.name,wp_tq_answers_set.email,wp_tq_answers_set.points,wp_tq_answers_set.newsletter';
		$sql = "SELECT $cols,COUNT(wp_tq_answer.id) AS num_answers, CONVERT((wp_tq_answers_set.updated - wp_tq_answers_set.created),UNSIGNED) AS seconds
				FROM wp_tq_answers_set LEFT JOIN wp_tq_answer ON wp_tq_answers_set.id = wp_tq_answer.answers_set_id WHERE 1=1";
				
		if(intVal($quiz_id)>0) {
			$sql .= " AND wp_tq_answers_set.quiz_id='$quiz_id'  ";
		}		
		
		
		if($only_completed){ $sql.= " AND email<>'' "; }		
		$sql.= ' GROUP BY wp_tq_answers_set.id ORDER BY wp_tq_answers_set.id DESC';
		
	//	echo $sql; die;
		$return['responses'] = $GLOBALS['wpdb']->get_results($sql,ARRAY_A);
		return $return;
	}
	
