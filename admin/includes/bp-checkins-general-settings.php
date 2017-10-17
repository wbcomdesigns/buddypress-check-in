<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

global $bp_checkins;
$saved_range = $bp_checkins->google_places_range;

$verify_btn_style = 'display: none;';
if( !empty( $bp_checkins->apikey ) ) {
	$verify_btn_style = '';
}

$placetype_settings_style = 'display: none;';
if( $bp_checkins->checkin_by == 'placetype' ) {
	$placetype_settings_style = '';
}

$place_types = array(
	'Accounting', 'Airport', 'Amusement Park', 'Aquarium', 'Art Gallery', 'ATM',
	'Bakery', 'Bank', 'Bar', 'Beauty Salon', 'Bicycle Store', 'Book Store',
	'Bowling Alley', 'Bus Station', 'Cafe', 'Campground', 'Car Dealer', 'Car Rental',
	'Car Repair', 'Car Wash', 'Casino', 'Cemetery', 'Church', 'City Hall',
	'Clothing Store', 'Convenience Store', 'Courthouse', 'Dentist', 'Department Store', 'Doctor',
	'Electrician', 'Electronics Store', 'Embassy', 'Fire Station', 'Florist', 'Funeral Home',
	'Furniture Home', 'Gas Station', 'Gym', 'Hair Care', 'Hardware Store', 'Hindu Temple',
	'Home Goods Store', 'Hospital', 'Insurance Agency', 'Jewelery Store', 'Laundry', 'Lawyer',
	'Library', 'Liquor Store', 'Local Government Office', 'Locksmith', 'Lodging', 'Meal Delivery',
	'Meal Takeaway', 'Mosque', 'Movie Rental', 'Movie Theatre', 'Moving Company', 'Museum',
	'Night Club', 'Painter', 'Park', 'Parking', 'Pet Store', 'Pharmacy',
	'Physiotherapist', 'Plumber', 'Police', 'Post Office', 'Real Estate Agency', 'Restaurant',
	'Roofing Contractor', 'RV Park', 'School', 'Shoe Store', 'Shopping Mall', 'SPA',
	'Stadium', 'Storage', 'Store', 'Subway Station', 'Synagogue', 'Taxi Stand',
	'Train Station', 'Transit Station', 'Travel Agency', 'University', 'Veterinary Care', 'Zoo'
);
?>
<form method="POST" action="">
	<table class="form-table bpchk-admin-page-table">
		<tbody>
			<!-- API Key -->
			<tr>
				<th scope="row"><label for="api-key"><?php _e( 'API Key', BPCHK_TEXT_DOMAIN );?></label></th>
				<td>
					<input class="regular-text" type="text" value="<?php echo $bp_checkins->apikey;?>" name="bpchk-api-key" id="bpchk-api-key" placeholder="<?php _e( 'API Key', BPCHK_TEXT_DOMAIN );?>" required>
					<button type="button" class="button button-secondary" style="<?php echo $verify_btn_style;?>" id="bpchk-verify-apikey"><?php _e( 'Verify', BPCHK_TEXT_DOMAIN );?></button>
					<p class="description"><?php _e( "Due to changes in Google Maps API it's required to use an API key for the BuddyPress Check-ins plugin to work properly. You can get the API key", BPCHK_TEXT_DOMAIN );?>&nbsp;<a target="blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key"><?php _e('here.',BPCHK_TEXT_DOMAIN); ?></a></p>
				</td>
			</tr>

			<?php if( $bp_checkins->apikey ) {?>
				<!-- Checkin By - autocomplete or placetype -->
				<tr>
					<th scope="row"><label for="checkin-by"><?php _e( 'Checkin by', BPCHK_TEXT_DOMAIN );?></label></th>
					<td>
						<p>
							<input <?php echo ( $bp_checkins->checkin_by == 'autocomplete' ) ? 'checked' : '';?> required type="radio" value="autocomplete" name="bpchk-checkin-by" class="bpchk-checkin-by" id="bpchk-checkin-by-autocomplete">
							<label for="bpchk-checkin-by-autocomplete"><?php _e( 'Location Autocomplete', BPCHK_TEXT_DOMAIN );?></label>
						</p>
						<p>
							<input <?php echo ( $bp_checkins->checkin_by == 'placetype' ) ? 'checked' : '';?> required type="radio" value="placetype" name="bpchk-checkin-by" class="bpchk-checkin-by" id="bpchk-checkin-by-placetype">
							<label for="bpchk-checkin-by-placetype"><?php _e( 'Place Types', BPCHK_TEXT_DOMAIN );?></label>
						</p>
						<p class="description"><?php _e( 'This setting will help the users checkin by autocomplete or placetype google features.', BPCHK_TEXT_DOMAIN );?></p>
					</td>
				</tr>
			<?php }?>

			<!-- Settings for place types -->
			<tr style="<?php echo $placetype_settings_style;?>" class="bpchk-placetype-settings-row">
				<th scope="row"><?php _e( 'Range', BPCHK_TEXT_DOMAIN );?></th>
				<td>
					<input type="hidden" value="<?php echo $saved_range;?>" id="hidden_range" />
					<input value="<?php echo $saved_range;?>" id="bpchk-google-places-range" type="range" name="bpchk-google-places-range" min="1" max="10">
					<span id="range_disp"><?php if ( $saved_range ) echo "$saved_range kms.";?></span>
					<p class="description"><?php _e( 'This will set the range for fetching the places while checkin.', BPCHK_TEXT_DOMAIN );?></p>
				</td>
			</tr>

			<!-- Settings for place types -->
			<tr style="<?php echo $placetype_settings_style;?>" class="bpchk-placetype-settings-row">
				<th scope="row"><?php _e( 'Place Types', BPCHK_TEXT_DOMAIN );?></th>
				<td>
					<p class="bpchk-selection-tags">
						<a href="javascript:void(0);" id="bpchk-select-all-place-types"><?php _e( 'Select All', BPCHK_TEXT_DOMAIN );?></a> / 
						<a href="javascript:void(0);" id="bpchk-unselect-all-place-types"><?php _e( 'Unselect All', BPCHK_TEXT_DOMAIN );?></a>
					</p>
					<select name="bpchk-google-place-types[]" id="bpchk-pre-place-types" multiple>
						<?php foreach( $place_types as $place_type ) {?>
							<?php $placetype_slug = str_replace( ' ', '_', strtolower( $place_type ) );?>
							<option value="<?php echo $placetype_slug;?>" <?php echo ( !empty( $bp_checkins->place_types ) && in_array( $placetype_slug, $bp_checkins->place_types ) ) ? 'selected' : '';?>><?php echo $place_type;?></option>
						<?php }?>
					</select>
					<p class="description"><?php _e( 'This will help in fetching the place types, that will be selected here.', BPCHK_TEXT_DOMAIN );?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field( 'bpchk-general', 'bpchk-general-settings-nonce'); ?>
		<input type="submit" name="bpchk-submit-general-settings" class="button button-primary" value="<?php _e( 'Save Changes', BPCHK_TEXT_DOMAIN );?>">
	</p>
</form>