<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_Checkins {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bp_Checkins_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'bp-checkins';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_globals();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bp_Checkins_Loader. Orchestrates the hooks of the plugin.
	 * - Bp_Checkins_i18n. Defines internationalization functionality.
	 * - Bp_Checkins_Admin. Defines all hooks for the admin area.
	 * - Bp_Checkins_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-checkins-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-checkins-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bp-checkins-admin.php';

		/**
		 * The class responsible for defining the global variable of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-checkins-globals.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bp-checkins-public.php';

		$this->loader = new Bp_Checkins_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bp_Checkins_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bp_Checkins_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bp_Checkins_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'bpchk_register_places_cpt' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'bpchk_places_metabox' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'bpchk_add_menu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'bpchk_plugin_settings' );
		$this->loader->add_action( 'bp_setup_admin_bar', $plugin_admin, 'bpchk_setup_admin_bar_links', 80 );

		//Ajax call for verifying the API Key
		$this->loader->add_action( 'wp_ajax_bpchk_verify_apikey', $plugin_admin, 'bpchk_verify_apikey' );
		$this->loader->add_action( 'wp_ajax_nopriv_bpchk_verify_apikey', $plugin_admin, 'bpchk_verify_apikey' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bp_Checkins_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'bp_setup_nav', $plugin_public, 'bpchk_member_profile_checkin_tab' );
		$this->loader->add_action( 'bp_activity_posted_update', $plugin_public, 'bpchk_update_meta_on_post_update', 10, 3 );
		$this->loader->add_action( 'bp_activity_entry_content', $plugin_public, 'bpchk_show_google_map_in_checkin_activity', 10 );
		$this->loader->add_action( 'bp_member_header_actions', $plugin_public, 'bpchk_add_place_button_on_member_header' );
		$this->loader->add_action( 'wp_ajax_bpchk_save_temp_location', $plugin_public, 'bpchk_save_temp_location' );
		$this->loader->add_action( 'wp_ajax_bpchk_fetch_places', $plugin_public, 'bpchk_fetch_places' );
		$this->loader->add_action( 'wp_ajax_bpchk_select_place_to_checkin', $plugin_public, 'bpchk_select_place_to_checkin' );
		$this->loader->add_action( 'wp_ajax_bpchk_cancel_checkin', $plugin_public, 'bpchk_cancel_checkin' );
	}

	/**
	 * Registers a global variable of the plugin - bp-checkins
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function define_globals() {
		global $bp_checkins;
		$bp_checkins = new Bp_Checkins_Globals( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bp_Checkins_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static function bpchk_fetch_google_places( $apikey, $lat = '', $lon = '', $radius = '' ){
		$places_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
		$parameters = array(
			'location' => "$lat,$lon",
			'radius' => $radius,
			'key' => $apikey
		);
		$url = add_query_arg( $parameters, esc_url_raw( $places_url ) );
		$response = wp_remote_get( esc_url_raw( $url ) );
		return $response;
	}

}
