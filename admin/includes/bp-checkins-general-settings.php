<?php
/**
 * BuddyPress Check-ins general setting tab file.
 *
 * @package Bp_Checkins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $bp_checkins;
$saved_range = $bp_checkins->google_places_range;

$verify_btn_style = 'display: none;';
if ( ! empty( $bp_checkins->apikey ) ) {
	$verify_btn_style = '';
}

$placetype_settings_style = 'display: none;';
if ( 'placetype' === $bp_checkins->checkin_by ) {
	$placetype_settings_style = '';
}
$place_types = array(
	'Accounting',
	'Airport',
	'Amusement Park',
	'Aquarium',
	'Art Gallery',
	'ATM',
	'Bakery',
	'Bank',
	'Bar',
	'Beauty Salon',
	'Bicycle Store',
	'Book Store',
	'Bowling Alley',
	'Bus Station',
	'Cafe',
	'Campground',
	'Car Dealer',
	'Car Rental',
	'Car Repair',
	'Car Wash',
	'Casino',
	'Cemetery',
	'Church',
	'City Hall',
	'Clothing Store',
	'Convenience Store',
	'Courthouse',
	'Dentist',
	'Department Store',
	'Doctor',
	'Electrician',
	'Electronics Store',
	'Embassy',
	'Fire Station',
	'Florist',
	'Funeral Home',
	'Furniture Home',
	'Gas Station',
	'Gym',
	'Hair Care',
	'Hardware Store',
	'Hindu Temple',
	'Home Goods Store',
	'Hospital',
	'Insurance Agency',
	'Jewelery Store',
	'Laundry',
	'Lawyer',
	'Library',
	'Liquor Store',
	'Local Government Office',
	'Locksmith',
	'Lodging',
	'Meal Delivery',
	'Meal Takeaway',
	'Mosque',
	'Movie Rental',
	'Movie Theatre',
	'Moving Company',
	'Museum',
	'Night Club',
	'Painter',
	'Park',
	'Parking',
	'Pet Store',
	'Pharmacy',
	'Physiotherapist',
	'Plumber',
	'Police',
	'Post Office',
	'Real Estate Agency',
	'Restaurant',
	'Roofing Contractor',
	'RV Park',
	'School',
	'Shoe Store',
	'Shopping Mall',
	'SPA',
	'Stadium',
	'Storage',
	'Store',
	'Subway Station',
	'Synagogue',
	'Taxi Stand',
	'Train Station',
	'Transit Station',
	'Travel Agency',
	'University',
	'Veterinary Care',
	'Zoo',
);
?>

<table class="form-table bpchk-admin-page-table">
	<tbody>
		<!-- API Key -->
		<tr>
			<th scope="row"><label for="api-key"><?php esc_html_e( 'API Key', 'bp-checkins' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" value="<?php echo esc_attr( $bp_checkins->apikey ); ?>" name="bpchk-api-key" id="bpchk-api-key" placeholder="<?php esc_html_e( 'API Key', 'bp-checkins' ); ?>" required>
				<button type="button" class="button button-secondary" style="<?php echo esc_attr( $verify_btn_style ); ?>" id="bpchk-verify-apikey"><?php esc_html_e( 'Verify', 'bp-checkins' ); ?></button>
				<p class="description"><?php esc_html_e( "Due to changes in Google Maps API it's required to use an API key for the BuddyPress Check-ins plugin to work properly. You can get the API key", 'bp-checkins' ); ?>&nbsp;<a target="blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key"><?php esc_html_e( 'here.', 'bp-checkins' ); ?></a>&nbsp;
					<a href="javascript:void(0);" onClick="window.open('https://support.wbcomdesigns.com/portal/kb/articles/how-to-get-google-api-key','pagename','resizable,height=600,width=700'); return false;">
							<?php esc_html_e( '( How to Get Google API Key? )', 'bp-checkins' ); ?>
					</a>
				</p>
			</td>
		</tr>

		<?php if ( $bp_checkins->apikey ) { ?>
			<!-- Checkin By - autocomplete or placetype -->
			<tr>
				<th scope="row"><label for="checkin-by"><?php esc_html_e( 'Check-in by', 'bp-checkins' ); ?></label></th>
				<td>
					<p>
						<input <?php echo ( 'autocomplete' === $bp_checkins->checkin_by ) ? 'checked' : ''; ?> required type="radio" value="autocomplete" name="bpchk-checkin-by" class="bpchk-checkin-by" id="bpchk-checkin-by-autocomplete" checked="checked">
						<label for="bpchk-checkin-by-autocomplete"><?php esc_html_e( 'Location Autocomplete', 'bp-checkins' ); ?></label>
					</p>
					<p>
						<input <?php echo ( 'placetype' === $bp_checkins->checkin_by ) ? 'checked' : ''; ?> required type="radio" value="placetype" name="bpchk-checkin-by" class="bpchk-checkin-by" id="bpchk-checkin-by-placetype">
						<label for="bpchk-checkin-by-placetype"><?php esc_html_e( 'Place Types', 'bp-checkins' ); ?></label>
					</p>
					<p class="description"><?php esc_html_e( 'This setting will help the users check-in by autocomplete or place type google features.', 'bp-checkins' ); ?></p>
				</td>
			</tr>
		<?php } ?>

		<!-- Settings for place types -->
		<tr style="<?php echo esc_attr( $placetype_settings_style ); ?>" class="bpchk-placetype-settings-row">
			<th scope="row"><?php esc_html_e( 'Range', 'bp-checkins' ); ?></th>
			<td>
				<input type="hidden" value="<?php echo esc_attr( $saved_range ); ?>" id="hidden_range" />
				<input value="<?php echo esc_attr( $saved_range ); ?>" id="bpchk-google-places-range" type="range" name="bpchk-google-places-range" min="1" max="10">
				<span id="range_disp">
				<?php
				if ( $saved_range ) {
					echo esc_attr( $saved_range . ' kms.' );}
?>
</span>
				<p class="description"><?php esc_html_e( 'This will set the range for fetching the places while check-in.', 'bp-checkins' ); ?></p>
			</td>
		</tr>

		<!-- Settings for place types -->
		<tr style="<?php echo esc_attr( $placetype_settings_style ); ?>" class="bpchk-placetype-settings-row">
			<th scope="row"><?php esc_html_e( 'Place Types', 'bp-checkins' ); ?></th>
			<td>
				<p class="bpchk-selection-tags">
					<a href="javascript:void(0);" id="bpchk-select-all-place-types"><?php esc_html_e( 'Select All', 'bp-checkins' ); ?></a> /
					<a href="javascript:void(0);" id="bpchk-unselect-all-place-types"><?php esc_html_e( 'Unselect All', 'bp-checkins' ); ?></a>
				</p>
				<select name="bpchk-google-place-types[]" id="bpchk-pre-place-types" multiple>
					<?php foreach ( $place_types as $place_type ) { ?>
						<?php $placetype_slug = str_replace( ' ', '_', strtolower( $place_type ) ); ?>
						<option value="<?php echo esc_attr( $placetype_slug ); ?>" <?php echo ( ! empty( $bp_checkins->place_types ) && in_array( $placetype_slug, $bp_checkins->place_types, true ) ) ? 'selected' : ''; ?>><?php echo esc_attr( $place_type ); ?></option>
					<?php } ?>
				</select>
				<p class="description"><?php esc_html_e( 'This will help in fetching the place types, that will be selected here.', 'bp-checkins' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>
<p class="submit">
	<?php submit_button( 'Save Changes', 'primary', 'bpchk-submit-general-settings' ); ?>
</p>
