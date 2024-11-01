<?php
/*
Plugin Name: WP Count
Description: Display stats about themes and plugins.
Version: 0.1
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: GPL
License URI: https://www.gnu.org/licenses/gpl.html
*/

if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

add_filter( 'widget_text', 'do_shortcode' );

add_shortcode( 'theme-download-count', 'wp_count_theme_shortcode' );
function wp_count_theme_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'slug' => '',
	), $atts );
	ob_start();
	$args = ( object ) array( 'slug' => $atts['slug'] ? $atts['slug'] : '' );
	$request = array( 'action' => 'theme_information', 'timeout' => 15, 'request' => serialize( $args ) );
	$url = 'https://api.wordpress.org/themes/info/1.0/';
	$response = wp_remote_post( $url, array( 'body' => $request ) );
	$theme_info = unserialize( $response['body'] );
	echo number_format( round( $theme_info->downloaded, true ) );
	$output = ob_get_clean();
	return $output;
}

add_shortcode( 'plugin-download-count', 'wp_count_plugin_shortcode' );
function wp_count_plugin_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'slug' => '',
	), $atts );
	ob_start();
	$args = ( object ) array( 'slug' => $atts['slug'] ? $atts['slug'] : '' );
	$request = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args ) );
	$url = 'https://api.wordpress.org/plugins/info/1.0/';
	$response = wp_remote_post( $url, array( 'body' => $request ) );
	$plugin_info = unserialize( $response['body'] );
	echo number_format( round( $plugin_info->downloaded, true ) );
	$output = ob_get_clean();
	return $output;
}