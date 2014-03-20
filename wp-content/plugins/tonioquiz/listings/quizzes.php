<?php

	require_once(TQ_PATH.'classes/Pagination.php');

	if(isset($_GET['delete_all_responses'])	&& intval($_GET['delete_all_responses'])>0)
	{
		delete_all_responses(intval($_GET['delete_all_responses']));
	}
	
	$pageNum = intVal($_GET['pageNum']);
	if($pageNum==0) $pageNum=1;
	
	if(isset($_GET['tq_show_responses']))
	{
	
		include(TQ_PATH.'listings/quiz-responses.php');
		
	}else{
		
		$items = tq_get_quizzes($pageNum,10);
		$itemsTotal = tq_get_quizzes(null,null);
		
		
		
		
		$occurrencies_post_id = array();
		$rows = $GLOBALS['wpdb']->get_results('SELECT quiz_post_id,COUNT(id) AS occurrencies FROM wp_tq_question GROUP BY  quiz_post_id');
		foreach($rows as $row){ $occurrencies_post_id[$row->quiz_post_id] = $row->occurrencies; }
		
		
		$occurrencies_responses = array();
		$rows = $GLOBALS['wpdb']->get_results('SELECT COUNT(id) AS occurrencies,quiz_id FROM wp_tq_answers_set WHERE quiz_id>0 GROUP BY quiz_id');
		foreach($rows as $row){ $occurrencies_responses[$row->quiz_id] = $row->occurrencies; }
		
		$occurrencies_responses_completed = array();
		$rows = $GLOBALS['wpdb']->get_results("SELECT COUNT(id) AS occurrencies,quiz_id FROM wp_tq_answers_set WHERE quiz_id>0 AND email<>'' GROUP BY quiz_id");
		foreach($rows as $row){ $occurrencies_responses_completed[$row->quiz_id] = $row->occurrencies; }
		
		foreach($itemsTotal as $tmp)
		{ 
			if(!isset($occurrencies_responses[$tmp->ID])){ $occurrencies_responses[$tmp->ID] = 0; }
			if(!isset($occurrencies_responses_completed[$tmp->ID])){ $occurrencies_responses_completed[$tmp->ID] = 0; }
			if(!isset($occurrencies_post_id[$tmp->ID])){ $occurrencies_post_id[$tmp->ID] = 0; }
		}
?>

<p><a href="./post-new.php"><img alt="create new item" src="<?=TQ_URL; ?>images/design/icons/new.gif"/>Create new item</a></p>






<?php

		$pagination = new CmsPagination(count($itemsTotal));
		$pagination->display(); 
?>
<table class="widefat">
	<thead>
		<tr><th width="150">Title</th><th width="150">Actions</th><th width="40">Status</th><th width="40">Questions</th><th width="40">Responses</th><th width="40">Completed</th>
		
	</thead>
	<tbody> <?php
	foreach($items as $item)
	{
		$deleteUrl = TQ_QUESTIONS_ADMIN_PAGE_URL.'&tq_item_id_delete='.$item->id;
		$editUrl = TQ_QUESTIONS_ADMIN_PAGE_URL.'&tq_item_id='.$item->id;
		
		
		?><tr <?php if($occurrencies_post_id[$item->ID]<10){ echo ' class="tq_alert" '; } ?>>			
			<td><?= $item->post_title; ?></td>			
			<td><a href="/wp-admin/post.php?post=<?php echo $item->ID; ?>&action=edit" >Edit post</a><?php
			if($item->post_status='publish')
			{ 
				echo ' | <a href="'.get_permalink($item->ID).'?show_ranking=true" target="_blank" >View ranking</a>'; 
			}
			
			if($occurrencies_responses[$item->ID]>0){ echo ' | <a title="'.$item->post_title.'" href="'.TQ_QUIZZES_ADMIN_PAGE_URL.'&delete_all_responses='.$item->ID.'"  class="warning_delete_all_responses" >Delete all responses</a>'; }
			?></td>
			<td><?= $item->post_status; ?></td>
			<td><?php echo $occurrencies_post_id[$item->ID]; ?></td>
			<td><?php echo '<a ';
			if($occurrencies_responses[$item->ID]>100){ echo ' class="tq_responses_confirm" '; }
			echo ' href="'.TQ_QUIZZES_ADMIN_PAGE_URL.'&tq_show_responses='.$item->ID.'">'.$occurrencies_responses[$item->ID].'</a>'; ?></td>
			<td><?php echo '<a ';
			if($occurrencies_responses_completed[$item->ID]>100){ echo ' class="tq_responses_confirm" '; }
			echo ' href="'.TQ_QUIZZES_ADMIN_PAGE_URL.'&tq_show_responses='.$item->ID.'&tq_only_completed=true">'.$occurrencies_responses_completed[$item->ID].'</a>'; ?></td>
			
		</tr><?php
	}

	?> 
		<tr  >			
			<td></td><td></td><td></td><td></td>
			<td><a <?php echo ' class="tq_responses_confirm" href="'.TQ_QUIZZES_ADMIN_PAGE_URL.'&tq_show_responses=0">Show all</a>'; ?>	</td>
			<td><?php echo '<a  class="tq_responses_confirm"  href="'.TQ_QUIZZES_ADMIN_PAGE_URL.'&tq_show_responses=0&tq_only_completed=true">Show all</a>'; ?></td>			
		</tr>
	
	
	</tbody>
</table>	
		
		
<?php
		$pagination->display();
	
	
	
		
		foreach($itemsTotal as $item)
		{
			if($occurrencies_post_id[$item->ID]<10){ $temp_html .= $item->post_title.'<br/>'; }	
		}	
		if($tmp_html!=''){ echo "<p>These quizzes don't have a sufficient number of questions:<br/>$temp_html</p>"; }
		
	
	}
	