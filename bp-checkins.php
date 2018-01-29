<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Bp_Checkins
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPress Check-ins
 * Plugin URI:        https://wbcomdesigns.com/downloads/buddypress-checkins/
 * Description:       This plugin allows BuddyPress members to share their location when they are posting activities, just like other social sites, you can add places where you visited.
 * Version:           1.0.8
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-checkins
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define Plugin Constants.
define( 'BPCHK_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BPCHK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'BPCHK_TEXT_DOMAIN' ) ) {
	define( 'BPCHK_TEXT_DOMAIN', 'bp-checkins' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-checkins-activator.php
 */
function activate_bp_checkins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins-activator.php';
	Bp_Checkins_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-checkins-deactivator.php
 */
function deactivate_bp_checkins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins-deactivator.php';
	Bp_Checkins_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_checkins' );
register_deactivation_hook( __FILE__, 'deactivate_bp_checkins' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_checkins() {
	$plugin = new Bp_Checkins();
	$plugin->run();
}

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires BuddyPress to be installed and active
 */
add_action( 'plugins_loaded', 'bpchk_plugin_init' );

/**
 * Check plugin requirement on plugins loaded,this plugin requires BuddyPress to be installed and active.
 *
 * @since    1.0.0
 */
function bpchk_plugin_init() {
	$bp_active = in_array( 'buddypress/bp-loader.php', get_option( 'active_plugins' ), true );
	if ( current_user_can( 'activate_plugins' ) && true !== $bp_active ) {
		add_action( 'admin_notices', 'bpchk_plugin_admin_notice' );
	} else {
		$bp_active_components = get_option( 'bp-active-components', true );
		if ( ! array_key_exists( 'activity', $bp_active_components ) ) {
			add_action( 'admin_notices', 'bpchk_plugin_require_activity_component_admin_notice' );
		} else {
			run_bp_checkins();
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bpchk_plugin_links' );
		}
	}
}

/**
 * Function to through notice when buddypress plugin is not activated.
 *
 * @since    1.0.0
 */
function bpchk_plugin_admin_notice() {
	$bpchk_plugin = 'BuddyPress Checkin';
	$bp_plugin    = 'BuddyPress';

	echo '<div class="error"><p>'
	. sprintf( esc_attr( '%1$s is ineffective as it requires %2$s to be installed and active.', 'bp-checkins' ), '<strong>' . esc_attr( $bpchk_plugin ) . '</strong>', '<strong>' . esc_attr( $bp_plugin ) . '</strong>' )
	. '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * Function to through notice when buddypress activity component is not activated.
 *
 * @since    1.0.0
 */
function bpchk_plugin_require_activity_component_admin_notice() {
	$bpchk_plugin = 'BuddyPress Checkin';
	$bp_component = 'BuddyPress\'s Activity Component';

	echo '<div class="error"><p>'
	. sprintf( esc_attr( '%1$s is ineffective now as it requires %2$s to be active.', 'bp-checkins' ), '<strong>' . esc_attr( $bpchk_plugin ) . '</strong>', '<strong>' . esc_attr( $bp_component ) . '</strong>' )
	. '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * Function to set plugin actions links.
 *
 * @param    array $links    Plugin settings link array.
 * @since    1.0.0
 */
function bpchk_plugin_links( $links ) {
	$bpchk_links = array(
		'<a href="' . admin_url( 'admin.php?page=bp-checkins' ) . '">' . __( 'Settings', 'bp-checkins' ) . '</a>',
		'<a href="https://wbcomdesigns.com/contact/" target="_blank">' . __( 'Support', 'bp-checkins' ) . '</a>',
	);
	return array_merge( $links, $bpchk_links );
}
