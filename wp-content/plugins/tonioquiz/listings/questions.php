<?php
	$items_per_page = 20;

	require_once(TQ_PATH.'classes/Pagination.php');

	$tryDelete = false;
	if(isset($_GET['tq_item_id_delete']) && intVal($_GET['tq_item_id_delete'])>0) 
	{ 
		$tryDelete = true;
		$deleted = tq_delete_question($_GET['tq_item_id_delete']);
	}
	
	

	
	
	$pageNum = intVal($_GET['pageNum']);
	if($pageNum==0) $pageNum=1;
	
//	$search = array('tpoll_search'=>'');
	if(isset($_GET['tq_filter_quiz_id']) && intval($_GET['tq_filter_quiz_id'])>0)
  {
    $filter_quiz_id = intval($_GET['tq_filter_quiz_id']);
  }else{
    $filter_quiz_id = null;
  }
	
	$items = tq_get_questions($pageNum,$items_per_page,$filter_quiz_id);
	$itemsTotal = tq_get_questions(null,null,$filter_quiz_id);


if($tryDelete){
	if($deleted){
		echo '<p>Item deleted successfully</p>';
	}else{
		echo '<p>An error occurred while deleting the element</p>';
	}

}


	$occurrencies_answers = array();
	$sql = 'SELECT COUNT(wp_tq_answer.id) AS occurrencies , question_id
			FROM wp_tq_choice JOIN wp_tq_answer ON wp_tq_choice.id=wp_tq_answer.choice_id
			JOIN wp_tq_question ON wp_tq_choice.question_id=wp_tq_question.id GROUP BY question_id';
	$rows = $GLOBALS['wpdb']->get_results($sql);
	foreach($rows as $row){ $occurrencies_answers[$row->question_id] = $row->occurrencies; }
	
	foreach($itemsTotal as $tmp)
	{ 
		if(!isset($occurrencies_answers[$tmp->id])){ $occurrencies_answers[$tmp->id] = 0; }
		
	}





?>


<p><a href="<?php echo TQ_QUESTIONS_ADMIN_PAGE_URL; ?>&amp;tq_item_id=0">
<img alt="create new item" src="<?=TQ_URL; ?>images/design/icons/new.gif"/>Create new item
</a></p>

<p>
  <form action="<?php echo TQ_QUESTIONS_ADMIN_PAGE_URL; ?>" method="get" >
    <select name="tq_filter_quiz_id" >
      <option value="0">All</option>
<?php
  $quizzes = tq_get_quizzes(null,null);
  foreach($quizzes as $tmp)
  { 
  if($filter_quiz_id == $tmp->ID){ $sel = ' selected="selected" '; }else{ $sel = ''; }
  echo '<option value="'.$tmp->ID.'" '.$sel.'>'.$tmp->post_title.'</option>'; 
  }
?>      
    </select>
    <input type="submit" value="submit" /><input type="hidden" name="page" value="tq_questions_admin_page" />
  </form>
</p>


<? 

	$pagination = new CmsPagination(count($itemsTotal));
	
	$pagination->set_entries_per_page($items_per_page);
	
	$pagination->display(); 
?>
<table class="widefat">
	<thead>
		<tr><th width="200">Title</th><th width="20">Quiz id</th><th width="80">Created</th><th width="80">Updated</th>
		<th width="10">Answers</th><th width="10">Edit</th><th width="10">Delete</th>		</tr>
	</thead>
	<tbody> <?php
	
	$row_class=='tq_odd';
	foreach($items as $item)
	{ 
		
		
		$deleteUrl = TQ_QUESTIONS_ADMIN_PAGE_URL.'&tq_item_id_delete='.$item->id;
		$editUrl = TQ_QUESTIONS_ADMIN_PAGE_URL.'&tq_item_id='.$item->id;
				
		if ($occurrencies_answers[$item->id]<=0 ){
			$deleteLink = '<a href="'.$deleteUrl.'" onclick="return confirm(\'Delete this item?\')"><img src="'.TQ_URL.'images/design/icons/delete.gif" alt="delete question" /></a>';
			$editLink = '<a href="'.$editUrl.'" ><img src="'.TQ_URL.'images/design/icons/edit.gif" alt="edit item" /></a>';
		}else{
			$deleteLink = '';
			$editLink = '';
		}
		if($row_class=='tq_odd'){ $row_class = ''; }else{ $row_class = 'tq_odd'; }
		
		?><tr class="<?php echo $row_class; ?>">			
			<td><?php 
				echo $item->title.'<br/><span class="tq_small">'.substr($item->question_text,0,80);
				if(strlen($item->question_text)>80){ echo ' ...'; }
				echo '</span>'; 
			?></td>		
			<td><?= $item->quiz_post_id; ?></td>
			<td><?= $item->created; ?></td>
			<td><?= $item->updated; ?></td>
			<td><?= $occurrencies_answers[$item->id]; ?></td>
			<td><?= $editLink; ?></td>
			<td><?= $deleteLink; ?></td>
		</tr><?
	}
		
	?> </tbody>
</table>	
		
		
<?
	$pagination->display();
?>