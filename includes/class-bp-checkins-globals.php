<?php
/**
 * The file that defines the global variable of the plugin
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 **/

if ( ! class_exists( 'Bp_Checkins_Globals' ) ) :
	class Bp_Checkins_Globals {
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
		 * The google places API Key.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $apikey
		 */
		public $apikey;

		/**
		 * The user is allowed to checkin by 2 options : autocomplete or by placetype.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $checkin_by
		 */
		public $checkin_by;

		/**
		 * The variable that defines the range of the place types, default = 5kms.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $google_places_range
		 */
		public $google_places_range;

		/**
		 * The variable that stores all the place types to be fetched during checkin
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $place_types
		 */
		public $place_types;

		/**
		 * To set tab visibilty on member profile, dedfault = 1
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $place_types
		 */
		public $tab_visibility;

		/**
		 * To change tab name, default is Check-ins
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $place_types
		 */
		public $tab_name;

		/**
		 * Set buddypress xprofile field location as location for members
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $place_types
		 */
		public $enable_location_field;

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
			$this->version     = '1.0.0';
			$this->setup_plugin_global();
		}

		/**
		 * Include the following files that make up the plugin:
		 *
		 * - Bp_Checkins_Globals.
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function setup_plugin_global() {
			global $bp_checkins;
			$bpchk_settings = bp_get_option( 'bpchk_general_settings' );

			$this->apikey = '';
			if ( isset( $bpchk_settings['apikey'] ) ) {
				$this->apikey = $bpchk_settings['apikey'];
			}

			$this->checkin_by = 'autocomplete';
			if ( isset( $bpchk_settings['checkin_by'] ) ) {
				$this->checkin_by = $bpchk_settings['checkin_by'];
			}

			$this->google_places_range = 5;
			if ( isset( $bpchk_settings['range'] ) ) {
				$this->google_places_range = $bpchk_settings['range'];
			}

			$this->place_types = array();
			if ( isset( $bpchk_settings['placetypes'] ) ) {
				$this->place_types = $bpchk_settings['placetypes'];
			}

			$this->tab_visibility = 1;
			if ( isset( $bpchk_settings['tab_visibility'] ) ) {
				$this->tab_visibility = $bpchk_settings['tab_visibility'];
			}

			$this->tab_name = 'Check-ins';
			if ( isset( $bpchk_settings['tab_name'] ) ) {
				$this->tab_name = $bpchk_settings['tab_name'];
			}

			$this->enable_location_field = 1;
			if ( isset( $bpchk_settings['enable_location_field'] ) ) {
				$this->enable_location_field = $bpchk_settings['enable_location_field'];
			}
		}
	}
endif;
