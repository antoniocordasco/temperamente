<?php
/* --- to do -- * /
1. add these as the last argument
	show_count=false" 
	scrolling="no" 
	frameborder="0" 
	allowTransparency="true" 
	style="border:none; overflow:hidden; width:300px; height:25px">

2. don't like how I'm handling check boxes

3. add an image to the admin screen

4. need to execute validation on width & css class field

/* -- */ 

	function fblike_set_admin_options() {
		$fbLikeOptions = fblike_get_default_admin_options();
		$fbLikeSave = (isset($_POST['fblike_hidden'])) ? $_POST['fblike_hidden'] : 'N';
		
		if($fbLikeSave && !empty($fbLikeOptions)) {
			foreach ($fbLikeOptions as $key => $option) {
				$fbLikeIsSet = 'false';
				if(isset($_POST[$key])) {
					$fbLikeIsSet = 'true';
					if($_POST[$key]) {
						$fbLikeOptions[$key] = $_POST[$key];

					}
				} 

				/* the bane of check buttons */
				if(	$key == 'fblike_settings_show_before_post' || 
					$key == 'fblike_settings_show_after_post' ||
					$key == 'fblike_settings_show_on_single_page' ||
					$key == 'fblike_settings_show_on_front_page' ||
					$key == 'fblike_settings_show_on_single_post' ||
					$key == 'fblike_param_show_faces' ) {
					$fbLikeOptions[$key] = $fbLikeIsSet;
				}
			}            
			update_option('fbLikeAdminOptions', $fbLikeOptions);
			?>
			<div class="updated"><p><strong><?php _e('FBLike Options Saved.' ); ?></strong></p></div>
			<?php
		}
		return $fbOptions;
	}

        function fblike_get_default_admin_options() {
            $fbLikeAdminOptions = array('fblike_settings_show_before_post' => 'true',
                'fblike_settings_show_after_post' => 'true',
                'fblike_settings_show_on_single_page' => 'true',
                'fblike_settings_show_on_front_page' => 'true',
                'fblike_settings_show_on_single_post' => 'true',
                'fblike_settings_cssclass' => 'fbLikeContainer',
		'fblike_param_layout' => 'standard',
		'fblike_param_width' => '300',
		'fblike_param_height' => '25',
		'fblike_param_action' => 'like',
		'fblike_param_font' => 'arial',
		'fblike_param_colorscheme' => 'light',
                'fblike_param_show_faces' => 'true');
            return $fbLikeAdminOptions;
        }


        function fblike_get_admin_options() {
            $fbLikeAdminOptions = fblike_get_default_admin_options();
            $fbLikeOptions = get_option('fbLikeAdminOptions');
            if (!empty($fbLikeOptions)) {
                foreach ($fbLikeOptions as $key => $option) {
                    $fbLikeAdminOptions[$key] = $option;
		}
            }            
            // update_option('fbLikeAdminOptions', $fbLikeAdminOptions);
            return $fbLikeAdminOptions;
        }

?>
