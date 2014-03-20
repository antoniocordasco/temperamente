<?php
/* this is where we build the iframe based on:
 * http://developers.facebook.com/docs/reference/plugins/like
 * v0.3 - update fblike_get_iframe()
 */

add_filter('the_content', 'fblike_the_content'); 
function fblike_the_content($content) {
	extract(get_option('fbLikeAdminOptions'));
	$fblike_uri = get_permalink(get_the_ID());

	if( 	(is_front_page() && $fblike_settings_show_on_front_page == 'true') ||
		(is_single() && $fblike_settings_show_on_single_post == 'true') ||
		(is_page() && $fblike_settings_show_on_single_page == 'true') ) 
	{
		if($fblike_settings_show_before_post == 'true') {
			$content = fblike_get_iframe($fblike_uri).$content;
		}
		if($fblike_settings_show_after_post == 'true') {
			$content .= fblike_get_iframe($fblike_uri);
		}
	}
	return $content;
}

function fblike_get_iframe($fblike_uri) {
	extract(get_option('fbLikeAdminOptions'));

	$fblike_ret = '<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($fblike_uri);
	$fblike_ret .= '&amp;layout='.$fblike_param_layout;
        $fblike_ret .= '&amp;show_faces='.$fblike_param_show_faces;
        $fblike_ret .= '&amp;width='.$fblike_param_width;
        $fblike_ret .= '&amp;height='.$fblike_param_height;
        $fblike_ret .= '&amp;action='.$fblike_param_action;
        $fblike_ret .= '&amp;font='.$fblike_param_font;
        $fblike_ret .= '&amp;colorscheme='.$fblike_param_colorscheme.'" ';	/* note, this is where the SRC arg ends */
	$fblike_ret .= ' id="fbLikeIframe" name="fbLikeIframe" ';
        $fblike_ret .= ' scrolling="no" frameborder="0" allowTransparency="true" ';
	if(!empty($fblike_settings_cssclass)) {
	        $fblike_ret .= ' class="'.$fblike_settings_cssclass.'" ';
	}
	$fblike_ret .= ' style="border:none; overflow:hidden; width:'.$fblike_param_width.'px; height:'.$fblike_param_height.'px; display:inline;" ';
        $fblike_ret .= ' ></iframe>';
	return $fblike_ret;
}
?>
