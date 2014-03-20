
jQuery(document).ready(function($){
	
	$('.choice label span').show();
	
	var response = false;
	var checked_radio = $('.tq_wrapper div input[checked]');	
	if(checked_radio.length==1){ response = true; }
	
	checked_radio = $('.tq_wrapper div input[checked]:not(.correct)');	
	if(checked_radio.length==1)
	{ 
		if(!$(checked_radio[0]).parent().hasClass('correct')){ $(checked_radio[0]).parent().css('background-color','#aaf'); }
	}
	
	$('.tq_wrapper div input[type=radio]').hide();
	
	
	
	$('.tq_wrapper div.choice').click(function(){
		var radio = $(this).find('input[type=radio]');
		if(!response && radio.length==1)
		{
			$(this).find('input[type=radio]').removeAttr('checked');
			$('.tq_wrapper div.choice').css('background-color','#F4F4F4');
			
			$(radio[0]).attr('checked','checked');
			$(this).css('background-color','#aaf');
		}
	
	});
	
	$("div.question img.magnify").imageMagnify({ magnifyby:3 });
	
});