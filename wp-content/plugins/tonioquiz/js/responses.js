
jQuery(document).ready(function($){	


	$('a.tq_responses_confirm').click(function(){	
		return confirm("This will open a very big page that might slow down the site. Continue?");	
	});



    $("#sortable_responses").tablesorter(); 
   

});