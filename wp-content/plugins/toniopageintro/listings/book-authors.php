<?php

	require_once(TQ_PATH.'classes/Pagination.php');


	$tryDelete = false;
	if(isset($_GET['tn_item_id_delete']) && intVal($_GET['tn_item_id_delete'])>0) 
	{ 
		$tryDelete = true;
		$deleted = false;
	}
	
	
	

	
	
	$pageNum = intVal($_GET['pageNum']);
	if($pageNum==0) $pageNum=1;
	
	
	
	$items = $GLOBALS['wpdb']->get_results('SELECT * FROM wp_tpi_book_author ORDER BY last_name ASC LIMIT '. ( ($pageNum-1) * 10) .',10');
	$itemsTotal = $GLOBALS['wpdb']->get_results('SELECT * FROM wp_tpi_book_author ORDER BY last_name ASC');


if($tryDelete){
	if($deleted){
		echo '<p>Item deleted successfully</p>';
	}else{
		echo '<p>An error occurred while deleting the element</p>';
	}

}
?>


<p><a href="<?php echo TPI_AUTHORS_ADMIN_PAGE_URL; ?>&amp;tpi_authors_item_id=0">
<img alt="create new item" src="<?php echo TPI_RELATIVE_URL; ?>images/design/icons/new.gif"/>Create new item
</a></p>

<p>	
</p>	
<? 

	$pagination = new CmsPagination(count($itemsTotal));
	$pagination->display(); 
?>
<table class="widefat">
	<thead>
		<tr><th width="160">Name</th><th width="160">Surname</th><th width="40">Edit</th><th width="40">Delete</th></tr>
	</thead>
	<tbody> <? 
	foreach($items as $item)
	{ 

		
		if (true){
			$deleteLink = '<a href="'.TPI_AUTHORS_ADMIN_PAGE_URL.'" onclick="return confirm(\'Delete this item?\')"><img src="'.TPI_RELATIVE_URL.'images/design/icons/delete.gif" alt="delete" /></a>';
			$editLink = '<a href="'.TPI_AUTHORS_ADMIN_PAGE_URL.'&tpi_authors_item_id='.$item->id.'" ><img src="'.TPI_RELATIVE_URL.'images/design/icons/edit.gif" alt="edit" /></a>';
		}else{
			$deleteLink = '';
			$editLink = '';
		}
		
		?><tr>			
			<td><?= $item->first_name; ?></td>
			<td><?= $item->last_name; ?></td>
	
			<td><?= $editLink; ?></td>
			<td><?= $deleteLink; ?></td>
		</tr><?
	}
		
	?> </tbody>
</table>	
		
		
<?
	$pagination->display();
	
	
	