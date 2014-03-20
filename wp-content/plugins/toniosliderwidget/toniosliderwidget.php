<?php
/*
Plugin Name: Tonio Slider Widget
Plugin URI: 
Description: 
Version: 
Author: Antonio Cordasco
Author URI: 
*/


add_action( 'widgets_init', 'tlw_load_widgets' );


function tlw_load_widgets() {
	register_widget( 'Tonio_Slider_Widget' );
}









class Tonio_Slider_Widget extends WP_Widget {

	function Tonio_Slider_Widget() {
		$widget_ops = array( 'classname' => 'example', 'description' => __('News slider', 'example') );

		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'example-widget' );

		$this->WP_Widget( 'example-widget', __('Tonio Slider Widget', 'example'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$link_posts = get_posts(array('category' => $GLOBALS['temperamente']['hidden_category_ids']['sidebar-links'], 'numberposts'=>5,'orderby'=>'date','order'=>'DESC'));
   
		$html_array = array();
		for($ii = 0; $ii<count($link_posts); $ii++) {
			if($ii == count($link_posts)-1){ $class_tmp = ' class="last" '; }else{ $class_tmp = ''; }
			$html_array[] = "\t<div $class_tmp >\n" . $link_posts[$ii]->post_content . "</div>\n";
		}
		if(count($html_array) > 0) {
			echo '<li class="widget tonio-slider " >' . implode('', $html_array) . '</li>';
		}

	}
}
