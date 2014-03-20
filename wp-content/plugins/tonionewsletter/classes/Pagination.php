<?php


Class Pagination
{		
	var $from;
	var $to;
	var $total;
	var $entries_per_page = 10;
	
	function   __construct(){ }
		
	function getFrom()
	{
		return $this->from;
	}
	
	function getTo()
	{
		return $this->to;
	}
	
	function getPageNum()
	{
		if(intVal($_GET['pageNum'])>0)
		{
			return intVal($_GET['pageNum']);
		}
		else 
		{ 
			return 1; 
		}
	}		
		
	function getQueryString()
	{
		$queryString = '';
		foreach($_GET as $getKey => $getVal)
		{
		
			if(($getKey!='pageNum')&&($getKey!='consultantId')&&  ($getKey!='folderSeminar')&& ($getKey!='folder1')&& ($getKey!='folder2')&& ($getKey!='folder3')&& ($getKey!='folder4')){  $queryString .= '&'.$getKey.'='.$getVal; }
		}
		
		return $queryString;
	}
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
Class CmsPagination extends Pagination
{
		
	function   __construct($total)
	{
		$this->total = $total;
		$pageNum = $this->getPageNum();
		$this->from = ($pageNum-1) * $this->entries_per_page;
		$to = ($pageNum*$this->entries_per_page) -1;
		if($to >= $total) $this->to = $total-1;
		else $this->to = $to;
		//echo $this->from.' '.$this->to ; exit();
	}
		
		
		
	

	function display()
	{	
		if($this->total>0)
		{
			$pageNum = $this->getPageNum();		
			
			echo "<span >";
			echo '<strong>Currently listing records '.($this->from+1).' to '.($this->to+1).' of '.$this->total.'</strong>';
			if($this->total>$this->entries_per_page)
			{
				$qs = $this->getQueryString();
				echo "<span class=\"spacer\">&nbsp;</span>";
				if($pageNum<=1) { echo '&lt;&lt;previous&nbsp;&nbsp;'; }
				else { echo "<a href=\"".$_SERVER['SCRIPT_NAME']."?pageNum=".($pageNum-1).$qs."\">&lt;&lt;previous&nbsp;&nbsp;</a>"; }
					
				//echo $to.$total;
				if($this->to>=($this->total-1)) { echo "next &gt;&gt;"; }
				else { echo "<a href=\"".$_SERVER['SCRIPT_NAME']."?pageNum=".($pageNum+1).$qs."\">next &gt;&gt;</a>	"; }	
			}
			echo "</span>";	
			
			
		}		
		
	}
	
		

}




























Class FrontendPagination extends Pagination
{
		
	function   __construct($total)
	{
		$this->total = $total;
		$pageNum = $this->getPageNum();
		$this->from = ($pageNum-1) * ENTRIES_PER_PAGE_WWW;
		$to = ($pageNum*ENTRIES_PER_PAGE_WWW) -1;
		if($to >= $total) $this->to = $total-1;
		else $this->to = $to;
		//echo $this->from.' '.$this->to ; exit();
	}
		
		
		
	

	function display()
	{	
		if($this->total>0)
		{
			$pageNum = $this->getPageNum();
			
			if($this->total>ENTRIES_PER_PAGE_WWW)
			{
				echo '<div class="pagination">';
				echo '<p><strong>Go to page </strong></p>';
				$numOfPages = ceil($this->total/ENTRIES_PER_PAGE_WWW); 
				$qs = $this->getQueryString();
				echo "<ul>";
				if($pageNum<=1) { echo '<li class="prev">previous</li>'; }
				else { echo "<li class=\"prev\"><a href=\"?pageNum=".($pageNum-1).$qs."\">previous</a></li>"; }
				for($ii=1;$ii<=$numOfPages;$ii++){
				 echo "<li ><a href=\"?pageNum=$ii".$qs."\">$ii</a></li>";
				}
				
				
				if($this->to>=($this->total-1)) { echo '<li class="next">next</li> '; }
				else { echo "<li class=\"next\"><a href=\"?pageNum=".($pageNum+1).$qs."\">next </a></li>	"; }	
				echo "</ul>\n";
				echo '</div>';	
			}
		}		
		
	}
	
		

}















