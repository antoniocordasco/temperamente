<?php 

/*
Template Name: Ajax
*/



if(isset($_GET['action'])) {
	include(ABSPATH . 'wp-content/themes/Owni/ajax/' . $_GET['action'] . '.php');
}