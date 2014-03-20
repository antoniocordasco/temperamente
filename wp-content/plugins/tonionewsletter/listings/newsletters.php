<?php
	require_once(TONIONEWSLETTER_PATH.'classes/Pagination.php');

	$tryDelete = false;
	if(isset($_GET['tn_item_id_delete']) && intVal($_GET['tn_item_id_delete'])>0) 
	{ 
		$tryDelete = true;
		$deleted = tn_delete_newsletter($_GET['tn_item_id_delete']);
	}
	if(isset($_GET['tn_preview_id']) && intVal($_GET['tn_preview_id'])>0) 
	{
		$previewed = tn_newsletter_preview($_GET['tn_preview_id']);
	}
	if(isset($_GET['tn_send_id']) && intVal($_GET['tn_send_id'])>0) 
	{
		$send = tn_newsletter_send($_GET['tn_send_id']);
	}
	
	
	
	if(isset($_GET['tn_item_id_publish']) && intVal($_GET['tn_item_id_publish'])>0) 
	{		
		tn_publish_newsletter($_GET['tn_item_id_publish']);
	}
	
	if(isset($_GET['tn_item_id_unpublish']) && intVal($_GET['tn_item_id_unpublish'])>0) 
	{		
		tn_unpublish_newsletter($_GET['tn_item_id_unpublish']);
	}

	
	
	$pageNum = intVal($_GET['pageNum']);
	if($pageNum==0) $pageNum=1;
	
	$search = array('tpoll_search'=>'');
	
	
	$items = tn_get_newsletters($search,false,$pageNum,10);
	$itemsTotal = tn_get_newsletters($search,false);


if($tryDelete){
	if($deleted){
		echo '<p>Item deleted successfully</p>';
	}else{
		echo '<p>An error occurred while deleting the element</p>';
	}

}
?>


<p><a href="<?php echo TONIONEWSLETTER_ADMIN_PAGE_URL; ?>&amp;tn_item_id=0">
<img alt="create new item" src="<?=TONIONEWSLETTER_URL; ?>images/design/icons/new.gif"/>Create new item
</a></p>

	
<? 

	$pagination = new CmsPagination(count($itemsTotal));
	$pagination->display(); 
?>
<table class="widefat">
	<thead>
		<tr><th width="40">Status</th><th width="160">Title</th><th width="100">Created</th><th width="100">Updated</th><th width="100">Sent</th>
		<th width="40">Edit</th><th width="40">Preview</th><th width="40">Send</th><th width="40">Delete</th>		</tr>
	</thead>
	<tbody> <? 
	foreach($items as $item)
	{ 
		if($item->sent==null || $item->sent=='0000-00-00 00:00:00'){
			if($item->status!='active'){
				
				$statusLink = '<a href="'.TONIONEWSLETTER_ADMIN_PAGE_URL		
				.'&amp;tn_item_id_publish='.$item->id.'"><img src="'.TONIONEWSLETTER_URL.'images/design/icons/draft.gif" alt="publish item" /></a>';
			}else{	
				$statusLink = '<a href="'.TONIONEWSLETTER_ADMIN_PAGE_URL		
				.'&amp;tn_item_id_unpublish='.$item->id.'"><img src="'.TONIONEWSLETTER_URL.'images/design/icons/published.gif" alt="unpublish item" /></a>';
			}
		}else{
			$statusLink = 'sent';
		}
		$deleteUrl = TONIONEWSLETTER_ADMIN_PAGE_URL.'&tn_item_id_delete='.$item->id;
		$editUrl = TONIONEWSLETTER_ADMIN_PAGE_URL.'&tn_item_id='.$item->id;
		$previewUrl = TONIONEWSLETTER_ADMIN_PAGE_URL.'&tn_preview_id='.$item->id;
		$sendUrl = TONIONEWSLETTER_ADMIN_PAGE_URL.'&tn_send_id='.$item->id;$previewLink = '<a href="'.$previewUrl.'" ><img src="'.TONIONEWSLETTER_URL.'images/design/icons/email.gif" alt="preview" /></a>';
		if($item->status=='active' AND ($item->sent==null || $item->sent=='0000-00-00 00:00:00' ) ){
			$sendLink = '<a href="'.$sendUrl.'" onclick="return confirm(\'Send this item?\')"><img src="'.TONIONEWSLETTER_URL.'images/design/icons/email.gif" alt="send" /></a>';
		}else{
			$sendLink = '';
		}
		if ($item->sent==null || $item->sent=='0000-00-00 00:00:00' ){
			$deleteLink = '<a href="'.$deleteUrl.'" onclick="return confirm(\'Delete this item?\')"><img src="'.TONIONEWSLETTER_URL.'images/design/icons/delete.gif" alt="delete quiz" /></a>';
			$editLink = '<a href="'.$editUrl.'" ><img src="'.TONIONEWSLETTER_URL.'images/design/icons/edit.gif" alt="edit item" /></a>';
		}else{
			$deleteLink = '';
			$editLink = '';
		}
		
		?><tr>			
			<td><?= $statusLink; ?></td>
			<td><?= $item->title; ?></td>
			<td><?= $item->created; ?></td>
			<td><?= $item->updated; ?></td>
			<td><?= $item->sent; ?></td>
			<td><?= $editLink; ?></td>
			<td><?= $previewLink; ?></td>
			<td><?= $sendLink; ?></td>
			<td><?= $deleteLink; ?></td>
		</tr><?
	}
		
	?> </tbody>
</table>	
		
		
<?
	$pagination->display();
?>