<?php 

	$only_completed = (isset($_GET['tq_only_completed']) && $_GET['tq_only_completed']=='true');
	$data = tq_get_quiz_responses_list(intval($_GET['tq_show_responses']), $only_completed);
	$responses = $data['responses'];
	
	$right_answers = array();
	foreach($data['right_answers'] as $row)
	{
		$right_answers[$row['answers_set_id']] = $row['num_right'];
	}
	
	if(count($responses)>0)
	{ 
		$table_headings = array();
		foreach($responses[0] as $key => $val){ $table_headings[] = $key; }
		
		
?>
				
		<p>Responses</p>
		<table class="widefat tablesorter" id="sortable_responses">
			<thead><tr><th><?php echo implode('</th><th>',$table_headings); ?></th><th>correct</th></tr></thead>
			<tbody>
<?php
		foreach($responses as $row)
		{ 
			echo '<tr><td>'.implode('</td><td>',$row).'</td><td>';
			if(isset($right_answers[$row['id']])){ echo $right_answers[$row['id']]; }else{ echo '0'; }
			echo '</td></tr>';
		
		}
	
	
?>		
			</tbody>
		</table>
<?php		
	}
	