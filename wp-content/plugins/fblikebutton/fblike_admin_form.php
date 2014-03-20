<?php
/* --- to do -- * /
add these as the last argument
	show_count=false" 
	scrolling="no" 
	frameborder="0" 
	allowTransparency="true" 
	style="border:none; overflow:hidden; width:300px; height:25px">
/* -- */ 

/*
include_once dirname(__FILE__).'/fblike_admin_options.php';
*/

/* basically make all keys variable names */
$fbOptions = fblike_get_default_admin_options();
if(isset($_POST['fblike_hidden'])) {
	if($_POST['fblike_hidden'] == 'Y') {
		$fbOptions = fblike_set_admin_options();
	}
}
extract(get_option('fbLikeAdminOptions'));



?>
<div class="fblikeAdminFormContainer">  
<h2><?php echo __( 'FBLike Plugin Options Setup', 'fblike_trdom' ); ?></h2>
<div class="description">
   <fieldset class="fblCellDescription">
	<legend><?php _e( 'FBLike Description'); ?> </legend>
	<p><?php _e("The form below provides options on how you would like the FaceBook Like button to appear on your website."); ?></p>
	<p><?php _e("The Like button enables users to make connections to your pages and share content back to their friends on Facebook with one click."); ?></p>
	<p><?php _e("For FBLikeButton Plugin updates, comments, questions, and/or feedback, go ahead and visit "); ?> 
		<a href='http://wordpress.org/extend/plugins/fblikebutton/' target='_blank'  
		title='<?php _e("FaceBook Like Button Wordpress Plugin Page"); ?>'><?php _e("the FaceBook Like Button Wordpress Plugin Page."); ?></a></p>
	<p><?php _e("For more information about the FaceBook Like Button and how it works, please visit "); ?> 
		<a href='http://developers.facebook.com/docs/reference/plugins/like' target='_blank'  
		title='<?php _e("FaceBook Like Button Page"); ?>'><?php _e("the FaceBook Like Button Reference Page."); ?></a></p>

   </fieldset><!- /.fblCellDescription -->
</div><!-- /.description -->
<form name="fblike_form" id="fblike_form"  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	<input type="hidden" class="hidden" name="fblike_hidden" id="fblike_hidden" value="Y">  

<div class="fblCellWrapper">
   <fieldset class="fblCellSettings">
	<?php    echo "<legend>" . __( 'FBLikeButton Settings', 'fblike_trdom' ) . "</legend>"; ?>  
	<ul id="fblikeSettings" name="fblikeSettings" class="fblikeFormFields">
	<li class="first settings_show_before_post">
		<label class="description" for="fblike_settings_show_before_post">
			<?php _e("display Above the content?"); ?></label>
		<input id="fblike_settings_show_before_post" name="fblike_settings_show_before_post" class="checkbox" type="checkbox" 
			value="<?php echo $fblike_settings_show_before_post; ?>" 
			<?php echo ($fblike_settings_show_before_post == 'true') ? 'checked="checked"' : '';  ?> />
		<span class="helpsetting" id="fblike_settings_show_before_post_help">
			<?php _e("Show like button above the page/post."); ?></span>
	</li>

	<li class="setting_show_after_post">
		<label class="description" for="fblike_settings_show_after_post">
			<?php _e("display Below the content?"); ?></label>
		<input id="fblike_settings_show_after_post" name="fblike_settings_show_after_post" class="checkbox" type="checkbox" 
			value="<?php echo $fblike_settings_show_after_post; ?>" 
			<?php echo ($fblike_settings_show_after_post == 'true') ? 'checked="checked"' : '';  ?> />
		<span class="helpsetting" id="fblike_settings_show_after_post_help">
			<?php _e("Show like button at the end of the page/post."); ?></span>
	</li>


	<li class="settings_show_on_single_post">
		<label class="description" for="fblike_settings_show_on_single_post">
			<?php _e("display on individual Posts?"); ?></label>
		<input id="fblike_settings_show_on_single_post" name="fblike_settings_show_on_single_post" 
			class="checkbox" type="checkbox" 
			value="<?php echo $fblike_settings_show_on_single_post; ?>" 
			<?php echo ($fblike_settings_show_on_single_post == 'true') ? 'checked="checked"' : '';  ?> />
		<span class="helpsetting" id="fblike_settings_show_on_single_post_help">
			<?php _e("Show like button on individual posts."); ?></span>
	</li>


	<li class="settings_show_on_single_page">
		<label class="description" for="fblike_settings_show_on_single_page">
			<?php _e("display on individual pAges?"); ?></label>
		<input id="fblike_settings_show_on_single_page" name="fblike_settings_show_on_single_page" 
			class="checkbox" type="checkbox" 
			value="<?php echo $fblike_settings_show_on_single_page; ?>" 
			<?php echo ($fblike_settings_show_on_single_page == 'true') ? 'checked="checked"' : '';  ?> />
		<span class="helpsetting" id="fblike_settings_show_on_single_page_help">
			<?php _e("Show like button on individual pages."); ?></span>
	</li>

	<li class="settings_show_on_front_page">
		<label class="description" for="fblike_settings_show_on_front_page">
			<?php _e("display on the Home page?"); ?></label>
		<input id="fblike_settings_show_on_front_page" name="fblike_settings_show_on_front_page" 
			class="checkbox" type="checkbox" 
			value="<?php echo $fblike_settings_show_on_front_page; ?>" 
			<?php echo ($fblike_settings_show_on_front_page == 'true') ? 'checked="checked"' : '';  ?> />
		<span class="helpsetting" id="fblike_settings_show_on_front_page_help">
			<?php _e("Show like button posts listed on the front/index page."); ?></span>
	</li>
	</ul>

   </fieldset><!-- /.fblCellSettings -->
   <fieldset class="fblCellPreview">
	<?php    echo "<legend>" . __( 'FBLikeButton Preview', 'fblike_trdom' ) . "</legend>"; ?>  
	<div id="fblike_preview" class="fblInnerHtml">
			
	</div>

   </fieldset><!-- /.fblCellPreview -->
   </div><!-- /.fblCellWrapper -->

   <fieldset class="fblCellParameters">
	<?php    echo "<legend>" . __( 'Facebook Like Button Display Parameters', 'fblike_trdom' ) . "</legend>"; ?>  
	<ul id="fbLikeParameters" name="fbLikeParameters" class="fblikeFormFields">
	<li class="param_layout">
	 	<label class="description" for="fblike_param_layout"><?php _e("Layout" ); ?></label>
		<select id="fblike_param_layout" name="fblike_param_layout" >
			<option value="standard" <?php echo ($fblike_param_layout == 'standard') ? "selected='selected'" : ""; ?> ><?php _e("Standard" ); ?></option>
			<option value="button_count" <?php echo ($fblike_param_layout == 'button_count') ? "selected='selected'" : ""; ?> ><?php _e("Button Count" ); ?></option>
		</select>
		<span class="help" id="fblike_param_layout_help"><?php _e("Determines the size and amount of social context next to the button." ); ?></span> 
	</li>

	<li class="param_width">
			<label class="description" for="fblike_param_width"><?php _e("Width"); ?></label>
			<input id="fblike_param_width" name="fblike_param_width" class="number" type="text" maxlength="7" value="<?php echo $fblike_param_width; ?>"/> 
			<span class="help" id="fblike_param_width_help"><?php _e("The width of the plugin, in pixels. The default is 300."); ?></span>		
	</li>

	<li class="param_height">
			<label class="description" for="fblike_param_height"><?php _e("Height"); ?></label>
			<input id="fblike_param_height" name="fblike_param_height" class="number" type="text" maxlength="4" value="<?php echo $fblike_param_height; ?>"/> 
			<span class="help" id="fblike_param_height_help"><?php _e("The height of the plugin, in pixels. The default is 25."); ?></span>		
	</li>

	<li class="param_action">
		<label class="description" for="fblike_param_action"><?php _e("Verb to Display"); ?></label>
		<select  id="fblike_param_action" name="fblike_param_action"> 
			<option value="like" <?php echo ($fblike_param_action == 'like') ? "selected='selected'" : ""; ?>><?php _e("Like"); ?></option>
			<option value="recommend" <?php echo ($fblike_param_action == 'recommend') ? "selected='selected'" : ""; ?>><?php _e("Recommend"); ?></option>
		</select>
		<span class="help" id="fblike_param_action_help">
			<?php _e("The verb to display in the button. Currently only 'Like' and 'Recommend' are supported."); ?>
		</span>
	</li>
	<li class="param_font">
		<label class="description" for="fblike_param_font"><?php _e("Font"); ?></label>
		<select id="fblike_param_font" name="fblike_param_font"> 
			<option value="arial" <?php echo ($fblike_param_font == 'arial') ? "selected='selected'" : ""; ?>	>Arial</option>
			<option value="lucida grande" <?php echo ($fblike_param_font == 'lucida grande') ? "selected='selected'" : ""; ?>	>Lucida Grande</option>
			<option value="segoe ui" <?php echo ($fblike_param_font == 'segoe ui') ? "selected='selected'" : ""; ?>>Segoe UI</option>
			<option value="tahoma" <?php echo ($fblike_param_font == 'tahoma') ? "selected='selected'" : ""; ?>>Tahoma</option>
			<option value="trebuchet ms" <?php echo ($fblike_param_font == 'trebuchet ms') ? "selected='selected'" : ""; ?>>Trebuchet MS</option>
			<option value="verdana" <?php echo ($fblike_param_font == 'verdana') ? "selected='selected'" : ""; ?>	>Verdana</option>
		</select>
		<span class="help" id="fblike_param_font_help"><?php _e("The font of the plugin."); ?></span>
	</li>
	
	<li class="param_colorscheme">
		<label class="description" for="fblike_param_colorscheme"><?php _e("Color Scheme"); ?></label>
		<select id="fblike_param_colorscheme" name="fblike_param_colorscheme"> 
			<option value="light" <?php echo ($fblike_param_colorscheme == 'light') ? "selected='selected'" : ""; ?>><?php _e("Light"); ?></option>
			<option value="dark" <?php echo ($fblike_param_colorscheme == 'dark') ? "selected='selected'" : ""; ?>	><?php _e("Dark"); ?></option>
		</select>
		<span class="help" id="fblike_param_colorscheme_help"><?php _e("The color scheme of the plugin."); ?></span>
	</li>
	<li class="param_show_faces">
		<label class="description" for="fblike_param_show_faces"><?php _e("Show Faces?"); ?></label>
		<input id="fblike_param_show_faces" name="fblike_param_show_faces" class="checkbox" type="checkbox" 
			value="<?php echo $fblike_param_show_faces; ?>" 
			<?php echo ($fblike_param_show_faces == 'true') ? ' checked="checked"' : '';  ?>/>
		<span class="help" id="fblike_param_show_faces_help">
			<?php _e("Show facebook profile pictures below the button."); ?></span>
	</li>
	<li class="setting_class">
			<label class="description" for="fblike_settings_cssclass"><?php _e("CSS Class"); ?></label>
			<input id="fblike_settings_cssclass" name="fblike_settings_cssclass" class="text" type="text" maxlength="50" value="<?php echo $fblike_settings_cssclass ?>"/> 
			<span class="help" id="fblike_settings_cssclass_help"><?php _e("CSS class encapsulating the button code. The default is fbLikeContainer."); ?></span>
	</li>


	<li class="last submit">
		<input type="submit" name="Submit" value="<?php _e('Update Options', 'fblike_trdom' ) ?>" />  
		<span class="warning" id="submit_warning">
			<?php _e('Please Note: the preview does not automatically save your changes'); ?></span>
	</li>
	</ul>
   </fieldset><!-- /.fblCellParameters -->
</form>  
</div>  

