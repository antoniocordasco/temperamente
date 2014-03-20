<?php
/*
Plugin Name: Tonio filter links
Plugin URI: 
Version: 1.0
Author: 
Author URI: 
*/
	
  
  
add_action('submitlink_box', 'tonio_filter_links_help');
$GLOBALS['tonio_filter_links_help'] = false;

function tonio_filter_links_help() {
  if(!$GLOBALS['tonio_filter_links_help']) {
?>
  <div>
    <p><b>Link rating:</b>
    <ul>
      <li>0 = only homepage</li>
      <li>1 = everywhere except posts pages</li>
      <li>&gt;1 = everywhere</li>
    </ul>
   </p>
  </div>
<?    
    $GLOBALS['tonio_filter_links_help'] = true;
  }
}

add_filter('widget_links_args', 'tonio_filter_links');


function tonio_filter_links($params) { 
  $params['orderby'] = 'rating';
  $params['order'] = 'DESC';
  $cond_tmp = " link_visible='Y' AND link_id IN (SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = ".$params['category'].") ";
  if(is_home()) {    
    $params['limit'] = 999;    
  }
  elseif(is_single()){
    $rows = $GLOBALS['wpdb']->get_results('SELECT COUNT(link_id) AS num FROM wp_links WHERE '.$cond_tmp.' AND link_rating > 1');
    if(isset($rows[0])) {
    $params['limit'] = $rows[0]->num;
    }
  }
  else{
    $rows = $GLOBALS['wpdb']->get_results('SELECT COUNT(link_id) AS num FROM wp_links WHERE '.$cond_tmp.' AND link_rating > 0');
    if(isset($rows[0])) {
    $params['limit'] = $rows[0]->num;
    }
  }
  // echo '<!-- limit '.$params['limit'];  var_dump($params);  echo ' -->';
  return $params;
}