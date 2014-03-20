<?php


function tfb_log_debug_info()
{	
	$log_dir_path = ABSPATH .'wp-content/plugins/toniofacebook/log/';
	$filename = $log_dir_path.date('Y-m-d_H.i.s').'.log';
	$fh = fopen($filename, 'w');
	
	$mydir = opendir($log_dir_path);
	while(false !== ($file = readdir($mydir)))
	{
        if(!is_dir($dir.$file)) 
		{
			$split = explode('_',$file);
			if(!is_dir($log_dir_path.$file) AND $split[0] != date('Y-m-d') AND $split[0] != date('Y-m-d',time()-24*60*60)){ unlink($log_dir_path.$file); }				
        }        
    }	
	
	fwrite($fh, 'REFERRER: '.$_SERVER['HTTP_REFERER']."\n");
	fwrite($fh, "\nIP: ".$_SERVER['REMOTE_ADDR']."\n");
	fwrite($fh, "\nSESSION: \n");
	foreach($_SESSION as $k => $v)
	{
		fwrite($fh, "$k => $v \n");
	}
	fwrite($fh, "\nGET: \n");
	foreach($_GET as $k => $v)
	{
		fwrite($fh, "$k => $v \n");
	}
	fclose($fh);
	chmod($filename,0777);
}


function tfb_log_debug_ajax($debug_text)
{
	$log_dir_path = ABSPATH .'wp-content/plugins/toniofacebook/log/ajax/';
	$filename = $log_dir_path.date('Y-m-d_H.i.s').'.log';
	$fh = fopen($filename, 'w');
	fwrite($fh, $debug_text);
	fclose($fh);
	chmod($filename,0777);


}