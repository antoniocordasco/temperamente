var tfb_ajax_base_url = '/?tfb_ajax_request=true';


jQuery(document).ready(function($){
	var rand = Math.floor(Math.random() * 5);
	
	if(rand == 0) { 
		// alert('posts');
		$.ajax({
			url: tfb_ajax_base_url + '&req_type=fetch_latest_posts',
			success: function(data){
			}
		});
	} else if(rand == 1) { 
		// alert(tfb_ajax_base_url + '&req_type=fetch_latest_images_5-buoni-motivi');
		$.ajax({
			url: tfb_ajax_base_url + '&req_type=fetch_latest_images_5-buoni-motivi',
			success: function(data){
			}
		});
	}
	
	 
	
});