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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->bpchk_save_general_settings();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( ( strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), 'bp-checkins' ) !== false ) ) {
			wp_enqueue_style( $this->plugin_name . '-font-awesome', BPCHK_PLUGIN_URL . 'public/css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name . '-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-checkins-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), 'bp-checkins' ) !== false ) {
			wp_enqueue_script( $this->plugin_name . '-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'bpchk_admin_js_obj',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
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
		add_menu_page( __( 'BuddyPress Checkins Settings', 'bp-checkins' ), __( 'Check-ins', 'bp-checkins' ), 'manage_options', $this->plugin_name, array( $this, 'bpchk_admin_settings_page' ), 'dashicons-location', 59 );
	}

	/**
	 * Actions performed to create a submenu page content
	 */
	public function bpchk_admin_settings_page() {
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : $this->plugin_name;
		?>
		<div class="wrap">
			<div class="bpchk-header">
				<div class="bpchk-extra-actions">
					<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/contact/', '_blank');"><i class="fa fa-envelope" aria-hidden="true"></i> <?php esc_html_e( 'Email Support', 'bp-checkins' ); ?></button>
					<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/helpdesk/article-categories/buddypress-checkins/', '_blank');"><i class="fa fa-file" aria-hidden="true"></i> <?php esc_html_e( 'User Manual', 'bp-checkins' ); ?></button>
					<button type="button" class="button button-secondary" onclick="window.open('https://wordpress.org/support/plugin/bp-check-in/reviews/', '_blank');"><i class="fa fa-star" aria-hidden="true"></i> <?php esc_html_e( 'Rate Us on WordPress.org', 'bp-checkins' ); ?></button>
				</div>
				<h2 class="bpchk-plugin-heading"><?php esc_html_e( 'BuddyPress Check-ins', 'bp-checkins' ); ?></h2>
			</div>
			<form method="POST" action="">

				<?php
				settings_errors();
				if ( filter_input( INPUT_POST, 'bpchk-submit-general-settings' ) !== null ) {
					$success_msg  = "<div class='notice updated is-dismissible' id='message'>";
					$success_msg .= '<p>' . __( '<strong>Settings Saved.</strong>', 'bp-checkins' ) . '</p>';
					$success_msg .= '</div>';
					echo wp_kses( $success_msg, $allowedposttags );
				}
				$this->bpchk_plugin_settings_tabs();
				settings_fields( $tab );
				?>
				<?php do_settings_sections( $tab ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Actions performed to create tabs on the sub menu page
	 */
	public function bpchk_plugin_settings_tabs() {
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : $this->plugin_name;
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=' . esc_attr( $this->plugin_name ) . '&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * General Tab.
	 */
	public function bpchk_plugin_settings() {
		// General settings tab.
		$this->plugin_settings_tabs['bp-checkins'] = __( 'General', 'bp-checkins' );
		register_setting( 'bp-checkins', 'bp-checkins' );
		add_settings_section( 'bp-checkins-section', ' ', array( &$this, 'bpchk_general_settings_content' ), 'bp-checkins' );

		// Support tab.
		$this->plugin_settings_tabs['bpchk-support'] = __( 'Support', 'bp-checkins' );
		register_setting( 'bpchk-support', 'bpchk-support' );
		add_settings_section( 'bpchk-support-section', ' ', array( &$this, 'bpchk_support_settings_content' ), 'bpchk-support' );
	}

	/**
	 * General Tab Content
	 */
	public function bpchk_general_settings_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-general-settings.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/bp-checkins-general-settings.php';
		}
	}

	/**
	 * Support Tab Content
	 */
	public function bpchk_support_settings_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-support-settings.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/bp-checkins-support-settings.php';
		}
	}

	/**
	 * Save Plugin General Settings
	 */
	public function bpchk_save_general_settings() {
		if ( filter_input( INPUT_POST, 'bpchk-submit-general-settings' ) !== null ) {
			$checkin_by = '';
			if ( filter_input( INPUT_POST, 'bpchk-checkin-by' ) !== null ) {
				$checkin_by = filter_input( INPUT_POST, 'bpchk-checkin-by', FILTER_SANITIZE_STRING );
			}

			$admin_settings = array(
				'apikey'     => filter_input( INPUT_POST, 'bpchk-api-key', FILTER_SANITIZE_STRING ),
				'checkin_by' => $checkin_by,
				'range'      => filter_input( INPUT_POST, 'bpchk-google-places-range', FILTER_SANITIZE_STRING ),
				'placetypes' => ( ! empty( $_POST['bpchk-google-place-types'] ) ) ? wp_unslash( $_POST['bpchk-google-place-types'] ) : array(),
			);

			bp_update_option( 'bpchk_general_settings', $admin_settings );

		}
	}

	/**
	 * Ajax served to delete the group type
	 */
	public function bpchk_verify_apikey() {
		if ( filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING ) && filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING ) === 'bpchk_verify_apikey' ) {
			$apikey    = filter_input( INPUT_POST, 'apikey', FILTER_SANITIZE_STRING );
			$latitude  = filter_input( INPUT_POST, 'latitude', FILTER_SANITIZE_STRING );
			$longitude = filter_input( INPUT_POST, 'longitude', FILTER_SANITIZE_STRING );
			$radius    = 10000;

			$response = Bp_Checkins::bpchk_fetch_google_places( $apikey, $latitude, $longitude, $radius );
			$code     = wp_remote_retrieve_response_code( $response );
			$message  = 'verified';
			bp_update_option( 'bpchk_apikey_verified', 'yes' );
			if ( 200 !== $code ) {
				$message = 'not-verified';
				bp_update_option( 'bpchk_apikey_verified', 'no' );
			}

			$response = array( 'message' => $message );
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * This function will list the checkin link in the dropdown list.
	 *
	 * @param    array $wp_admin_nav    BuddyPress Check-ins nav array.
	 */
	public function bpchk_setup_admin_bar_links( $wp_admin_nav = array() ) {
		global $wp_admin_bar;
		$profile_menu_slug  = 'checkin';
		$profile_menu_title = __( 'Check-ins', 'bp-checkins' );

		$base_url = bp_loggedin_user_domain() . $profile_menu_slug;
		if ( is_user_logged_in() ) {
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'my-account-buddypress',
					'id'     => 'my-account-' . $profile_menu_slug,
					'title'  => $profile_menu_title,
					'href'   => trailingslashit( $base_url ),
				)
			);
		}
	}

}
