<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(class_exists("WP_REST_Request")){
	include_once(ABSPATH . 'wp-includes/pluggable.php');
	if(current_user_can('wplc_ma_agent')) {
		include_once "wplc-api-routes.php";
		include_once "wplc-api-functions.php";
	}
}