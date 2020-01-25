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
 * Version:           1.3.0
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
define( 'BP_CHECKINS_PLUGIN_BASENAME',  plugin_basename( __FILE__ ) );
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
add_action( 'bp_loaded', 'bpchk_plugin_init' );

/**
 * Check plugin requirement on plugins loaded,this plugin requires BuddyPress to be installed and active.
 *
 * @since    1.0.0
 */
function bpchk_plugin_init() {
	if ( bp_checkins_check_config() ){
		run_bp_checkins();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bpchk_plugin_links' );
	}
}

function bp_checkins_check_config(){
	global $bp;
	
	$config = array(
		'blog_status'    => false, 
		'network_active' => false, 
		'network_status' => true 
	);
	if ( get_current_blog_id() == bp_get_root_blog_id() ) {
		$config['blog_status'] = true;
	}
	
	$network_plugins = get_site_option( 'active_sitewide_plugins', array() );

	// No Network plugins
	if ( empty( $network_plugins ) )

	// Looking for BuddyPress and bp-activity plugin
	$check[] = $bp->basename;
	$check[] = BP_CHECKINS_PLUGIN_BASENAME;

	// Are they active on the network ?
	$network_active = array_diff( $check, array_keys( $network_plugins ) );
	
	// If result is 1, your plugin is network activated
	// and not BuddyPress or vice & versa. Config is not ok
	if ( count( $network_active ) == 1 )
		$config['network_status'] = false;

	// We need to know if the plugin is network activated to choose the right
	// notice ( admin or network_admin ) to display the warning message.
	$config['network_active'] = isset( $network_plugins[ BP_CHECKINS_PLUGIN_BASENAME ] );

	// if BuddyPress config is different than bp-activity plugin
	if ( !$config['blog_status'] || !$config['network_status'] ) {

		$warnings = array();
		if ( !bp_core_do_network_admin() && !$config['blog_status'] ) {
			add_action( 'admin_notices', 'bpcheckins_same_blog' );
			$warnings[] = __( 'BuddyPress Check-ins requires to be activated on the blog where BuddyPress is activated.', 'bp-checkins'  );
		}

		if ( bp_core_do_network_admin() && !$config['network_status'] ) {
			add_action( 'admin_notices', 'bpcheckins_same_network_config' );
			$warnings[] = __( 'BuddyPress Check-ins and BuddyPress need to share the same network configuration.',  'bp-checkins');
		}
		$bp_active_components = bp_get_option( 'bp-active-components');
		if ( ! array_key_exists( 'activity', $bp_active_components ) ) {
			add_action( $config['network_active'] ? 'network_admin_notices' : 'admin_notices', 'bpchk_plugin_require_activity_component_admin_notice' );
			$warnings[] = __( 'Activity component required.',  'bp-checkins');
		}
		if ( ! empty( $warnings ) ) :
			return false;
		endif;
	}
	return true;
}
function bpcheckins_same_blog(){
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Check-ins requires to be activated on the blog where BuddyPress is activated.', 'bp-checkins'  ) )
	. '</p></div>';
}

function bpcheckins_same_network_config(){
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Check-ins and BuddyPress need to share the same network configuration.', 'bp-checkins' ) )
	. '</p></div>';
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
