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
		$post_type = '';
		if( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
			$post_type = get_post_type( $post_id );
		}
		if( ( strpos( $_SERVER['REQUEST_URI'], 'bp-checkins' ) !== false ) || $post_type == 'bpchk_places' ) {
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
		add_menu_page( __( 'BuddyPress Checkins Settings', BPCHK_TEXT_DOMAIN ), __( 'Checkins', BPCHK_TEXT_DOMAIN ), 'manage_options', $this->plugin_name, array( $this, 'bpchk_admin_settings_page' ), 'dashicons-location', 59 );
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
				<h2 class="bpchk-plugin-heading"><?php _e( 'BuddyPress Checkin', BPCHK_TEXT_DOMAIN );?></h2>
			</div>
			<?php $this->bpchk_plugin_settings_tabs();?>
			<?php do_settings_sections( $tab );?>
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
	public function bpchk_register_general_settings() {
		$this->plugin_settings_tabs['bp-checkins'] = __( 'General', BPCHK_TEXT_DOMAIN );
		register_setting('bp-checkins', 'bp-checkins');
		add_settings_section('bp-checkins-section', ' ', array(&$this, 'bpchk_general_settings_content'), 'bp-checkins');
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
	 * Support Tab
	 */
	public function bpchk_register_support_settings() {
		$this->plugin_settings_tabs['bpchk-support'] = __( 'Support', BPCHK_TEXT_DOMAIN );
		register_setting('bpchk-support', 'bpchk-support');
		add_settings_section('bpchk-support-section', ' ', array(&$this, 'bpchk_support_settings_content'), 'bpchk-support');
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
	 * Register A CPT - Places
	 */
	public function bpchk_register_places_cpt() {
		$labels = array(
			'name'               => __( 'Places', BPCHK_TEXT_DOMAIN ),
			'singular_name'      => __( 'Place', BPCHK_TEXT_DOMAIN ),
			'menu_name'          => __( 'Places', 'admin menu', BPCHK_TEXT_DOMAIN ),
			'name_admin_bar'     => __( 'Place', 'add new on admin bar', BPCHK_TEXT_DOMAIN ),
			'add_new'            => __( 'Add New', BPCHK_TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Place', BPCHK_TEXT_DOMAIN ),
			'new_item'           => __( 'New Place', BPCHK_TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Place', BPCHK_TEXT_DOMAIN ),
			'view_item'          => __( 'View Place', BPCHK_TEXT_DOMAIN ),
			'all_items'          => __( 'All Places', BPCHK_TEXT_DOMAIN ),
			'search_items'       => __( 'Search Places', BPCHK_TEXT_DOMAIN ),
			'parent_item_colon'  => __( 'Parent Places:', BPCHK_TEXT_DOMAIN ),
			'not_found'          => __( 'No Places Found.', BPCHK_TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No Places Found In Trash.', BPCHK_TEXT_DOMAIN )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'menu_icon'			 => 'dashicons-location-alt',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'bpchk_places' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'author' )
		);
		register_post_type( 'bpchk_places', $args );
	}

	/**
	 * Add meta box to ahow location of the added place
	 */
	public function bpchk_location_metabox() {
		add_meta_box( 'bpchk-place-metabox', __( 'Location', BPCHK_TEXT_DOMAIN ), array( $this, 'bpchk_place_metabox_content' ), 'bpchk_places', 'normal', 'high', null );
	}

	/**
	 * Location metabox - show content
	 */
	public function bpchk_place_metabox_content() {
		$file = BPCHK_PLUGIN_PATH.'admin/includes/bp-checkins-place-metabox-content.php';
		if( file_exists( $file ) ) {
			include_once $file;
		}
	}

	/**
	 * Add meta box to ahow location of the added place
	 */
	public function bpchk_place_visit_date_metabox() {
		add_meta_box( 'bpchk-placevisit-date-metabox', __( 'Visit Date', BPCHK_TEXT_DOMAIN ), array( $this, 'bpchk_place_visit_date_metabox_content' ), 'bpchk_places', 'side', 'low', null );
	}

	/**
	 * Location metabox - show content
	 */
	public function bpchk_place_visit_date_metabox_content() {
		global $post;
		$place_id = $post->ID;
		$place_details = get_post_meta( $place_id, 'place_details', true );
		$visit_date = isset( $place_details['visit_date'] ) ? $place_details['visit_date'] : '';
		echo date( "F jS, Y", strtotime( $visit_date ) );
	}

	/**
	 * Save Plugin General Settings
	 */
	function bpchk_save_general_settings() {
		if( isset( $_POST['bpchk-submit-general-settings'] ) && wp_verify_nonce( $_POST['bpchk-general-settings-nonce'], 'bpchk-general' ) ) {

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
			$success_msg = "<div class='notice updated is-dismissible' id='message'>";
			$success_msg .= "<p>".__( '<strong>Settings Saved.</strong>', BPCHK_TEXT_DOMAIN )."</p>";
			$success_msg .= "</div>";
			echo $success_msg;
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
		$profile_menu_title = 'Checkin';

		$base_url		 = bp_loggedin_user_domain() . $profile_menu_slug;
		$place_add_url	 = $base_url . '/add-place';
		$place_list_url	 = $base_url . '/my-places';
		if ( is_user_logged_in() ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account-buddypress',
				'id'	 => 'my-account-' . $profile_menu_slug,
				'title'	 => __( $profile_menu_title, BPCHK_TEXT_DOMAIN ),
				'href'	 => trailingslashit( $place_list_url )
			) );

			// Add add-new submenu
			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account-' . $profile_menu_slug,
				'id'	 => 'my-account-' . $profile_menu_slug . '-' . 'my-places',
				'title'	 => __( 'My Places', BPCHK_TEXT_DOMAIN ),
				'href'	 => trailingslashit( $place_list_url )
			) );

			// Add add-new submenu
			$wp_admin_bar->add_menu( array(
				'parent' => 'my-account-' . $profile_menu_slug,
				'id'	 => 'my-account-' . $profile_menu_slug . '-' . 'add-place',
				'title'	 => __( 'Add Place', BPCHK_TEXT_DOMAIN ),
				'href'	 => trailingslashit( $place_add_url )
			) );
		}
	}

}