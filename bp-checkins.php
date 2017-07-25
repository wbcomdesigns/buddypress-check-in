<?php
/**
 * Plugin Name: BuddyPress Check-In
 * Plugin URI: https://wbcomdesigns.com/contact/
 * Description: This plugin adds extra feature to BuddyPress Activity updates allowing users to check-ins.
 * Version: 1.0.5
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * Text Domain: bp-checkins
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $bp_checkin;
define( 'BPCHK_TEXT_DOMAIN', 'bp-checkins' );

function run_bp_checkin_plugin() {
	define( 'BPCHK_PLUGIN_PATH', plugin_dir_path(__FILE__) );
	define( 'BPCHK_PLUGIN_URL', plugin_dir_url(__FILE__) );
	
	$include_files = array(
		'admin/bpchk-admin.php',
		'includes/bpchk-scripts.php',
		'includes/bpchk-filters.php',
		'includes/bpchk-ajax.php',
	);
	foreach( $include_files  as $include_file ) include $include_file;
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

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires buddypress to be installed and active
 */
add_action('plugins_loaded', 'bpchk_plugin_init');
function bpchk_plugin_init() {
	$bp_active = in_array('buddypress/bp-loader.php', get_option('active_plugins'));
	if ( current_user_can('activate_plugins') && $bp_active !== true ) {
		add_action('admin_notices', 'bpchk_plugin_admin_notice');
	} else {
		run_bp_checkin_plugin();
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bpchk_admin_settings_link' );
	}
}

function bpchk_plugin_admin_notice() {
	$bpfit_plugin = 'BuddyPress Check-In';
	$bp_plugin = 'BuddyPress';

	echo '<div class="error"><p>'.sprintf(__('%1$s is ineffective now as it requires %2$s to function correctly.', BPCHK_TEXT_DOMAIN ), '<strong>' . esc_html($bpfit_plugin) . '</strong>', '<strong>' . esc_html($bp_plugin) . '</strong>').'</p></div>';
	if (isset($_GET['activate'])) unset($_GET['activate']);
}

function bpchk_admin_settings_link( $links ){
	$settings_link = array( '<a href="'.admin_url('admin.php?page=bp-checkins').'">'.__( 'Settings', BPCHK_TEXT_DOMAIN ).'</a>' );
	return array_merge( $links, $settings_link );
}