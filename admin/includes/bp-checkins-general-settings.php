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
?>

<table class="form-table bpchk-admin-page-table">
	<tbody>
		<!-- API Key -->
		<tr>
			<th scope="row"><label for="api-key"><?php esc_html_e( 'API Key', 'bp-checkins' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" value="<?php echo esc_attr( $bp_checkins->apikey ); ?>" name="bpchk-api-key" id="bpchk-api-key" placeholder="<?php esc_html_e( 'API Key', 'bp-checkins' ); ?>" required>
				<button type="button" class="button button-secondary" style="<?php echo esc_attr( $verify_btn_style ); ?>" id="bpchk-verify-apikey"><?php esc_html_e( 'Verify', 'bp-checkins' ); ?></button>
				<?php $gm_api_url = 'https://console.developers.google.com/henhouse/?pb=["hh-1","maps_backend",null,[],"https://developers.google.com",null,["static_maps_backend","street_view_image_backend","maps_embed_backend","places_backend","geocoding_backend","directions_backend","distance_matrix_backend","geolocation","elevation_backend","timezone_backend","maps_backend"],null]';?>
				
				<p class="description"><?php esc_html_e( "Due to changes in Google Maps API it's required to use an API key for the BuddyPress Check-ins plugin to work properly.", 'bp-checkins' ); ?>
				</p>
				<a id="gd-api-key" onclick='window.open("<?php echo wp_slash($gm_api_url);?>", "newwindow", "width=600, height=400"); return false;' href='<?php echo $gm_api_url;?>' class="button-primary" name="<?php _e('Generate API Key - ( MUST be logged in to your Google account )','geodirectory');?>" ><?php _e('Generate API Key','geodirectory');?></a>&nbsp;
					<a href="javascript:void(0);" onClick="window.open('https://support.wbcomdesigns.com/portal/kb/articles/how-to-get-google-api-key','pagename','resizable,height=600,width=700'); return false;">
							<?php esc_html_e( '( How to Get Google API Key? )', 'bp-checkins' ); ?>
					</a>
			</td>
		</tr>
		
	</tbody>
</table>
<p class="submit">
	<?php submit_button( 'Save Changes', 'primary', 'bpchk-submit-general-settings' ); ?>
</p>
