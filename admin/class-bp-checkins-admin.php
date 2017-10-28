<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_Checkins_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->bpchk_save_general_settings();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if( ( strpos( $_SERVER['REQUEST_URI'], 'bp-checkins' ) !== false )) {
			wp_enqueue_style( $this->plugin_name.'-font-awesome', BPCHK_PLUGIN_URL . 'public/css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name.'-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-checkins-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( strpos( $_SERVER['REQUEST_URI'], 'bp-checkins' ) !== false ) {
			wp_enqueue_script( $this->plugin_name.'-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'bpchk_admin_js_obj',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				)
			);
		}

	}

	/**
	 * Register a menu page to handle checkins settings
	 *
	 * @since    1.0.0
	 */
	public function bpchk_add_menu_page() {
		add_menu_page( __( 'BuddyPress Checkins Settings', BPCHK_TEXT_DOMAIN ), __( 'Check-ins', BPCHK_TEXT_DOMAIN ), 'manage_options', $this->plugin_name, array( $this, 'bpchk_admin_settings_page' ), 'dashicons-location', 59 );
	}

	/**
	 * Actions performed to create a submenu page content
	 */
	public function bpchk_admin_settings_page() {
		$tab = isset($_GET['tab']) ? $_GET['tab'] : $this->plugin_name;
		?>
		<div class="wrap">
			<div class="bpchk-header">
				<div class="bpchk-extra-actions">
					<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/contact/', '_blank');"><i class="fa fa-envelope" aria-hidden="true"></i> <?php _e( 'Email Support', BPCHK_TEXT_DOMAIN )?></button>
					<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/helpdesk/article-categories/buddypress-checkins/', '_blank');"><i class="fa fa-file" aria-hidden="true"></i> <?php _e( 'User Manual', BPCHK_TEXT_DOMAIN )?></button>
					<button type="button" class="button button-secondary" onclick="window.open('https://wordpress.org/support/plugin/bp-check-in/reviews/', '_blank');"><i class="fa fa-star" aria-hidden="true"></i> <?php _e( 'Rate Us on WordPress.org', BPCHK_TEXT_DOMAIN )?></button>
				</div>
				<h2 class="bpchk-plugin-heading"><?php _e( 'BuddyPress Check-ins', BPCHK_TEXT_DOMAIN );?></h2>
			</div>
			<form method="POST" action="">

				<?php settings_errors();
				if( isset( $_POST['bpchk-submit-general-settings'] ) ) {
					$success_msg = "<div class='notice updated is-dismissible' id='message'>";
					$success_msg .= "<p>".__( '<strong>Settings Saved.</strong>', BPCHK_TEXT_DOMAIN )."</p>";
					$success_msg .= "</div>";
					echo $success_msg;
				}
				$this->bpchk_plugin_settings_tabs();
				settings_fields($tab); ?>
				<?php do_settings_sections( $tab );?>
			</form>
		</div>
		<?php
	}

	/**
	 * Actions performed to create tabs on the sub menu page
	 */
	public function bpchk_plugin_settings_tabs() {
		$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->plugin_name;
		echo '<h2 class="nav-tab-wrapper">';
		foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" id="' . $tab_key . '-tab" href="?page=' . $this->plugin_name . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * General Tab
	 */
	public function bpchk_plugin_settings() {
		//General settings tab
		$this->plugin_settings_tabs['bp-checkins'] = __( 'General', BPCHK_TEXT_DOMAIN );
		register_setting('bp-checkins', 'bp-checkins');
		add_settings_section('bp-checkins-section', ' ', array(&$this, 'bpchk_general_settings_content'), 'bp-checkins');

		//Support tab
		$this->plugin_settings_tabs['bpchk-support'] = __( 'Support', BPCHK_TEXT_DOMAIN );
		register_setting('bpchk-support', 'bpchk-support');
		add_settings_section('bpchk-support-section', ' ', array(&$this, 'bpchk_support_settings_content'), 'bpchk-support');
	}

	/**
	 * General Tab Content
	 */
	public function bpchk_general_settings_content() {
		if (file_exists(dirname(__FILE__) . '/includes/bp-checkins-general-settings.php')) {
			require_once( dirname(__FILE__) . '/includes/bp-checkins-general-settings.php' );
		}
	}

	/**
	 * Support Tab Content
	 */
	public function bpchk_support_settings_content() {
		if (file_exists(dirname(__FILE__) . '/includes/bp-checkins-support-settings.php')) {
			require_once( dirname(__FILE__) . '/includes/bp-checkins-support-settings.php' );
		}
	}

	/**
	 * Save Plugin General Settings
	 */
	function bpchk_save_general_settings() {
		if( isset( $_POST['bpchk-submit-general-settings'] ) ) {

			$checkin_by = '';
			if( isset( $_POST['bpchk-checkin-by'] ) ) {
				$checkin_by = sanitize_text_field( $_POST['bpchk-checkin-by'] );
			}

			$admin_settings = array(
				'apikey' => sanitize_text_field( $_POST['bpchk-api-key'] ),
				'checkin_by' => $checkin_by,
				'range' => sanitize_text_field( $_POST['bpchk-google-places-range'] ),
				'placetypes' => ( !empty( $_POST['bpchk-google-place-types'] ) ) ? wp_unslash( $_POST['bpchk-google-place-types'] ) : array(),
			);

			update_option( 'bpchk_general_settings', $admin_settings );
			
		}
	}

	/**
	 * Ajax served to delete the group type
	 */
	public function bpchk_verify_apikey() {
		if( isset( $_POST['action'] ) && $_POST['action'] == 'bpchk_verify_apikey' ) {
			$apikey = sanitize_text_field( $_POST['apikey'] );
			$latitude = sanitize_text_field( $_POST['latitude'] );
			$longitude = sanitize_text_field( $_POST['longitude'] );
			$radius = 10000;


			$response = Bp_Checkins::bpchk_fetch_google_places( $apikey, $latitude, $longitude, $radius );
			$code = wp_remote_retrieve_response_code( $response );
			$message = 'verified';
			update_option( 'bpchk_apikey_verified', 'yes' );
			if( $code != 200 ) {
				$message = 'not-verified';
				update_option( 'bpchk_apikey_verified', 'no' );
			}

			$response = array( 'message' => $message );
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * This function will list the checkin link in the dropdown listy
	 */
	public function bpchk_setup_admin_bar_links( $wp_admin_nav = array() ) {
		global $wp_admin_bar;
		$profile_menu_slug = 'checkin';
		$profile_menu_title = __( 'Check-ins', BPCHK_TEXT_DOMAIN );

		$base_url		 = bp_loggedin_user_domain() . $profile_menu_slug;
		if ( is_user_logged_in() ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account-buddypress',
				'id'	 => 'my-account-' . $profile_menu_slug,
				'title'	 => $profile_menu_title,
				'href'	 => trailingslashit( $base_url )
			) );
		}
	}

}