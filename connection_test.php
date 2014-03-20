<?php

$link = mysql_connect('62.149.150.140', 'Sql498585', '13d93c3d');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';


mysql_select_db('Sql498585_2'); 
var_dump($temp);
mysql_close($link);

die;