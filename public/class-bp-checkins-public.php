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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->bpchk_add_place();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name.'-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css' );
		wp_enqueue_style( $this->plugin_name.'-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-checkins-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( is_user_logged_in() ) {
			global $bp_checkins;
			wp_enqueue_script( $this->plugin_name-'google-places-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key='.$bp_checkins->apikey, array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );

			//Create the checkin html
			$checkin_html = '';
			$checkin_html .= '<div class="bp-checkins bp-checkin-panel">';
			if( $bp_checkins->checkin_by == 'autocomplete' ) {
				$checkin_html .= '<div class="checkin-by-autocomplete">';
				$checkin_html .= '<input type="text" id="bpchk-autocomplete-place" placeholder="'.__( 'Type in to checkin', BPCHK_TEXT_DOMAIN ).'" />';
				$checkin_html .= '<input type="hidden" id="bpchk-checkin-place-lat" />';
				$checkin_html .= '<input type="hidden" id="bpchk-checkin-place-lng" />';
				$checkin_html .= '<input type="checkbox" id="bpchk-add-as-place" checked />';
				$checkin_html .= '<label for="bpchk-add-as-place">'.__( 'Add as my place', BPCHK_TEXT_DOMAIN ).'</label>';
				$checkin_html .= '</div>';
			} else {
				$checkin_html .= '<div class="checkin-by-placetype">';
				$checkin_html .= '<p>'.__( 'Please Wait..', BPCHK_TEXT_DOMAIN ).'</p>';
				$checkin_html .= '</div>';
			}
			$checkin_html .= '</div>';
			$checkin_html .= '<span class="bpchk-allow-checkin"><i class="fa fa-map-marker" aria-hidden="true"></i></span>';

			wp_localize_script(
				$this->plugin_name,
				'bpchk_public_js_obj',
				array(
					'ajaxurl'		=>	admin_url( 'admin-ajax.php' ),
					'checkin_html'	=>	$checkin_html,
					'checkin_by'	=>	$bp_checkins->checkin_by
				)
			);
		}
	}

	/**
	 * Register a new tab in member's profile - Checkin
	 *
	 * @since    1.0.0
	 */
	public function bpchk_member_profile_checkin_tab() {
		$name = bp_get_displayed_user_username();
		$displayed_uid = bp_displayed_user_id();
		$parent_slug = 'checkin';
		$add_place_link = bp_core_get_userlink( $displayed_uid, false, true ).$parent_slug.'/add-place';
		$my_places_link = bp_core_get_userlink( $displayed_uid, false, true ).$parent_slug.'/my-places';

		bp_core_new_nav_item(
			array(
				'name' => __( 'Checkin', BPCHK_TEXT_DOMAIN ),
				'slug' => 'checkin',
				'screen_function' => array($this, 'bpchk_checkin_tab_function_to_show_screen'),
				'position' => 75,
				'default_subnav_slug' => 'my-places',
				'show_for_displayed_user' => true,
			)
		);
		if( $displayed_uid == get_current_user_id() ) {
			bp_core_new_subnav_item(
				array(
					'name' => __( 'Add Place', BPCHK_TEXT_DOMAIN ),
					'slug' => 'add-place',
					'parent_url' => bp_core_get_userlink( $displayed_uid, false, true ).$parent_slug.'/',
					'parent_slug' => $parent_slug,
					'screen_function' => array($this, 'bpchk_add_place_show_screen'),
					'position' => 200,
					'link' => $add_place_link,
				)
			);
		}
		bp_core_new_subnav_item(
			array(
				'name' => __( 'My Places', BPCHK_TEXT_DOMAIN ),
				'slug' => 'my-places',
				'parent_url' => bp_core_get_userlink( $displayed_uid, false, true ).$parent_slug.'/',
				'parent_slug' => $parent_slug,
				'screen_function' => array($this, 'bpchk_my_places_show_screen'),
				'position' => 100,
				'link' => $my_places_link,
			)
		);
	}

	/**
	 * Screen function for add place menu item
	 */
	function bpchk_add_place_show_screen() {
		add_action('bp_template_title', array($this, 'bpchk_add_place_tab_function_to_show_title'));
		add_action('bp_template_content', array($this, 'bpchk_add_place_tab_function_to_show_content'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
	}

	/**
	 * Add Place - Title
	 */
	function bpchk_add_place_tab_function_to_show_title() {
		_e( 'Add a place to visit', BPCHK_TEXT_DOMAIN );
	}

	/**
	 * Add Place - Content
	 */
	function bpchk_add_place_tab_function_to_show_content() {
		$file = BPCHK_PLUGIN_PATH.'public/templates/checkin/bp-checkins-add-place.php';
		if( file_exists( $file ) ) {
			include_once $file;
		}
	}

	/**
	 * Screen function for listing all my places in menu item
	 */
	function bpchk_my_places_show_screen() {
		add_action('bp_template_title', array($this, 'bpchk_my_places_tab_function_to_show_title'));
		add_action('bp_template_content', array($this, 'bpchk_my_places_tab_function_to_show_content'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
	}

	/**
	 * My Places - Title
	 */
	function bpchk_my_places_tab_function_to_show_title() {
		_e( 'My places', BPCHK_TEXT_DOMAIN );
	}

	/**
	 * My Places - Content
	 */
	function bpchk_my_places_tab_function_to_show_content() {
		$file = BPCHK_PLUGIN_PATH.'public/templates/checkin/bp-checkins-my-places.php';
		if( file_exists( $file ) ) {
			include_once $file;
		}
	}

	/**
	 * Add code to save the place to visit
	 */
	public function bpchk_add_place() {
		if( isset( $_POST['bpchk-save-place-to-visit'] ) && wp_verify_nonce( $_POST['bpchk-add-place-to-visit-nonce'], 'bpchk-add-place' ) ) {
			
			$place_title = sanitize_text_field( $_POST['bpchk-add-place-title'] );
			$place_visit_date = sanitize_text_field( $_POST['bpchk-add-place-visit-date'] );
			$place_latitude = sanitize_text_field( $_POST['bpchk-place-latitude'] );
			$place_longitude = sanitize_text_field( $_POST['bpchk-place-longitude'] );

			$place_get_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$place_latitude,$place_longitude&sensor=false";
			$response = wp_remote_get( $place_get_url );
			$response_code = wp_remote_retrieve_response_code( $response );
			if( $response_code == 200 ) {
				$jsondata = json_decode( wp_remote_retrieve_body( $response ), true );

				$address 						=	array();
				$address['latitude']			=	$place_latitude;
				$address['longitude']			=	$place_longitude;
				$address['country']				=	self::google_getCountry( $jsondata );
				$address['province']			=	self::google_getProvince( $jsondata );
				$address['city']				=	self::google_getCity( $jsondata );
				$address['street']				=	self::google_getStreet( $jsondata );
				$address['postal_code']			=	self::google_getPostalCode( $jsondata );
				$address['country_code']		=	self::google_getCountryCode( $jsondata );
				$address['formatted_address']	=	self::google_getAddress( $jsondata );
				$address['visit_date']			=	$place_visit_date;

				$args = array(
					'post_title' => $place_title,
					'post_type' => 'bpchk_places',
					'post_status' => 'publish'
				);
				$place_id = wp_insert_post( $args );
				update_post_meta( $place_id, 'place_details', $address );

				$redirection = sanitize_text_field( $_POST['bpchk-redirect'] );
				wp_safe_redirect( $redirection );
				exit;
			}
		}
	}

	/**
	 * Return country from json data
	 */
	public function google_getCountry( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "country", $jsondata["results"][0]["address_components"] );
	}

	/**
	 * Return province from json data
	 */
	public function google_getProvince( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "administrative_area_level_1", $jsondata["results"][0]["address_components"], true );
	}

	/**
	 * Return city from json data
	 */
	public function google_getCity( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "locality", $jsondata["results"][0]["address_components"] );
	}

	/**
	 * Return street from json data
	 */
	public function google_getStreet( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "street_number", $jsondata["results"][0]["address_components"] ) . ' ' . self::bpchk_find_long_name_given_type( "route", $jsondata["results"][0]["address_components"] );
	}

	/**
	 * Return postal code from json data
	 */
	public function google_getPostalCode( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "postal_code", $jsondata["results"][0]["address_components"] );
	}

	/**
	 * Return country code from json data
	 */
	public function google_getCountryCode( $jsondata ) {
		return self::bpchk_find_long_name_given_type( "country", $jsondata["results"][0]["address_components"], true );
	}

	/**
	 * Return formatted address from json data
	 */
	public function google_getAddress( $jsondata ) {
		return $jsondata["results"][0]["formatted_address"];
	}

	/**
	 * Searching in Google Geo json, return the long name given the type. 
	 * (If short_name is true, return short name)
	 */
	public function bpchk_find_long_name_given_type( $type, $array, $short_name = false ) {
		foreach( $array as $value ) {
			if ( in_array( $type, $value["types"] ) ) {
				if ( $short_name )
					return $value["short_name"];
				return $value["long_name"];
			}
		}
	}

	/**
	 * Ajax served to save the temporary location
	 */
	public function bpchk_save_temp_location() {
		if( isset( $_POST['action'] ) && $_POST['action'] == 'bpchk_save_temp_location' ) {
			$args = array(
				'place'					=> sanitize_text_field( $_POST['place'] ),
				'latitude'				=> sanitize_text_field( $_POST['latitude'] ),
				'longitude'				=> sanitize_text_field( $_POST['longitude'] ),
				'add_as_my_place'		=> sanitize_text_field( $_POST['add_as_my_place'] )
			);
			update_option( 'bpchk_temp_location', $args );
			$response = array( 'message' => 'temp-locaition-saved' );
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * Action performed to save the activity update to show the checkin
	 */
	public function bpchk_update_meta_on_post_update( $content, $user_id, $activity_id ) {
		global $wpdb;
		$tbl	 = $wpdb->prefix . "options";
		$qry	 = "SELECT `option_value` from $tbl where `option_name` = 'bpchk_temp_location'";
		$result	 = $wpdb->get_results( $qry );
		if ( !empty( $result ) ) {
			$place_details		= unserialize( $result[0]->option_value );
			$place 				= $place_details['place'];
			$longitude			= $place_details['longitude'];
			$latitude			= $place_details['latitude'];
			$add_as_my_place	= $place_details['add_as_my_place'];
			
			$location_html		= ' -at <a class=checkin-loc href="http://maps.google.com/maps/place/' . $place . '/@' . $latitude . ',' . $longitude . '" target="_blank" title="' . $place . '">' . $place . '</a>';

			//Get the content of the activity
			$activity_tbl	 	= $wpdb->prefix . 'bp_activity';
			$activity_meta_tbl	= $wpdb->prefix . 'bp_activity_meta';
			
			$activity_qry	 	= "SELECT * from $activity_tbl where `id` = $activity_id";
			$activity_result 	= $wpdb->get_results( $activity_qry );

			$pos = strpos( $activity_result[0]->content, '-at <a class="checkin-loc"' );
			//Update the activity content to post the checkin along with the post update
			if ( $pos === false ) {
				$wpdb->update(
					$activity_tbl,
					array( 'content' => $activity_result[0]->content . $location_html ),
					array( 'id' => $activity_id ),
					array( '%s' ),
					array( '%d' )
				);

				//Update the location details in activity meta
				$wpdb->insert(
					$activity_meta_tbl,
					array(
						'activity_id' => $activity_id,
						'meta_key' => 'bpchk_place_details',
						'meta_value' => serialize( $place_details ),
					)
				);
			}

			if( $add_as_my_place == 'yes' ) {
				$place_get_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
				$response = wp_remote_get( $place_get_url );
				$response_code = wp_remote_retrieve_response_code( $response );
				if( $response_code == 200 ) {
					$jsondata = json_decode( wp_remote_retrieve_body( $response ), true );

					$place_visit_date				=	date( "Y-m-d" );

					$address 						=	array();
					$address['latitude']			=	$latitude;
					$address['longitude']			=	$longitude;
					$address['country']				=	self::google_getCountry( $jsondata );
					$address['province']			=	self::google_getProvince( $jsondata );
					$address['city']				=	self::google_getCity( $jsondata );
					$address['street']				=	self::google_getStreet( $jsondata );
					$address['postal_code']			=	self::google_getPostalCode( $jsondata );
					$address['country_code']		=	self::google_getCountryCode( $jsondata );
					$address['formatted_address']	=	self::google_getAddress( $jsondata );
					$address['visit_date']			=	$place_visit_date;

					$args = array(
						'post_title' => $place,
						'post_type' => 'bpchk_places',
						'post_status' => 'publish'
					);
					$place_id = wp_insert_post( $args );
					update_post_meta( $place_id, 'place_details', $address );
				}
			}
			/**
			 * Delete the temp location after posting update,
			 * so that the same place doesn't gets posted
			 * when no checkin is done
			 */
			$wpdb->delete( $tbl, array( 'option_name' => 'bpchk_temp_location' ) );

		}
	}

	/**
	 * Show mep on checkin activities
	 */
	public function bpchk_show_google_map_in_checkin_activity() {
		$activity_id 		= bp_get_activity_id();
		global $wpdb, $bp_checkins;
		$activity_tbl	 	= $wpdb->prefix . 'bp_activity';
		$activity_meta_tbl	= $wpdb->prefix . 'bp_activity_meta';

		$qry				= "SELECT `meta_value` from `$activity_meta_tbl` where `activity_id` = $activity_id AND `meta_key` = 'bpchk_place_details'";
		$result	 			= $wpdb->get_results( $qry );
		if( !empty( $result ) ) {
			$place = unserialize( $result[0]->meta_value );
			$apikey = $bp_checkins->apikey;
			$formatted_address = $place['place'];
			$map_url = 'https://www.google.com/maps/embed/v1/place?key='.$apikey.'&q='.$formatted_address;
			echo '<div id="bpchk-place-map"><iframe frameborder="0" style="border:0" src="'.$map_url.'" allowfullscreen></iframe></div>';
		}
	}

	/**
	 * Ajax served to fetch the places on page load
	 */
	public function bpchk_fetch_places() {
		if( isset( $_POST['action'] ) && $_POST['action'] == 'bpchk_fetch_places' ) {
			global $bp_checkins;
			$apikey 		= $bp_checkins->apikey;
			$range 			= $bp_checkins->google_places_range;
			$placetypes		= implode( ',', $bp_checkins->place_types );
			$latitude 		= sanitize_text_field( $_POST['latitude'] );
			$longitude 		= sanitize_text_field( $_POST['longitude'] );
			$places_html	= '';

			$parameters = array(
				'location' => "$latitude,$longitude",
				'radius' => $range,
				'key' => $apikey,
				'type' => $placetypes,
				'heading' => false,
				'title' => false
			);
			$places_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
			$url = add_query_arg( $parameters, esc_url_raw( $places_url ) );
			$response = wp_remote_get( esc_url_raw( $url ) );
			$response_code = wp_remote_retrieve_response_code( $response );
			if( $response_code == 200 ) {
				$msg = __( 'places-found', BPCHK_TEXT_DOMAIN );
				$places = json_decode( wp_remote_retrieve_body( $response ) );

				if( !empty( $places->results ) ) {
					$places_html .= '<ul class="bpchk-places-fetched">';
					foreach( $places->results as $place ) {
						$places_html .= '<li class="bpchk-single-place">';
						$places_html .= '<div class="place-icon">';
						$places_html .= '<img height="25px" width="25px" title="'.$place->name.'" src="'.$place->icon.'">';
						$places_html .= '</div>';
						$places_html .= '<div class="place-details">';
						$places_html .= '<h6>'.$place->name.'</h6>';
						$places_html .= '<span>'.$place->vicinity.'</span>';
						$places_html .= '</div>';
						$places_html .= '<div class="place-actions">';
						$places_html .= '<a href="javascript:void(0);" class="bpchk-select-place-to-checkin" data-place_reference="'.$place->reference.'" data-place_id="'.$place->place_id.'">'.__( 'Select this place', BPCHK_TEXT_DOMAIN ).'</a>';
						$places_html .= '</div>';
						$places_html .= '</li>';
					}
					$places_html .= '</ul>';
					$places_html .= '<input type="checkbox" id="bpchk-add-as-place" checked />';
					$places_html .= '<label for="bpchk-add-as-place" id="bpchk-add-my-place-label">'.__( 'Add as my place', BPCHK_TEXT_DOMAIN ).'</label>';
					$places_html .= '<div class="bpchk-single-location-added"></div>';
					$places_html .= '';
				}
				
			} else {
				$msg = __( 'places-not-found', BPCHK_TEXT_DOMAIN );
				$places_html .= '<p>'.__( 'Places not found !', BPCHK_TEXT_DOMAIN ).'</p>';
			}


			$result = array(
				'message' => $msg,
				'html' => stripslashes( $places_html )
			);
			wp_send_json_success( $result );
			die;
		}
	}

	/**
	 * Ajax served to save the temporary location
	 */
	public function bpchk_select_place_to_checkin() {
		if( isset( $_POST['action'] ) && $_POST['action'] == 'bpchk_select_place_to_checkin' ) {
			global $bp_checkins, $wpdb;
			$place_reference 	= sanitize_text_field( $_POST['place_reference'] );
			$place_id 			= sanitize_text_field( $_POST['place_id'] );
			$place_html 		= '';
			$options_tbl 		= $wpdb->prefix."options";

			$parameters = array(
				'placeid'		=> $place_id,
				'key' 			=> $bp_checkins->apikey
			);

			$place_detail_url = 'https://maps.googleapis.com/maps/api/place/details/json';
			$url = add_query_arg( $parameters, esc_url_raw( $place_detail_url ) );
			$response = wp_remote_get( esc_url_raw( $url ) );
			$response_code = wp_remote_retrieve_response_code( $response );
			if( $response_code == 200 ) {
				$msg = __( 'place-details-found', BPCHK_TEXT_DOMAIN );
				$place = json_decode( wp_remote_retrieve_body( $response ) );

				$place_name = $place->result->name;
				$latitude = $place->result->geometry->location->lat;
				$longitude = $place->result->geometry->location->lng;

				$args = array(
					'place'					=> $place_name,
					'latitude'				=> $latitude,
					'longitude'				=> $longitude,
					'add_as_my_place'		=> sanitize_text_field( $_POST['add_as_my_place'] )
				);

				$href = 'http://maps.google.com/maps/place/'.$place_name."/@$latitude,$longitude";
				$place_html .= "<div class='bpchk-checkin-temp-location'>-at <a title='".$place_name."' href='".$href."' target='_blank' id='bpchk-temp-location'>".$place_name."</a>";
				$place_html .= ' <a href="javascript:void(0);" id="bpchk-cancel-checkin" title="'.__( 'Click here to cancel your checkin.', BPCHK_TEXT_DOMAIN ).'"><i class="fa fa-times"></i></a>';
				$place_html .= '</div>';
				$place_html .= '<div>';
				$place_html .= '<a href="javascript:void(0);" id="bpchk-show-places-panel">'.__( 'Show places', BPCHK_TEXT_DOMAIN ).'</a>';
				$place_html .= '<a href="javascript:void(0);" id="bpchk-hide-places-panel">'.__( 'Hide places', BPCHK_TEXT_DOMAIN ).'</a>';
				$place_html .= '</div>';

				$qry = "SELECT `option_id`, `option_value` from $options_tbl where `option_name` = 'bpchk_temp_location'";
				$result = $wpdb->get_results( $qry );
				 if( empty( $result ) ){
				 	//Insert the temp location in options table
				 	$wpdb->insert( 
						$options_tbl, 
						array( 
							'option_name' => 'bpchk_temp_location', 
							'option_value' => serialize( $args )
						) 
					);
				 } else {
				 	//Update the previously existing temp location in options table
				 	$option_id = $result[0]->option_id;
					$wpdb->update( 
						$options_tbl, 
						array( 'option_value' => serialize( $args ) ), 
						array( 'option_id' => $option_id )
					);
				 }
			} else {
				$msg = __( 'place-details-not-found', BPCHK_TEXT_DOMAIN );
				$place_html .= '<p>'.__( 'Place details not found !', BPCHK_TEXT_DOMAIN ).'</p>';
			}

			$result = array(
				'message' => $msg,
				'html' => stripslashes( $place_html )
			);
			wp_send_json_success( $result );
			die;
		}
	}

	/**
	 * Ajax served to cancel the checkin
	 */
	public function bpchk_cancel_checkin() {
		if( isset( $_POST['action'] ) && $_POST['action'] == 'bpchk_cancel_checkin' ) {
			global $wpdb;
			$tbl = $wpdb->prefix.'options';
			$wpdb->delete( $tbl, array( 'option_name' => 'bpchk_temp_location' ) );
			$result = array(
				'message' => __( 'Checkin cancelled !', BPCHK_TEXT_DOMAIN )
			);
			wp_send_json_success( $result );
			die;
		}
	}
}
