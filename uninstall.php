<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

global $wpdb;

$table = $wpdb->prefix.'location-map';
$wpdb->query( "DROP TABLE IF EXISTS `$table`");

// $option = $wpdb->prefix.'options';
// $wpdb->query( "DROP TABLE IF EXISTS `$table`");


    


