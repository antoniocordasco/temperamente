<?php
/*
Plugin Name: Tonio Archivi Widget
Plugin URI: 
Description: 
Version: 
Author: Antonio Cordasco
Author URI: 
*/

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'archivi_load_widgets' );

/**
 * Register our widget.
 * 'Archivi_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function archivi_load_widgets() {
	register_widget( 'Archivi_Widget' ); 
}

/**
 * Archivi Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Archivi_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Archivi_Widget() { 
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'archivi-widget', 'description' => __('Archives in italian.', 'example') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'archivi-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'archivi-widget', __('Archivi Widget', 'archivi-widget'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
    $replace_from = array('January','February','March','April','May','June','July','August','September','October','November','December');
    $replace_to = array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');    
    
		$res = $GLOBALS['wpdb']->get_results("SELECT post_date FROM wp_posts WHERE post_status = 'publish' AND post_type='post' ORDER BY post_date DESC");
    $months = array();
    $tmp_str = '';
    foreach($res as $row) {
      if(!isset($months[substr($row->post_date,0,7)])) {
        $months[substr($row->post_date,0,7)] = true;
        $time_tmp = strtotime($row->post_date);
        $tmp_str .= '<li><a title="'.date('F Y',$time_tmp).'" href="/'.date('Y',$time_tmp).'/'.date('m',$time_tmp).'/">'.date('F Y',$time_tmp).'</a></li>';
      }
    }
    
    ?>
    <li class="widget archivi" >
      <p class="widget-title">Archivi</p>		
      <ul><?php echo str_replace($replace_from,$replace_to,$tmp_str); ?></ul>
    </li>
<?php
	}
}
