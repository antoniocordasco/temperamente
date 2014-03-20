	var hide_submenu=true;
	var original_parent_id=0;
	function show_current_sub_pages()
	{	
		if(hide_submenu)
		{
			jQuery('.sub_navigation_links').hide();
			jQuery('.sub_navigation_links.current').show();	
			jQuery('#navigation_links li').removeClass('current_page_item');
			jQuery('#'+original_parent_id).addClass('current_page_item');
		}
	}

var active_post_sections = Array();
  
jQuery(document).ready(function($){ 
	$.ajax({
		url: root_folder_url + '/ajax/',	
		data: {action : 'update_gr_random_book_details'}		
	});

	var default_search_text = $('#search-box .input-text').val();
	$('#search-box .input-text').val(default_search_text).focus(function(){ $(this).val(''); }).blur(function(){ if($(this).val() == '') $(this).val(default_search_text); });		
	$.ajax({
		url: root_folder_url + '/ajax/',	
		data: {action : 'get_search_autocomplete_words'},
		success: function(data){
			var json_obj = eval(data);
			$('#search-box .input-text').autocomplete({
				source: json_obj
			});
			$('ul.ui-autocomplete li').live("click", function() {
				$('#search-box .input-submit').click();
			});	
		}
	});
	
	$('ul li.catlink a').hover(function() {
		$('ul li.catlink .cat-dropdown').hide().html('');	
	});
	$('ul li.catlink a').hoverIntent(function() {
		var tmp_dropdown = $(this).parent().find('.cat-dropdown');
		var cat_id = $(this).parent().attr('id').replace('catlink', '');	
		$.ajax({
			url: root_folder_url + '/ajax/',	
			data: {action : 'get_category_dropdown', category_id : cat_id},
			success: function(data){		
				$('ul li.catlink .cat-dropdown').hide().html('');		
				$(tmp_dropdown).html(data).show();
			}
		});
	},function() {
		$('ul li.catlink .cat-dropdown').hide().html('');
	});
	
	
	
	

	original_parent_id = $('.current_page_item').attr('id');


	$('#navigation_links a').mouseover(function(){
		
		var split = $(this).parent().attr('id').split('-');
		if(split.length==3)
		{
			var parent_id=split[2];
			var sub_pages = $('#sub_navigation_links'+parent_id);
			if(sub_pages.length==1)
			{
				$('#navigation_links li').removeClass('current_page_item');
				$(this).parent().addClass('current_page_item');
				$('.sub_navigation_links').hide();
				$(sub_pages).show();
			}
		}
	});
	
	$('#navigation_links a').mouseout(function(){
		hide_submenu=true;
		setTimeout('show_current_sub_pages()',800);
	});

	$('.sub_navigation_links a').mouseover(function(){
		hide_submenu=false;
	});
	$('.sub_navigation_links a').mouseout(function(){
		hide_submenu=true;
		setTimeout('show_current_sub_pages()',800);
	});
	
	
	$('#related_contents').show();
	$('#related_contents img').show();
	$('#related_contents_carousel').jcarousel({ easing: 'linear' });
	
	$('.author_article').click(function(){
		$('.author_posts_list').hide();
		$(this).parent().parent().parent().find('.author_posts_list').slideDown();
		return false;
	});
  
  
  
  $('.more_entries').hide();
  $('.show_more_entries').click(function(){
    $('.show_more_entries').show();
    $('.more_entries').hide();
    $(this).parent().find('.more_entries').slideDown(1000);
    $(this).hide();
    return false;
  });
  
  
  
  
  // homepage category slideshows
  
  $('.post_wide').css('height','280px');
  var tmp = $('.post_wide');
  $(tmp[0]).css('height','350px');
  
  $('.post_wide .entry').css('position','absolute');
  
  var category_sections = $('.post_wide');
  var posts_sections;
  
  for(var i=0; i<category_sections.length; i++) { 
	$(category_sections[i]).find('.entry').hide();
	posts_sections = $(category_sections[i]).find('.entry');
	$(posts_sections[0]).show();
	active_post_sections[i] = 0;
	var pagination_string = '';	
	
	if(!$(category_sections[i]).hasClass('news_div')){
		$(category_sections[i]).find('.entry').css('width','900px');
		for(var ii=0; ii<posts_sections.length; ii++)
		{
			if(ii == 0){ var active_class = 'active'; } else { var active_class = ''; }
			pagination_string += '<li><a href="#" class="hs_pag_link ' + active_class + '">' + (ii+1) + '</a></li>';
		}
		$(category_sections[i]).append('<ul class="hs_controls"><li><a href="#" class="hs_prev">&laquo;</a></li> ' + pagination_string + '<li><a href="#" class="hs_next">&raquo;</a></li></ul>');
	
	}else{
		var image_url_tmp = '';
		var title_tmp = '';
		for(var ii=0; ii<posts_sections.length; ii++)
		{
			image_url_tmp = $(posts_sections[ii]).css('background-image').replace('url("','').replace('")','').replace('url(','').replace(')','');
			title_tmp = $(posts_sections[ii]).find('a').html();
			
			if(ii == 0){ var active_class = 'active'; } else { var active_class = ''; }
			pagination_string += '<li class="' + active_class + '"><a href="#" class="hs_pag_link ' + active_class + '"><img id="news_img_button_' + (ii+1) + '" src="' + image_url_tmp + '" />' +
			'' + title_tmp + '' + 
			'</a></li>';
		}
		// $(category_sections[i]).append('<ul class="hs_news_controls"> ' + pagination_string + '</ul>');
	}
	
 }
  
  $('.hs_prev').addClass('previous-off');
  $('.hs_next').click(function(){
	if(!$(this).hasClass('next-off')){ category_slide($(this),'next'); }
	return false;
  });
  $('.hs_prev').click(function(){
	if(!$(this).hasClass('previous-off')){ category_slide($(this),'prev'); }
	return false;
  });
  
  
  $('.hs_pag_link').click(function(){
	if(!$(this).hasClass('active')){ category_slide($(this),null); }
	return false;
  });
  
  

  
  // end homepage category slideshows
  
  
  
  function category_slide(button_element, direction) { 
  
	var current_category_section = category_sections.length - $(button_element).parent().parent().parent().siblings().length -1;	
	posts_sections = $(button_element).parent().parent().parent().find('.entry');
	$(button_element).parent().parent().parent().find('.entry').hide();	
	
	$(button_element).parent().parent().find('.hs_pag_link').removeClass('active');
	$(button_element).parent().parent().find('li').removeClass('active');
	
	if(direction == 'next') {
		active_post_sections[current_category_section]++;
	} else if(direction == 'prev') {
		active_post_sections[current_category_section]--;	
	} else {
		var id_tmp = parseInt($(button_element).html());
		
		active_post_sections[current_category_section] = id_tmp - 1;
		$(button_element).addClass('active');
		$(button_element).parent().addClass('active');
	}
	var page_links = $(button_element).parent().parent().find('.hs_pag_link');
	$(page_links[active_post_sections[current_category_section]]).addClass('active');
	
	$(button_element).parent().parent().find('.hs_prev').removeClass('previous-off');
	$(button_element).parent().parent().find('.hs_next').removeClass('next-off');
	if((active_post_sections[current_category_section]) <= 0)
	{
		$(button_element).parent().parent().find('.hs_prev').addClass('previous-off');
	} else if((active_post_sections[current_category_section]) >= posts_sections.length-1){
		$(button_element).parent().parent().find('.hs_next').addClass('next-off');
	}
	
	$(posts_sections[active_post_sections[current_category_section]]).fadeIn();
  }
  
  function news_slide() { 
  
	posts_sections = $('.post_wide.news_div .entry');
	$(posts_sections[active_post_sections[0]]).find('.bottom_text').slideUp('slow', function(){
		$(this).parent().fadeOut('slow', function(){
			$('.post_wide.news_div .entry').hide();
			$('.post_wide.news_div .entry .bottom_text').hide();
			var next = $(this).next();
			if(next.length == 0 ){
				next = $(this).parent().children('div');
				$(this).find('.bottom_text').hide();
				next = next[0];
			}
			$(next).fadeIn('slow', function(){				
				$(this).find('.bottom_text').slideDown('slow');
			});
		});
	});
	active_post_sections[0]++;
	if(active_post_sections[0] > 4){ active_post_sections[0] = 0; }
  }
  
  /**/
  var buttons_news = $('.post_wide.news_div div');  
  if(buttons_news.length>0)
  {
	  setInterval(function(){	
		news_slide();	
	  }, 8000 );
  }
  /**/
  
  collapse_archivi();
  
  function collapse_archivi() {
	var widget_ul = $('.archivi ul');
	var lis = $(widget_ul).find('li');
	
	var years = Array();
	var lis_grouped = Array();
	
	for(var i=0; i<lis.length; i++) {
		var title = $(lis[i]).find('a').html();
		var year = title.substring(title.length-4, title.length);
		if(years.length == 0 || year != years[years.length-1]) {
			years[years.length] = year;
			$(widget_ul).append('<li id="ok1" ><a class="expand_year" href="#">' + year + '</a><ul></ul></li>');
			tmp = $(widget_ul).find('ul');
		}
		
		$(lis[i]).remove();
		$(tmp[tmp.length-1]).append($(lis[i]));
	}
	$(widget_ul).find('ul').hide();
	$(widget_ul).find('.expand_year').click(function() {
		$(widget_ul).find('ul').hide();
		$(this).parent().find('ul').slideDown();
		return false;
	});
  }
  
  	ifrm = document.createElement("IFRAME");
	ifrm.setAttribute("src", "http://www.facebook.com/plugins/likebox.php?id=104043492963912&width=200&connections=10&stream=false&header=true&height=287");
	ifrm.style.border = "medium none";
	ifrm.style.overflow = "hidden";
	ifrm.style.width = "200px"; 
	ifrm.style.height = "287px";
	ifrm.scrolling = "no";
	ifrm.frameborder = "0";
	ifrm.allowtransparency = "true";
	$('#fb_div_sidebar').append(ifrm);
  
	$('a.new_window').attr('target', '_blank');
	$('.socialmedia-buttons a').attr('target', '_blank');
	
	$('.lightbox a').lightBox();
	
	
	$('#related_contents_carousel a').click(function(){
		_gaq.push(['_trackEvent', 'Related contents clicks', 'click', '']);
	});	
	$('.sidebar-quiz-link').click(function(){
		_gaq.push(['_trackEvent', 'Sidebar clicks', 'Quiz', '']);
	});
	$('.sidebar-link-motivi').click(function(){
		_gaq.push(['_trackEvent', 'Sidebar clicks', '5 motivi per leggere un libro', '']);
	});
	
	// amazon stuff here:
	$('a.amazon-link').attr('target', '_blank');
	$('a.amazon-link').click(function(){	
		_gaq.push(['_trackEvent', 'Amazon link', $('.post h1').html(), '']);
	});
	
	
//	$(window).scroll(function () {	
//		var pos = $('#related_contents_carousel').offset();	
//		if ($(window).scrollTop() >= (parseInt(pos['top']) - 360)) {		
//			$('#share_slider').slideDown(6000);
//		}
//	});

	
	
});


  
  

