<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_Checkins_Public {

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
	public $bp_checkins;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $bp_checkins;
		$this->plugin_name	 = $plugin_name;
		$this->version		 = $version;
		$this->bp_checkins	 = &$bp_checkins;
		// global $bp_checkins;
		// $placetypes	 = implode( ',', $bp_checkins->place_types );
		// var_dump($placetypes);die;
	}

	/**
	 * Modify the html localized to main js file.
	 * @since    1.0.7
	 */
	public function alter_bpchk_checkin_html( $checkin_html ) {
		$checkin_html = '';
		return $checkin_html;
	}
	
	/**
	 * Render location pickup html to buddypress what's new section.
	 * @since    1.0.7
	 */
	public function render_location_pickup_html() {
		global $bp_checkins;
		$checkin_html = '';
		if ( is_user_logged_in() ) {
			//Create the checkin html
			if ( $bp_checkins->apikey ) {
				// $checkin_html .= '<div class="bpchk-checkin-html-container">';
					$checkin_html .= '<div class="bpchk-marker-container"><span class="bpchk-allow-checkin"><i class="fa fa-map-marker" aria-hidden="true"></i></span></div>';
					$checkin_html .= '<div class="bp-checkins bp-checkin-panel">';
					if ( $bp_checkins->checkin_by == 'autocomplete' ) {
						$checkin_html .= '<div class="checkin-by-autocomplete">';
							$checkin_html .= '<input type="text" id="bpchk-autocomplete-place" placeholder="' . __( 'Start typing your location...', BPCHK_TEXT_DOMAIN ) . '" />';
							$checkin_html .= '<input type="hidden" id="bpchk-checkin-place-lat" />';
							$checkin_html .= '<input type="hidden" id="bpchk-checkin-place-lng" />';
							$checkin_html .= '<input type="checkbox" id="bpchk-add-as-place" checked />';
							$checkin_html .= '<label for="bpchk-add-as-place">' . __( 'Add as my location', BPCHK_TEXT_DOMAIN ) . '</label>';
							$checkin_html .= '<span class="bpchk-place-loader">' . __( 'Saving location...', BPCHK_TEXT_DOMAIN ) . '<i class="fa fa-refresh fa-spin"></i></span><span class="clear"></span>';
						$checkin_html .= '</div>';
						$checkin_html .= '<div class="checkin-by-autocomplete-map" id="checkin-by-autocomplete-map"></div>';
						$checkin_html .= '<div class="clear"></div>';
					} else {
						$checkin_html .= '<div class="checkin-by-placetype">';
							$checkin_html .= '<p>' . __( 'Please Wait..', BPCHK_TEXT_DOMAIN ) . '</p>';
						$checkin_html .= '</div>';
					}
					$checkin_html .= '</div>';
				// $checkin_html .= '</div>';	
			}
		}
		echo $checkin_html;
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if( !bp_is_activity_component() ) { return; }
		wp_enqueue_style( $this->plugin_name . '-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css' );
		wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-checkins-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( !bp_is_activity_component() ) { return; }
		global $bp_checkins;
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( $this->plugin_name.'-google-places-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . $bp_checkins->apikey, array( 'jquery' ) );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
		$checkin_html = '';
		if ( is_user_logged_in() ) {

			//Create the checkin html

			if ( $bp_checkins->apikey ) {
				$checkin_html	 .= '<div><div class="bpchk-marker-container"><span class="bpchk-allow-checkin"><i class="fa fa-map-marker" aria-hidden="true"></i></span></div>';
				$checkin_html	 .= '<div class="bp-checkins bp-checkin-panel">';
				if ( $bp_checkins->checkin_by == 'autocomplete' ) {
					$checkin_html	 .= '<div class="checkin-by-autocomplete">';
					$checkin_html	 .= '<input type="text" id="bpchk-autocomplete-place" placeholder="' . __( 'Start typing your location...', BPCHK_TEXT_DOMAIN ) . '" />';
					$checkin_html	 .= '<input type="hidden" id="bpchk-checkin-place-lat" />';
					$checkin_html	 .= '<input type="hidden" id="bpchk-checkin-place-lng" />';
					$checkin_html	 .= '<input type="checkbox" id="bpchk-add-as-place" checked />';
					$checkin_html	 .= '<label for="bpchk-add-as-place">' . __( 'Add as my location', BPCHK_TEXT_DOMAIN ) . '</label>';
					$checkin_html	 .= '<span class="bpchk-place-loader">' . __( 'Saving location...', BPCHK_TEXT_DOMAIN ) . '<i class="fa fa-refresh fa-spin"></i></span><span class="clear"></span>';
					$checkin_html	 .= '</div>';
					$checkin_html	 .= '<div class="checkin-by-autocomplete-map" id="checkin-by-autocomplete-map">';
					$checkin_html	 .= '</div><div class="clear"></div>';
				} else {
					$checkin_html	 .= '<div class="checkin-by-placetype">';
					$checkin_html	 .= '<p>' . __( 'Please Wait..', BPCHK_TEXT_DOMAIN ) . '</p>';
					$checkin_html	 .= '</div>';
				}
				$checkin_html .= '</div>';
			}
			if ( xprofile_get_field_id_from_name( 'Location' ) ) {
				$bpchk_location_id	 = xprofile_get_field_id_from_name( 'Location' );
				$bpchk_loc_xprof	 = 'field_' . $bpchk_location_id;
			}


		}
		if(empty($bpchk_loc_xprof)){
			$bpchk_loc_xprof = '';
		}
		wp_localize_script(
			$this->plugin_name, 'bpchk_public_js_obj', array(
				'ajaxurl'			 => admin_url( 'admin-ajax.php' ),
				'checkin_html'         => apply_filters( 'alter_bpchk_checkin_html', $checkin_html ),
				'checkin_by'		 => $bp_checkins->checkin_by,
				'bpchk_loc_xprof'	 => $bpchk_loc_xprof
			)
		);
	}

	/**
	 * Register a new tab in member's profile - Checkin
	 *
	 * @since    1.0.1
	 */
	public function bpchk_member_profile_checkin_tab() {
		$name			 = bp_get_displayed_user_username();
		$displayed_uid	 = bp_displayed_user_id();
		$parent_slug	 = 'checkin';
		$add_place_link	 = bp_core_get_userlink( $displayed_uid, false, true ) . $parent_slug . '/add-place';
		$my_places_link	 = bp_core_get_userlink( $displayed_uid, false, true ) . $parent_slug . '/my-places';

		bp_core_new_nav_item(
			array(
				'name'						 => __( 'Check-ins', BPCHK_TEXT_DOMAIN ),
				'slug'						 => 'checkin',
				'screen_function'			 => array( $this, 'bpchk_checkin_tab_function_to_show_screen' ),
				'position'					 => 75,
				'default_subnav_slug'		 => 'my-places',
				'show_for_displayed_user'	 => true,
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'				 => __( 'Locations', BPCHK_TEXT_DOMAIN ),
				'slug'				 => 'my-places',
				'parent_url'		 => bp_core_get_userlink( $displayed_uid, false, true ) . $parent_slug . '/',
				'parent_slug'		 => $parent_slug,
				'screen_function'	 => array( $this, 'bpchk_checkins_activity_show_screen' ),
				'position'			 => 100,
				'link'				 => $my_places_link,
			)
		);
	}

	/**
	 * Screen function for listing all my places in menu item
	 */
	function bpchk_checkins_activity_show_screen() {
		add_action( 'bp_template_title', array( $this, 'bpchk_checkins_tab_function_to_show_title' ) );
		add_action( 'bp_template_content', array( $this, 'bpchk_checkins_tab_function_to_show_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * My Places - Title
	 */
	function bpchk_checkins_tab_function_to_show_title() {
		_e( 'My Locations', BPCHK_TEXT_DOMAIN );
	}

	/**
	 * My Places - Content
	 */
	function bpchk_checkins_tab_function_to_show_content() {
		$file = BPCHK_PLUGIN_PATH . 'public/templates/checkin/bp-checkins-activity.php';
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}

	/**
	 * Return country from json data
	 */
	public static function google_getCountry( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "country", $jsondata[ 'results' ][ 0 ][ 'address_components' ] );
	}

	/**
	 * Return province from json data
	 */
	public static function google_getProvince( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "administrative_area_level_1", $jsondata[ "results" ][ 0 ][ "address_components" ], true );
	}

	/**
	 * Return city from json data
	 */
	public static function google_getCity( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "locality", $jsondata[ "results" ][ 0 ][ "address_components" ] );
	}

	/**
	 * Return street from json data
	 */
	public static function google_getStreet( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "street_number", $jsondata[ "results" ][ 0 ][ "address_components" ] ) . ' ' . self::bpchk_find_long_name_given_type( "route", $jsondata[ "results" ][ 0 ][ "address_components" ] );
	}

	/**
	 * Return postal code from json data
	 */
	public static function google_getPostalCode( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "postal_code", $jsondata[ "results" ][ 0 ][ "address_components" ] );
	}

	/**
	 * Return country code from json data
	 */
	public static function google_getCountryCode( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "country", $jsondata[ "results" ][ 0 ][ "address_components" ], true );
	}

	/**
	 * Return formatted address from json data
	 */
	public static function google_getAddress( $jsondata ) {
		return $jsondata[ "results" ][ 0 ][ "formatted_address" ];
	}

	/**
	 * Searching in Google Geo json, return the long name given the type.
	 * (If short_name is true, return short name)
	 */
	public static function bpchk_find_long_name_given_type( $type, $array, $short_name = false ) {

		foreach ( $array as $value ) {
			if ( in_array( $type, $value[ "types" ] ) ) {
				if ( $short_name )
					return $value[ "short_name" ];
				return $value[ "long_name" ];
			}
		}
	}

	/**
	 * Ajax served to save the temporary location
	 */
	public function bpchk_save_temp_location() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'bpchk_save_temp_location' ) {
			$args		 = array(
				'place'				 => sanitize_text_field( $_POST[ 'place' ] ),
				'latitude'			 => sanitize_text_field( $_POST[ 'latitude' ] ),
				'longitude'			 => sanitize_text_field( $_POST[ 'longitude' ] ),
				'add_as_my_place'	 => sanitize_text_field( $_POST[ 'add_as_my_place' ] )
			);
			update_option( 'bpchk_temp_location', $args );
			$response	 = array( 'message' => 'temp-locaition-saved' );
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * Add location xprofile field.
	 * @since 1.0.1
	 */
	public function bpchk_add_location_xprofile_field() {
		if ( xprofile_get_field_id_from_name( 'Location' ) )
			return;
		$location_list_args	 = array(
			'field_group_id' => 1,
			'type'			 => 'textbox',
			'name'			 => 'Location',
			'description'	 => 'Please select your location',
			'is_required'	 => false,
			'can_delete'	 => true,
			'order_by'		 => 'default'
		);
		$location_list_id	 = xprofile_insert_field( $location_list_args );
	}

	/**
	 * Ajax request to save location xprofile field.
	 * @since 1.0.1
	 */
	public function bpchk_save_xprofile_location() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'bpchk_save_xprofile_location' ) {
			$args = array(
				'place'		 => sanitize_text_field( $_POST[ 'place' ] ),
				'latitude'	 => sanitize_text_field( $_POST[ 'latitude' ] ),
				'longitude'	 => sanitize_text_field( $_POST[ 'longitude' ] )
			);
			if ( xprofile_get_field_id_from_name( 'Location' ) ) {
				$bpchk_location_id = xprofile_get_field_id_from_name( 'Location' );
				bp_xprofile_update_meta( $bpchk_location_id, 'data', 'bpchk_loc_xprofile', $args );
			}
		}
		exit;
	}

	/**
	 * Function to filter location xprofile field value at profile page.
	 *
	 * @since 1.0.1
	 * @param string $value Value for the profile field.
	 * @param string $type  Type for the profile field.
	 * @param int    $id    ID for the profile field.
	 */
	public function bpchk_show_xprofile_location( $field_value, $field_type, $field_id ) {
		if ( xprofile_get_field_id_from_name( 'Location' ) ) {
			$bpchk_location_id = xprofile_get_field_id_from_name( 'Location' );
			if ( $field_id == $bpchk_location_id ) {
				$loc_xprof_meta = bp_xprofile_get_meta( $bpchk_location_id, 'data', 'bpchk_loc_xprofile' );
				if ( !empty( $loc_xprof_meta ) && is_array( $loc_xprof_meta ) ) {

					$field_value = '<a class=checkin-loc href="http://maps.google.com/maps/place/' . $loc_xprof_meta[ 'place' ] . '/@' . $loc_xprof_meta[ 'latitude' ] . ',' . $loc_xprof_meta[ 'longitude' ] . '" target="_blank" title="' . $loc_xprof_meta[ 'place' ] . '">' . $loc_xprof_meta[ 'place' ] . '</a>';
					return $field_value;
				}
			}
			return $field_value;
		} else {
			return $field_value;
		}
	}

	/**
	 * Function to add checkin activity types.
	 *
	 * @since 1.0.1
	 * @param array $types Value for the profile field.
	 */
	public function bpchk_add_checkin_activity_type( $types ) {
		$types[] = 'activity_bpchk_chkins';
		return $types;
	}

	/**
	 * Function to register activity action.
	 *
	 * @since 1.0.1
	 */
	public function custom_plugin_register_activity_actions() {

		$component_id = buddypress()->activity->id;

		bp_activity_set_action(
			$component_id, 'activity_bpchk_chkins', __( 'Check-ins Update', BPCHK_TEXT_DOMAIN ), array( $this, 'bp_activity_format_activity_action_activity_bpchk_chkins' ), __( 'Check-ins', BPCHK_TEXT_DOMAIN ), array( 'member' )
		);
	}

	/**
	 * Format 'activity_update' activity actions.
	 *
	 * @since 1.0.1
	 *
	 * @param string $action   Static activity action.
	 * @param object $activity Activity data object.
	 * @return string $action
	 */
	function bp_activity_format_activity_action_activity_bpchk_chkins( $action, $activity ) {
		//echo '<pre>'; print_r($activity); echo '</pre>';
		$action = sprintf( __( '%s checked-in', 'buddypress' ), bp_core_get_userlink( $activity->user_id ) );

		/**
		 * Filters the formatted activity action update string.
		 *
		 * @since 1.2.0
		 *
		 * @param string               $action   Activity action string value.
		 * @param BP_Activity_Activity $activity Activity item object.
		 */
		return apply_filters( 'bp_activity_new_checkin_action', $action, $activity );
	}

	/**
	 * Function to set activity type activity_bpchk_chkins.
	 *
	 * @since 1.0.1
	 * @param array $activity_object
	 */
	public function bpchk_update_activity_type_checkins( $activity_object ) {

		$bpchk_temp_location = get_option( 'bpchk_temp_location' );
		if ( $bpchk_temp_location ) {
			$activity_object->type = 'activity_bpchk_chkins';
		}
	}

	/**
	 * Action performed to save the activity update to show the checkin
	 * @since 1.0.1
	 */
	public function bpchk_update_meta_on_post_update( $content, $user_id, $activity_id ) {
		global $wpdb;
		$place_details	 = get_option( 'bpchk_temp_location' );
		$activity_tbl	 = $wpdb->base_prefix . 'bp_activity';

		if ( !empty( $place_details ) ) {
			$place			 = $place_details[ 'place' ];
			$longitude		 = $place_details[ 'longitude' ];
			$latitude		 = $place_details[ 'latitude' ];
			$add_as_my_place = $place_details[ 'add_as_my_place' ];

			$location_html	 = ' -at <a class=checkin-loc href="http://maps.google.com/maps/place/' . $place . '/@' . $latitude . ',' . $longitude . '" target="_blank" title="' . $place . '">' . $place . '</a>';
			$content		 .= $location_html;
			$pos			 = strpos( $content, '-at <a class="checkin-loc"' );
			//Update the activity content to post the checkin along with the post update
			if ( $pos === false ) {
				$wpdb->update(
					$activity_tbl, array( 'content' => $content ), array( 'id' => $activity_id ), array( '%s' ), array( '%d' )
				);

				//Update the location details in activity meta
				bp_activity_update_meta( $activity_id, 'bpchk_place_details', $place_details );
			}

			if ( $add_as_my_place == 'yes' ) {
				$bpchk_fav_places	 = get_user_meta( $user_id, 'bpchk_fav_places', true );
				$place_get_url		 = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
				$response			 = wp_remote_get( $place_get_url );

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( $response_code == 200 ) {
					$jsondata			 = json_decode( wp_remote_retrieve_body( $response ), true );
					$place_visit_date	 = date( 'Y-m-d', time() );

					if ( $jsondata[ "results" ][ 0 ][ "formatted_address" ] ) {
						$address						 = array();
						$address[ 'latitude' ]			 = $latitude;
						$address[ 'longitude' ]			 = $longitude;
						$address[ 'activity_id' ]		 = $activity_id;
						$address[ 'place' ]				 = $place;
						$address[ 'country' ]			 = self::google_getCountry( $jsondata );
						$address[ 'province' ]			 = self::google_getProvince( $jsondata );
						$address[ 'city' ]				 = self::google_getCity( $jsondata );
						$address[ 'street' ]			 = self::google_getStreet( $jsondata );
						$address[ 'postal_code' ]		 = self::google_getPostalCode( $jsondata );
						$address[ 'country_code' ]		 = self::google_getCountryCode( $jsondata );
						$address[ 'formatted_address' ]	 = self::google_getAddress( $jsondata );
						$address[ 'visit_date' ]		 = $place_visit_date;

						if ( $bpchk_fav_places ) {
							array_push( $bpchk_fav_places, $address );
							update_user_meta( $user_id, 'bpchk_fav_places', $bpchk_fav_places );
						} else {
							$fav_places		 = array();
							$fav_places[]	 = $address;
							update_user_meta( $user_id, 'bpchk_fav_places', $fav_places );
						}
					}
				}
			}
			/**
			 * Delete the temp location after posting update,
			 * so that the same place doesn't gets posted
			 * when no checkin is done.
			 */
			delete_option( 'bpchk_temp_location' );
		}
	}

	/**
	 * Show mep on checkin activities
	 * @since 1.0.1
	 */
	public function bpchk_show_google_map_in_checkin_activity() {
		$activity_id		 = bp_get_activity_id();
		global $wpdb, $bp_checkins;
		$activity_tbl		 = $wpdb->base_prefix . 'bp_activity';
		$activity_meta_tbl	 = $wpdb->base_prefix . 'bp_activity_meta';

		$qry	 = "SELECT `meta_value` from `$activity_meta_tbl` where `activity_id` = $activity_id AND `meta_key` = 'bpchk_place_details'";
		$result	 = $wpdb->get_results( $qry );
		if ( !empty( $result ) ) {
			$place			 = unserialize( $result[ 0 ]->meta_value );
			$apikey			 = $bp_checkins->apikey;
			$latitude		 = $place[ 'latitude' ];
			$longitude		 = $place[ 'longitude' ];
			$place_get_url	 = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
			$response		 = wp_remote_get( $place_get_url );

			$response_code = wp_remote_retrieve_response_code( $response );
			$formatted_address = $place[ 'place' ];
			if ( $response_code == 200 ) {
				$jsondata			 = json_decode( wp_remote_retrieve_body( $response ), true );
				if(isset ( $jsondata[ "results" ][ 0 ][ "formatted_address" ] )){
					$formatted_address	 = self::google_getAddress( $jsondata );
				}
			} else {
				$formatted_address = $place[ 'place' ];
			}
			$map_url = 'https://www.google.com/maps/embed/v1/place?key=' . $apikey . '&q=' . $formatted_address;
			echo '<div id="bpchk-place-map"><iframe frameborder="0" style="border:0" src="' . $map_url . '" allowfullscreen></iframe></div>';
		}
	}

	/**
	 * Ajax served to fetch the places on page load
	 */
	public function bpchk_fetch_places() {
		global $bp_checkins;
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'bpchk_fetch_places' ) {

			$apikey		 = $bp_checkins->apikey;
			$range		 = $bp_checkins->google_places_range * 1000;
			$placetypes	 = implode( '||', $bp_checkins->place_types );

			$latitude	 = $_POST[ 'latitude' ];
			$longitude	 = $_POST[ 'longitude' ];
			$places_html = '';

			$parameters		 = array(
				'location'	 => "$latitude,$longitude",
				'radius'	 => $range,
				'key'		 => $apikey,
				'type'		 => $placetypes,
				'heading'	 => true,
				'title'		 => true
			);
			$places_url		 = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
			$url			 = add_query_arg( $parameters, esc_url_raw( $places_url ) );
			
			$response		 = wp_remote_get( esc_url_raw( $url ) );
			
			$response_code	 = wp_remote_retrieve_response_code( $response );
			if ( $response_code == 200 ) {
				$msg	 = __( 'places-found', BPCHK_TEXT_DOMAIN );
				$places	 = json_decode( wp_remote_retrieve_body( $response ) );

				if ( !empty( $places->results ) ) {
					$places_html .= '<ul class="bpchk-places-fetched">';
					foreach ( $places->results as $place ) {
						$places_html .= '<li class="bpchk-single-place">';
						$places_html .= '<div class="place-icon">';
						$places_html .= '<img height="18px" width="18px" title="' . $place->name . '" src="' . $place->icon . '">';
						$places_html .= '</div>';
						$places_html .= '<div class="place-details">';
						$places_html .= '<b>' . $place->name . '</b>';
						$places_html .= '<div>' . $place->vicinity . '</div>';
						$places_html .= '</div>';
						$places_html .= '<div class="place-actions">';
						$places_html .= '<a href="javascript:void(0);" class="bpchk-select-place-to-checkin" data-place_reference="' . $place->reference . '" data-place_id="' . $place->place_id . '">' . __( 'Select', BPCHK_TEXT_DOMAIN ) . '</a>';
						$places_html .= '</div>';
						$places_html .= '</li>';
					}
					$places_html .= '</ul>';
					$places_html .= '<input type="checkbox" id="bpchk-add-as-place" checked />';
					$places_html .= '<label for="bpchk-add-as-place" id="bpchk-add-my-place-label">' . __( 'Add as my location', BPCHK_TEXT_DOMAIN ) . '</label>';
					$places_html .= '<div class="bpchk-single-location-added"></div>';
					$places_html .= '';
				}
			} else {
				$msg		 = __( 'places-not-found', BPCHK_TEXT_DOMAIN );
				$places_html .= '<p>' . __( 'Places not found !', BPCHK_TEXT_DOMAIN ) . '</p>';
			}
			$result = array(
				'message'	 => $msg,
				'html'		 => stripslashes( $places_html )
			);
			echo json_encode( $result );
			//wp_send_json_success( $result );
			die;
		}
	}

	/**
	 * Ajax served to save the temporary location
	 */
	public function bpchk_select_place_to_checkin() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'bpchk_select_place_to_checkin' ) {
			global $bp_checkins, $wpdb;
			$place_reference = sanitize_text_field( $_POST[ 'place_reference' ] );
			$place_id		 = sanitize_text_field( $_POST[ 'place_id' ] );
			$place_html		 = '';
			$options_tbl	 = $wpdb->prefix . "options";

			$parameters = array(
				'placeid'	 => $place_id,
				'key'		 => $bp_checkins->apikey
			);

			$place_detail_url	 = 'https://maps.googleapis.com/maps/api/place/details/json';
			$url				 = add_query_arg( $parameters, esc_url_raw( $place_detail_url ) );
			$response			 = wp_remote_get( esc_url_raw( $url ) );
			$response_code		 = wp_remote_retrieve_response_code( $response );
			if ( $response_code == 200 ) {
				$msg	 = __( 'place-details-found', BPCHK_TEXT_DOMAIN );
				$place	 = json_decode( wp_remote_retrieve_body( $response ) );

				$place_name	 = $place->result->name;
				$latitude	 = $place->result->geometry->location->lat;
				$longitude	 = $place->result->geometry->location->lng;

				$args = array(
					'place'				 => $place_name,
					'latitude'			 => $latitude,
					'longitude'			 => $longitude,
					'add_as_my_place'	 => sanitize_text_field( $_POST[ 'add_as_my_place' ] )
				);

				$href		 = 'http://maps.google.com/maps/place/' . $place_name . "/@$latitude,$longitude";
				$place_html	 .= "<div class='bpchk-checkin-temp-location'>-at <a title='" . $place_name . "' href='" . $href . "' target='_blank' id='bpchk-temp-location'>" . $place_name . "</a>";
				$place_html	 .= ' <a href="javascript:void(0);" id="bpchk-cancel-checkin" title="' . __( 'Click here to cancel your checkin.', BPCHK_TEXT_DOMAIN ) . '"><i class="fa fa-times"></i></a>';
				$place_html	 .= '</div>';
				$place_html	 .= '<div>';
				$place_html	 .= '<a class="button" href="javascript:void(0);" id="bpchk-show-places-panel">' . __( 'Show Locations', BPCHK_TEXT_DOMAIN ) . '</a>';
				// $place_html	 .= '<a href="javascript:void(0);" id="bpchk-hide-places-panel">' . __( 'Hide places', BPCHK_TEXT_DOMAIN ) . '</a>';
				$place_html	 .= '</div>';

				$qry	 = "SELECT `option_id`, `option_value` from $options_tbl where `option_name` = 'bpchk_temp_location'";
				$result	 = $wpdb->get_results( $qry );
				if ( empty( $result ) ) {
					//Insert the temp location in options table
					$wpdb->insert(
						$options_tbl, array(
							'option_name'	 => 'bpchk_temp_location',
							'option_value'	 => serialize( $args )
						)
					);
				} else {
					//Update the previously existing temp location in options table
					$option_id = $result[ 0 ]->option_id;
					$wpdb->update(
						$options_tbl, array( 'option_value' => serialize( $args ) ), array( 'option_id' => $option_id )
					);
				}
			} else {
				$msg		 = __( 'place-details-not-found', BPCHK_TEXT_DOMAIN );
				$place_html	 .= '<p>' . __( 'Place details not found !', BPCHK_TEXT_DOMAIN ) . '</p>';
			}

			$result = array(
				'message'	 => $msg,
				'html'		 => stripslashes( $place_html )
			);
			wp_send_json_success( $result );
			die;
		}
	}

	/**
	 * Ajax served to cancel the checkin
	 */
	public function bpchk_cancel_checkin() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'bpchk_cancel_checkin' ) {
			global $wpdb;
			$tbl	 = $wpdb->prefix . 'options';
			$wpdb->delete( $tbl, array( 'option_name' => 'bpchk_temp_location' ) );
			$result	 = array(
				'message' => __( 'Checkin cancelled !', BPCHK_TEXT_DOMAIN )
			);
			wp_send_json_success( $result );
			die;
		}
	}

}
