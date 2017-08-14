<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//Include the checked in place while posting update
add_action( 'bp_activity_posted_update', 'bpchk_update_meta_on_post_update', 10, 3 );

function bpchk_update_meta_on_post_update( $content, $user_id, $activity_id ) {

	global $wpdb;
	$tbl	 = $wpdb->prefix . "options";
	$qry	 = "SELECT `option_value` from $tbl where `option_name` = 'temp_location'";
	$result	 = $wpdb->get_results( $qry );
	if ( !empty( $result ) ) {
		$place = $result[ 0 ]->option_value;
		bp_activity_update_meta( $activity_id, 'checkedin_location', $place );

		//Update content on post update
		$unserialized_place	 = unserialize( $place );
		$location			 = $unserialized_place[ 'place_name' ];
		$lng				 = $unserialized_place[ 'lng' ];
		$lat				 = $unserialized_place[ 'lat' ];
		$locname			 = preg_replace( '/\s+/', '+', $location );
		$location_html		 = '-at <a class=checkin-loc href="http://maps.google.com/maps/place/' . $locname . '/@' . $lat . ',' . $lng . '" target="_blank" title="' . $location . '">' . $location . '</a>';

		//Get the content of the activity
		$activity_tbl	 = $wpdb->prefix . "bp_activity";
		$activity_qry	 = "SELECT * from $activity_tbl where `id` = $activity_id";
		$activity_result = $wpdb->get_results( $activity_qry );

		$pos = strpos( $activity_result[ 0 ]->content, '-at <a class="checkin-loc"' );

		if ( $pos === false ) {
			$wpdb->update(
			$activity_tbl, array( 'content' => $activity_result[ 0 ]->content . $location_html ), array( 'id' => $activity_id ), array( '%s' ), array( '%d' )
			);
		}

		//Delete the temp location in `options` table
		$wpdb->delete( $tbl, array( 'option_name' => 'temp_location' ) );
		?>
		<script>
			jQuery( document ).ready( function () {
				jQuery( '.checkin-panel' ).hide();
				jQuery( '#bpchk-close' ).hide();
			} );
		</script>
		<?php
	}

	//if autocomplete is selected from admin then add the place to places custom post type
	$add_location_chk	 = sanitize_text_field( $_POST[ 'add_location' ] );
	$autocomplete		 = sanitize_text_field( $_POST[ 'auto_complete' ] );
	if ( $add_location_chk == 1 && $autocomplete != '' ) {
		$auto_complete_location	 = explode( ",", $autocomplete );
		$post_title				 = $auto_complete_location[ 0 ];
		$post_name				 = strtolower( str_replace( " ", "-", $auto_complete_location[ 0 ] ) );
		$post_content			 = $autocomplete;
		$post_id				 = wp_insert_post( array(
			'post_type'		 => 'place',
			'post_title'	 => $post_title,
			'post_content'	 => $post_content,
			'post_name'		 => $post_name,
			'post_status'	 => 'publish',
			'comment_status' => 'closed',
			'ping_status'	 => 'closed',
		) );
		$lat					 = sanitize_text_field( $_POST[ 'lat' ] );
		$lon					 = sanitize_text_field( $_POST[ 'lon' ] );
		$city					 = sanitize_text_field( $_POST[ 'city' ] );
		$state					 = sanitize_text_field( $_POST[ 'state' ] );
		$state_long				 = sanitize_text_field( $_POST[ 'state_long' ] );
		$country				 = sanitize_text_field( $_POST[ 'country' ] );
		$country_long			 = sanitize_text_field( $_POST[ 'country_long' ] );
		$address				 = sanitize_text_field( $_POST[ 'formatted_address' ] );
		$zip_code				 = sanitize_text_field( $_POST[ 'zip_code' ] );
		$data					 = array(
			'post_id'			 => $post_id,
			'feature'			 => 0,
			'post_status'		 => 'publish',
			'post_type'			 => 'place',
			'post_title'		 => $post_title,
			'lat'				 => $lat,
			'long'				 => $lon,
			'street_number'		 => '',
			'street_name'		 => '',
			'street'			 => '',
			'apt'				 => '',
			'phone'				 => '',
			'fax'				 => '',
			'email'				 => '',
			'website'			 => '',
			'city'				 => $city,
			'state'				 => $state,
			'state_long'		 => $state_long,
			'zipcode'			 => $zip_code,
			'country'			 => $country,
			'country_long'		 => $country_long,
			'address'			 => $address,
			'formatted_address'	 => $address,
			'map_icon'			 => '_default.png'
		);
		$table_name				 = $wpdb->prefix . "places_locator";
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			//table not in database
		} else {
			$wpdb->insert( $table_name, $data );
		}
	} //END OF IF
	?>
	<script>
		jQuery( '.chk_in_part' ).toggle();
		jQuery( '#map' ).hide();
		jQuery( '#add_location_lbl' ).hide();
	</script>
	<?php
}

//show either google "autocomplete box" or "place types auto select box" based on the selection in the admin section
add_action( 'wp_footer', 'bpchk_show_locations_and_google_map_in_activity', 10 );

function bpchk_show_locations_and_google_map_in_activity() {
	$bpchk_settings = get_option( 'bpchk_settings', true );
	if ( $bpchk_settings != '' ) {
		$saved_api		 = $bpchk_settings[ 'api_key' ];
		$saved_range	 = $bpchk_settings[ 'range' ];
		$selected_option = $bpchk_settings[ 'selected_option' ];
		if ( $saved_range ) {
			$saved_range = $saved_range / 1000;
		}
		$saved_place_types = $bpchk_settings[ 'place_types' ];
	}
	//if auto_complete option is selected in admin section
	if ( $selected_option == 'auto_complete' ) {
		wp_enqueue_script( 'bpchk-js-map-auto-complete', 'http://maps.googleapis.com/maps/api/js?key=' . $saved_api . '&libraries=places' );
		wp_enqueue_script( 'bpchk-js-auto-complete', BPCHK_PLUGIN_URL . 'assets/js/bpchk-auto-complete.js', array( 'jquery' ) );
	}
	//if place_types option is selected in admin section
	else if ( $selected_option == 'place_types' ) {
		wp_enqueue_script( 'bpchk-js-map-place-types', 'http://maps.googleapis.com/maps/api/js?key=' . $saved_api . '&libraries=places' );
		wp_enqueue_script( 'bpchk-js-place-types', BPCHK_PLUGIN_URL . 'assets/js/bpchk-place-types.js', array( 'jquery' ) );
	}
}

//show google map under activity posts if any post have place_types
add_action( 'bp_activity_entry_content', 'bpchk_show_google_map_in_activity' );

function bpchk_show_google_map_in_activity() {
	$url = 'http://' . $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'REQUEST_URI' ];
	if ( strpos( $url, 'members' ) !== false || strpos( $url, 'activity' ) !== false ) {
		?>
																																<!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAaB5dGHZoDvmFAlcJoN-JmtoCB-wiAIzM&libraries=places&sensor=false"></script>-->
		<?php
	}
	$body	 = bp_get_activity_content_body();
	$match	 = 'http://maps.google.com/maps';
	if ( strpos( $body, $match ) !== false ) {
		preg_match_all( '/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $body, $result );
		print_r( $result );
		if ( !empty( $result ) ) {
			$map_url	 = $result[ 2 ][ 0 ];
			$map_arr	 = explode( "@", $map_url );
			$last_args	 = $map_arr[ 1 ];
			$lat_lon_arr = explode( ",", $last_args );
			$lat		 = $lat_lon_arr[ 0 ];
			$lon		 = $lat_lon_arr[ 1 ];
			?>
			<div id="map_<?php echo $lat; ?>_<?php echo $lon; ?>" style="width:100%; height:200px">
				<div id="map_canvas_<?php echo $lat; ?>_<?php echo $lon; ?>" style="width:100%; height:200px"></div>
				<div id="crosshair_<?php echo $lat; ?>_<?php echo $lon; ?>"></div>
			</div>
			<script type="text/javascript">
				var map;
				var geocoder;
				var centerChangedLast;
				var reverseGeocodedLast;
				var currentReverseGeocodeResponse;
				function initialize()
				{
					var latlng = new google.maps.LatLng( '<?php echo $lat; ?>', '<?php echo $lon; ?>' );
					var myOptions = { zoom: 17, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP };
					var map = new google.maps.Map( document.getElementById( "map_canvas_<?php echo $lat; ?>_<?php echo $lon; ?>" ), myOptions );
					var geocoder = new google.maps.Geocoder();
					var marker = new google.maps.Marker( { position: latlng, map: map, title: "Hello World!" } );
				}
				initialize();
			</script>
			<?php
		}
	}
}
