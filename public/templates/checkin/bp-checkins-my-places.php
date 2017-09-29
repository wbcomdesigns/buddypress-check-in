<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly
$displayed_user_id = bp_displayed_user_id();
$args = array(
	'post_type'			=>	'bpchk-places',
	'post_status'		=>	'publish',
	'posts_per_page'	=>	-1,
	'author'			=>	$displayed_user_id,
	'order'				=>	'DESC',
	'orderby'			=>	'date'
);
$places = get_posts( $args );
if( !empty( $places ) ) {
	?>
	<div class="bpchk-myplace-admin-settings-block">
		<div id="bpchk-myplace-settings-tbl">
			<?php foreach( $places as $place ) {?>
				<?php 
				global $bp_checkins;
				$apikey = $bp_checkins->apikey;
				$place_id = $place->ID;
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
				<div class="bpchk-myplace-admin-row">
					<div>
						<button class="bpchk-myplace-accordion"><?php echo $place->post_title;?></button>
						<div class="panel">
							<div class="bpchk-place-content">
								<div class="bpchk-place-map">
									<p><?php echo __( 'Address: ', BPCHK_TEXT_DOMAIN ).$formatted_address;?></p>
									<iframe frameborder="0" style="border:0" src="<?php echo $map_url;?>" allowfullscreen></iframe>
								</div>
								<div class="bpchk-place-details">
									<table class="bpchk-my-place-details">
										<!-- LATITUDE & LONGITUDE -->
										<tr>
											<th><label for="bpchk-place-latitude"><?php _e( 'Latitude', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $latitude;?></td>

											<th><label for="bpchk-place-longitude"><?php _e( 'Longitude', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $longitude;?></td>
										</tr>

										<!-- STREET & CITY -->
										<tr>
											<th><label for="bpchk-place-street"><?php _e( 'Street', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $street;?></td>

											<th><label for="bpchk-place-city"><?php _e( 'City', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $city;?></td>
										</tr>

										<!-- STATE & ZIPCODE -->
										<tr>
											<th><label for="bpchk-place-state"><?php _e( 'State', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $province;?></td>

											<th><label for="bpchk-place-zipcode"><?php _e( 'Zipcode', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $postal_code;?></td>
										</tr>

										<!-- COUNTRY -->
										<tr>
											<th><label for="bpchk-place-country"><?php _e( 'Country', BPCHK_TEXT_DOMAIN );?></label></th>
											<td><?php echo $country;?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?php } else {?>
	<div id="message" class="info">
		<p><?php _e( 'Sorry, no saved places found.', BPCHK_TEXT_DOMAIN );?></p>
	</div>
<?php }?>