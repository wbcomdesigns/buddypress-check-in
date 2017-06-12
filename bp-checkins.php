<?php
/*
Plugin Name: BuddyPress Check-In
Plugin URI: https://wbcomdesigns.com/contact/
Description: This plugin adds extra feature to BuddyPress Activity updates allowing users to check-ins.
Version: 1.0.5
Author: Wbcom Designs
Author URI: https://wbcomdesigns.com
Text Domain: bp-checkins
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'BPCHK_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'BPCHK_PLUGIN_URL', plugin_dir_url(__FILE__) );

//Include needed files
$include_files = array(
	'admin/bpchk-admin.php',
	'includes/bpchk-scripts.php',
	'includes/bpchk-filters.php',
	'includes/bpchk-ajax.php',
);
foreach( $include_files  as $include_file ) {
	include $include_file;
}

//BP Checkins Plugin Activation
register_activation_hook( __FILE__, 'bpchk_plugin_activation' );
function bpchk_plugin_activation() {
	//Check if "Buddypress" plugin is active or not
	if (!in_array('buddypress/bp-loader.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		//Buddypress Plugin is inactive, hence deactivate this plugin
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'The <b>BP Checkins</b> plugin requires <b>Buddypress</b> plugin to be installed and active. Return to <a href="'.admin_url('plugins.php').'">Plugins</a>' );
	}
}

/* custom post type function */
function create_place_post_type() {
	register_post_type( 'place',
		array(
			'labels' => array(
				'name' => __( 'Place' ),
				'singular_name' => __( 'Place' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'place'),
		)
	);
}
add_action( 'init', 'create_place_post_type' );

//Settings link for this plugin
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bpchk_settings_link' );
function bpchk_settings_link( $links ){
	$settings_link = array( '<a href="'.admin_url('admin.php?page=bp-checkins').'">Setup</a>' );
	return array_merge( $links, $settings_link );
}
