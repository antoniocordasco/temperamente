<?php

global $is_wphc_admin;
$is_wphc_admin = 1;
$wphc_options = get_option('wphc_options');
if ( isset($_POST['submit']) ) {
	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
		die(__('Cheatin&#8217; uh?'));
	$wphc_options['excluded'] = addslashes($_POST['excluded']);

	update_option('wphc_options', $wphc_options);
}
?>
<?php if ( !empty($_POST ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php _e('Hide Categories Options', 'wphc'); ?></h2>

<div class="metabox-holder" id="poststuff">
<div class="meta-box-sortables">
<script>
jQuery(document).ready(function($) { $('.postbox').children('h3, .handlediv').click(function(){ $(this).siblings('.inside').toggle();});});
</script>
<div class="postbox">
    <div title="<?php _e("Click to open/close", "wphc"); ?>" class="handlediv">
      <br>
    </div>
    <h3 class="hndle"><span><?php _e("Do you use it ?", "wphc"); ?></span></h3>
    <div class="inside" style="display: block;">
        <img src="../wp-content/plugins/wp-hide-posts/img/icon_coffee.png" alt="buy me a coffee" style=" margin: 5px; float:left;" />
        <p>Hi! I'm <a href="http://nxsn.com?f=wpfp" target="_blank" title="Huseyin Berberoglu">Huseyin Berberoglu</a>, developer of this plugin.</p>
        <p>I've been spending many hours to develop this plugin. <br />If you like and use this plugin, you can <strong>buy me a cup of coffee</strong>.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHRwYJKoZIhvcNAQcEoIIHODCCBzQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBSHdcQViaHAHOiGx4KaECVC2hhPshwur7gVh4TrpTo69W9YlVKiRaLOqhvTBQoU7Hulrkj5BYPcjfMfUkf6SVZQJUQg3WudCxscMmD1Yu0Kf2wvnS7zfICmFgBNuJDvJnyZr3RUeIuxyOdELlljaSNxZh+BXkW3WhOlz6xdwMfSTELMAkGBSsOAwIaBQAwgcQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQI9MyqRaXCZk+AgaDYnP1ixyLNgN9gkp//StP670kML2c3iYKWxi5NtUJwjCVbRM/+xjHB0oEcJn0muKxdKyAodaSJCBmCMGrYvdLB2mycp4997/dCixkDxYujKNdeYDijAD4v2gqp0gOGk/AbTcKbUhieAKijSYxlVBKvQkcDBZ9t3sO912zo74wI8SqTh7TGBtmIBDoVPr54eQbS/UBJElBrdO+YIRyWKkueoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMDkwMjIzMTQwOTU0WjAjBgkqhkiG9w0BCQQxFgQUq9PPaw3TVyLjcfei097XMhV6qWcwDQYJKoZIhvcNAQEBBQAEgYAvssotUVP3jyMFgYt1zF4muThMzlLAMFSZCTjeLpqLRWL/eFaSMEd0NYa5maKfqu5M79gucNS9o0/eBgXuCXSgI2wwIakaym6A31YqeuaRBq0Z4n9tPInj8O8vSknNskFbDrgsbgWr864Gp/jlXDwSc80siR2uV2GVuJpAH732PA==-----END PKCS7-----
            ">
            <input type="image" src="../wp-content/plugins/wp-hide-posts/img/donate.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
        </form>
        <div style="clear:both;"></div>
    </div>
</div>
<script type="text/javascript">
function removecategory(id) {
    jQuery('#excluded').val(jQuery('#excluded').val().replace(id+",",""));
    jQuery('#wphc-notselected').append('<a style="margin-right:5px;" href="javascript:addcategory('+id+')" title="click to hide" id="wphc_add_category_'+id+'">'+ jQuery('#wphc_rem_category_'+ id).text() + '</a> ' );
    jQuery('#wphc_rem_category_'+ id).remove();
}
function addcategory(id) {
    jQuery('#excluded').val(jQuery('#excluded').val()+id+',');
    jQuery('#wphc-selected').append('<a style="margin-right:5px;" href="javascript:removecategory('+id+')" title="click to make visible" id="wphc_rem_category_'+id+'">'+ jQuery('#wphc_add_category_'+ id).text() + '</a> ' );
    jQuery('#wphc_add_category_'+ id).remove();
}
</script>

<form action="" method="post">

<div class="postbox">
    <div title="<?php _e('Click to open/close', 'wphc'); ?>" class="handlediv">
      <br>
    </div>
    <h3 class="hndle"><span><?php _e("Options", "wphc"); ?></span></h3>
    <div class="inside" style="display: block;">
        <p>Click the categories to hide/make visible. Don't forget to click 'Update Options' button.</p>
<table class="form-table">
    <tr>
	    <th><?php _e("Visible categories:", "wphc"); ?></th>
	    <td><div id='wphc-notselected'>
        <?php
            $categories = get_categories('hierarchical=0&hide_empty=0&exclude='.$wphc_options['excluded']);
            foreach ($categories as $category) { ?>
	        <a style='margin-right:5px;' href='javascript:addcategory(<?php echo $category->term_id; ?>)' title='click to hide' id='wphc_add_category_<?php echo $category->term_id ?>'><?php echo $category->name; ?></a>
        <?php } ?>
	    </div></td>
    </tr>

    <tr>
	<th><?php _e("Hidden categories:", "wphc"); ?></th>
	    <td><input type="hidden" name="excluded" id='excluded' style="width:400px" value="<?php echo stripslashes($wphc_options['excluded']); ?>" />
        <?php
        echo '<div id="wphc-selected">';
        if ($wphc_options['excluded']) {
            $categories = get_categories('hierarchical=0&hide_empty=0&include='.$wphc_options['excluded']);
            foreach ($categories as $category) { ?>
            <a style='margin-right:5px;' href='javascript:removecategory(<?php echo $category->term_id; ?>)' title="click to make visible" id='wphc_rem_category_<?php echo $category->term_id ?>'><?php echo $category->name; ?></a>
            <?php } 
        } 
        echo '</div>';
        ?>
	    </td>
    </tr>
    
    <tr>
        <th></th>
        <td>
            <input type="submit" name="submit" class="button" value="<?php _e('Update options &raquo;'); ?>" />
        </td>
    </tr>
</table>
</div>
</div>
</form>
</div>


<div class="postbox">
    <div title="<?php _e('Click to open/close', 'wphc'); ?>" class="handlediv">
      <br>
    </div>
    <h3 class="hndle"><span><?php _e("There is more...", "wphc"); ?></span></h3>
    <div class="inside" style="display: block;">
        <p>Check out my other Wordpress Plugins like 
        <a href="http://nxsn.com/projects/wp-favorite-posts-plugin/?ref=<?php echo basename(__FILE__, '.php'); ?>" title="WP Favorite Posts">WP Favorite Posts</a>, 
        <a href="http://nxsn.com/projects/mediarss-with-post-thumbnail-plugin-for-wordpress/?ref=<?php echo basename(__FILE__, '.php'); ?>" title="MediaRSS with Post Thumbnail">MediaRSS with Post Thumbnails</a>, 
        <a href="http://nxsn.com/projects/bp-posts-on-profile/?ref=<?php echo basename(__FILE__, '.php'); ?>" title="BP Posts on Profile">BP Posts on Profile</a>...
    </div>
</div>

</div>
</div>
</div>
