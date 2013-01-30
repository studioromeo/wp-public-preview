<?php
/**
 * Plugin Name: WP Public Preview
 * Plugin URI:  http://jigoshop.com/
 * Description: Allows guests to preview themes
 * Version:     1.0
 * Author:      Jigoshop
 * Author URI:  http://jigoshop.com/
 */

function wp_preview_preview_theme() {
	if ( ! (isset($_GET['template']) && isset($_GET['preview'])) )
		return;

	// Admin Thickbox requests
	if ( isset( $_GET['preview_iframe'] ) )
		show_admin_bar( false );

	$_GET['template'] = preg_replace('|[^a-z0-9_./-]|i', '', $_GET['template']);

	if ( validate_file($_GET['template']) )
		return;

	add_filter( 'template', '_preview_theme_template_filter' );

	if ( isset($_GET['stylesheet']) ) {
		$_GET['stylesheet'] = preg_replace('|[^a-z0-9_./-]|i', '', $_GET['stylesheet']);
		if ( validate_file($_GET['stylesheet']) )
			return;
		add_filter( 'stylesheet', '_preview_theme_stylesheet_filter' );
	}

	// Prevent theme mods to current theme being used on theme being previewed
	add_filter( 'pre_option_theme_mods_' . get_option( 'stylesheet' ), '__return_empty_array' );

	ob_start( 'preview_theme_ob_filter' );
}

remove_action('setup_theme', 'preview_theme');
add_action('setup_theme', 'wp_preview_preview_theme');