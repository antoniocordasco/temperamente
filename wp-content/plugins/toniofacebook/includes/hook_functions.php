<?php





function tfb_connect()
{	
	$redirect_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$split = explode('?',$redirect_url);
	$redirect_url = $split[0];
	
	if(!isset($_GET['code']) && !isset($_SESSION['tfb_access_token'])){
		
		$url = TFB_GRAPH_SECURE_URL . 'oauth/authorize?client_id=' . TFB_APP_ID . '&redirect_uri=' . $redirect_url . '&scope=offline_access,read_stream';
		wp_redirect($url);
		
		
	}elseif(isset($_GET['code']) && $_GET['code']!=''){ 
		$url = TFB_GRAPH_SECURE_URL . 'oauth/access_token?client_id='.TFB_APP_ID.'&redirect_uri=' . $redirect_url . '&client_secret='.TFB_APP_SECRET.'&code='.$_GET['code'];
		
		
		$access_token = tfb_get_url($url);
		$_SESSION['tfb_access_token'] = substr($access_token, strpos($access_token, "=")+1, strlen($access_token));
		tfb_log_authorization($_SERVER['REMOTE_ADDR'],'true');
		// var_dump($_SESSION); var_dump($_GET);		die;
	}	
	tfb_db_maintenance();
}

function tfb_add_js() {	
?>
	<script type="text/javascript">
		var tfb_graph_url = '<?php echo TFB_GRAPH_URL; ?>';
		var tfb_graph_secure_url = '<?php echo TFB_GRAPH_SECURE_URL; ?>';
	</script>
<?php
	if(!isset($_GET['tfb_ajax_request']) AND isset($_SESSION['tfb_access_token']) AND $_SESSION['tfb_access_token']!='') {	
		echo '<script type="text/javascript" src="/wp-content/plugins/toniofacebook/js/functions.js"></script>'."\n";
	}
}



function tfb_check_ajax_request() {	
	if(isset($_GET['tfb_ajax_request'])) {
		include(ABSPATH . '/wp-content/plugins/toniofacebook/ajax/request.php'); 
		die;
	}
}
