<?php


function holding_page(){
    $options = get_option('aurer_theme_options');
    if( !is_user_logged_in() && $options['enable_holding_page'] == "checked" ){
    	if( $_SERVER['REQUEST_URI'] != "/" ){
    		wp_redirect("/");
    	}
    	include get_template_directory()."/plugins/holding-page/holding-page.php";
    	exit;    
    }
}
add_action('template_redirect', 'holding_page');