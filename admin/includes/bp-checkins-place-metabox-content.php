<?php 
global $post, $bp_checkins;
$apikey = $bp_checkins->apikey;
$place_id = $post->ID;
$place_details = get_post_meta( $place_id, 'place_details', true );

$latitude 			= isset( $place_details['latitude'] ) ? $place_details['latitude'] : '';
$longitude 			= isset( $place_details['longitude'] ) ? $place_details['longitude'] : '';
$country 			= isset( $place_details['country'] ) ? $place_details['country'] : '';
$province 			= isset( $place_details['province'] ) ? $place_details['province'] : '';
$city 				= isset( $place_details['city'] ) ? $place_details['city'] : '';
$street 			= isset( $place_details['street'] ) ? $place_details['street'] : '';
$postal_code		= isset( $place_details['postal_code'] ) ? $place_details['postal_code'] : '';
$country_code		= isset( $place_details['country_code'] ) ? $place_details['country_code'] : '';
$formatted_address	= isset( $place_details['formatted_address'] ) ? $place_details['formatted_address'] : '';

$map_url = 'https://www.google.com/maps/embed/v1/place?key='.$apikey.'&q='.$formatted_address;
?>
<div class="bpchk-place-content">
	<div class="bpchk-place-map">
		<p><?php echo __( 'Address: ', BPCHK_TEXT_DOMAIN ).$formatted_address;?></p>
		<iframe height="400px" frameborder="0" style="border:0" src="<?php echo $map_url;?>" allowfullscreen></iframe>
	</div>
	<div class="bpchk-place-details">
		<table>
			<!-- LATITUDE & LONGITUDE -->
			<tr>
				<th><label for="bpchk-place-latitude"><?php _e( 'Latitude', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $latitude;?>" readonly type="text" id="bpchk-place-latitude" placeholder="<?php _e( 'Latitude', BPCHK_TEXT_DOMAIN );?>"></td>

				<th><label for="bpchk-place-longitude"><?php _e( 'Longitude', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $longitude;?>" readonly type="text" id="bpchk-place-longitude" placeholder="<?php _e( 'Longitude', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>

			<!-- STREET -->
			<tr>
				<th><label for="bpchk-place-street"><?php _e( 'Street', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $street;?>" readonly type="text" id="bpchk-place-street" placeholder="<?php _e( 'Street', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>

			<!-- CITY -->
			<tr>
				<th><label for="bpchk-place-city"><?php _e( 'City', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $city;?>" readonly type="text" id="bpchk-place-city" placeholder="<?php _e( 'City', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>

			<!-- STATE -->
			<tr>
				<th><label for="bpchk-place-state"><?php _e( 'State', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $province;?>" readonly type="text" id="bpchk-place-state" placeholder="<?php _e( 'State', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>

			<!-- ZIPCODE -->
			<tr>
				<th><label for="bpchk-place-zipcode"><?php _e( 'Zipcode', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $postal_code;?>" readonly type="text" id="bpchk-place-zipcode" placeholder="<?php _e( 'Zipcode', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>

			<!-- COUNTRY -->
			<tr>
				<th><label for="bpchk-place-country"><?php _e( 'Country', BPCHK_TEXT_DOMAIN );?></label></th>
				<td><input value="<?php echo $country;?>" readonly type="text" id="bpchk-place-country" placeholder="<?php _e( 'Country', BPCHK_TEXT_DOMAIN );?>"></td>
			</tr>
		</table>
	</div>
</div>