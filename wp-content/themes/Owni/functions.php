<?php
/**
 * Coded by misbah (ini_misbah@yahoo.com)
 */

 
 
include( ABSPATH . 'wp-content/themes/Owni/includes/amazon_functions.php' ); 
include( ABSPATH . 'wp-content/themes/Owni/includes/goodread_functions.php' ); 
 
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Owni widget',
		'before_widget' => '<li id="%1$s" class="widget">',
		'after_widget' => '</li>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>', // 'after_title' => '<span class="toggle"></span></h6>',
	));
}

//Check for widgets in widget-ready areas http://wordpress.org/support/topic/190184?replies=7#post-808787
//Thanks to Chaos Kaizer http://blog.kaizeku.com/
function is_sidebar_active( $index = 1){
	$sidebars	= wp_get_sidebars_widgets();
	$key		= (string) 'sidebar-'.$index;
 
	return (isset($sidebars[$key]));
}

function another_entry_post($number) {
	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	$content = preg_replace('@<script[^>]*?>.*?</script>@si', '', $content);
	$content = preg_replace('@<style[^>]*?>.*?</style>@si', '', $content);
	$content = strip_tags($content);
	$content = substr($content, 0, strrpos(substr($content, 0, $number), ' '));
	echo $content;
	echo "...";
}



function get_authors_posts($folder_name)
{
	$sql = "SELECT   wp_tpi_book_author.id AS author_id, wp_posts.*
			FROM wp_posts JOIN wp_postmeta ON wp_posts.ID=wp_postmeta.post_id JOIN wp_tpi_book_author ON wp_postmeta.meta_value=wp_tpi_book_author.id
			WHERE folder_name='".mysql_real_escape_string($folder_name)."' AND wp_postmeta.meta_key='tpi_book_author' ORDER BY ID DESC; ";
	return $GLOBALS['wpdb']->get_results($sql);
}

function get_authors_list()
{	
	$sql1 = "SELECT COUNT(wp_posts.ID) AS auth_occurrencies,  wp_tpi_book_author.*
			FROM wp_posts JOIN wp_postmeta ON wp_posts.ID=wp_postmeta.post_id JOIN wp_tpi_book_author ON wp_postmeta.meta_value=wp_tpi_book_author.id
			WHERE  wp_postmeta.meta_key='tpi_book_author' GROUP BY wp_tpi_book_author.id ORDER BY last_name ASC; ";
	$return = array('authors'=>array(), 'posts'=>array());
	
	$return['authors'] = $GLOBALS['wpdb']->get_results($sql1);	
	
	$sql2 = "SELECT   wp_tpi_book_author.id AS author_id, wp_posts.*
			FROM wp_posts JOIN wp_postmeta ON wp_posts.ID=wp_postmeta.post_id JOIN wp_tpi_book_author ON wp_postmeta.meta_value=wp_tpi_book_author.id
			WHERE  wp_postmeta.meta_key='tpi_book_author' ORDER BY ID DESC; ";
		
	$tmp = $GLOBALS['wpdb']->get_results($sql2);	
	foreach($tmp as $row)
	{
		if(!isset($return['posts'][$row->author_id]))
		{
			$return['posts'][$row->author_id] = array();
		}		
		$return['posts'][$row->author_id][] = $row;		
	}
	return $return;
}



function owni_get_recent_categories() {

	$sql = "SELECT t.term_id,t.name,t.slug, MAX(p.post_date) AS max_date, COUNT(p.ID) AS category_count
			FROM wp_posts AS p JOIN wp_term_relationships AS tr ON p.ID = tr.object_id 
			JOIN wp_term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
			JOIN wp_terms AS t ON tt.term_id = t.term_id 
			WHERE tt.taxonomy='category' 
			AND p.post_status='publish' 
			AND p.post_type='post' 
			AND t.slug <> ' uncategorized' 
			AND t.term_id <> ".$GLOBALS['temperamente']['category_ids']['news']." 
			AND t.term_id NOT IN (".implode(',', $GLOBALS['temperamente']['hidden_category_ids']).")
			GROUP BY t.term_id
			ORDER BY max_date DESC;";	
	$rows = $GLOBALS['wpdb']->get_results($sql);
	return $rows;
}

function owni_is_review($post_id) {
	$cats = wp_get_post_categories($post_id);
	foreach($cats as $cat) {
		if(in_array($cat, $GLOBALS['temperamente']['nonreview_categories_ids'])) {
			return false;
		}		
	}
	return true;
}

function owni_get_attachment_post_from_guid($guid) { 
	$sql = "SELECT * FROM wp_posts WHERE post_type = 'attachment' AND guid = '".mysql_real_escape_string($guid)."'; ";
	$res = $GLOBALS['wpdb']->get_results($sql);
	if($res) {
		return $res[0];
	} else {
		return false;
	}
}



// given a guid of an attachment post, returns the selected thumbnail url if there is one, 
// otherwise it returns the guid in input
function owni_get_thumbnail_url_from_guid($guid, $thumbnail_size = 'medium') { 	
	if($thumbnail_post = owni_get_attachment_post_from_guid($guid)) {
		if($thumbnail_image = wp_get_attachment_image_src($thumbnail_post->ID, $thumbnail_size)) { 
			return $thumbnail_image[0];
		}
	}
	return $guid;
}

function owni_is_mobile() {

	if(isset($_GET['owni_is_mobile'])) {
		$_SESSION['owni_is_mobile'] = $_GET['owni_is_mobile'];	
	}
	if(isset($_SESSION['owni_is_mobile'])) {
		return	($_SESSION['owni_is_mobile'] == 'true');
	}
	
	$mobile_browser = 0;
	if (preg_match('/(ipad|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
	}
	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');
	 
	if (in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}
	if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
		$mobile_browser++;
	}
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
		$mobile_browser = 0;
	}	 
	return ($mobile_browser > 0);
}

function owni_load_mobile_template($template_filename) {
	include(ABSPATH . 'wp-content/themes/Owni/mobile/' . $template_filename);
	die;
}

function owni_tn2_subscribe($email) {

	$time = date('Y-m-d H:i:s');		
	if( $GLOBALS['wpdb']->query("INSERT INTO wp_tn2_subscriber (email, created, updated) VALUES ('" . mysql_real_escape_string(strip_tags($email)) . "', '$time', '$time');")) { 
		$api = new MCAPI($GLOBALS['temperamente']['mailchimp']['api_key']);	
		$res = $api->listSubscribe($GLOBALS['temperamente']['mailchimp']['list_id'], $email, array(), 'html', false);
	//	var_dump($res);
	}	
}
function owni_tn2_unsubscribe($email) { 
	$time = date('Y-m-d H:i:s');
	$email = mysql_real_escape_string($email);
	if( $GLOBALS['wpdb']->query("UPDATE wp_tn2_subscriber SET deleted='true', updated='$time' WHERE email='$email';") ) { 
		$api = new MCAPI($GLOBALS['temperamente']['mailchimp']['api_key']);	
		return $api->listUnsubscribe($GLOBALS['temperamente']['mailchimp']['list_id'], $email);
	}	
}
function owni_tn2_get_subscriber_data($email) {	
	$email = mysql_real_escape_string($email);	
	return $GLOBALS['wpdb']->get_results("SELECT * FROM wp_tn2_subscriber WHERE email='$email';");
}











include(TEMPLATEPATH.'/includes/template-options.php');
include(TEMPLATEPATH.'/includes/MCAPI.class.php');
include(TEMPLATEPATH.'/includes/plugins.php');











