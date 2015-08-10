<?php
/*
Plugin Name: Easy WP Cleaner
Plugin URI: http://www.nikunjsoni.co.in/
Description: Easy WP Cleaner is user friendly plugin to clean unnecessary data from WordPress database like "revision", "draft", "auto draft", "moderated comments", "spam comments", "trash comments", "orphan postmeta", "orphan commentmeta", "orphan relationships", "dashboard transient feed" and this plugin also allows you to optimize your WordPress database without any tool like phpMyAdmin.
Version: 1.0
Author: Nikunj Soni
Author URI: http://www.nikunjsoni.co.in/
Text Domain: Easy-WP-Cleaner
*/

function easy_wp_cleaner_settings_link($action_links,$plugin_file){
	if($plugin_file==plugin_basename(__FILE__)){
		$wcu_settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/easy-wp-cleaner-admin.php">' . __("Settings") . '</a>';
		array_unshift($action_links,$wcu_settings_link);
	}
	return $action_links;
}
add_filter('plugin_action_links','easy_wp_cleaner_settings_link',10,2);

if(is_admin()){
	require_once('easy-wp-cleaner-admin.php');
}

function easy_wp_cleaner_admin_script(){
	wp_register_style('admin-bootstrap',plugins_url( 'assets/css/bootstrap.min.css',__FILE__) );
	wp_register_style('admin-css',plugins_url( 'assets/css/admin.css',__FILE__) );
	
	wp_enqueue_style('admin-bootstrap');
	wp_enqueue_style('admin-css');
}

if($_REQUEST['page']=='easy-wp-cleaner/easy-wp-cleaner-admin.php'){
	add_action( 'wp_print_scripts', 'easy_wp_cleaner_admin_script' ); 
}
?>