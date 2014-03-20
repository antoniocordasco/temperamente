
jQuery(document).ready(function($){	


	$('.right_answer').click(function(){	
		$('#right_id').val($(this).val());
	});
	$('.picture_selector').change(function(){	
	
		var preview_url = img_preview_urls[$(this).val()];
		$(this).parent().parent().find('img').attr('src',preview_url);
		$(this).parent().find('.hidden_picture_id').val($(this).val());
	});

	
	
	var picture_selectors = $('.hidden_picture_id');
	
	for(var i =0;i<picture_selectors.length;i++)
	{ 
		var curr_sel = picture_selectors[i];
		$(curr_sel).parent().find('.picture_selector').val($(curr_sel).val());
		$(curr_sel).parent().parent().find('img').attr('src',img_preview_urls[$(curr_sel).val()]);
	
	}
	
	
	$('a.warning_delete_all_responses').click(function(){
	
		return confirm("This will delete all the responses to the quiz:\n" + $(this).attr('title') + "\nDo you wish to continue?");
	});
});