<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly
$bpchk_redirect_url = bp_core_get_userlink( get_current_user_id(), false, true ).'checkin/my-places';
?>
<form method="POST" action="">
	<table class="bpchk-add-place" border="0">
		<!-- ADD PLACE -->
		<tr>
			<th><?php _e( 'Place', BPCHK_TEXT_DOMAIN );?></th>
			<td>
				<input type="text" id="bpchk-add-place-title" name="bpchk-add-place-title" placeholder="<?php _e( 'Start writing the place here', BPCHK_TEXT_DOMAIN );?>" required />
				<input type="hidden" name="bpchk-place-latitude" id="bpchk-place-latitude">
				<input type="hidden" name="bpchk-place-longitude" id="bpchk-place-longitude">
			</td>
		</tr>
		<!-- DATE TO VISIT THE PLACE - TO GET REMINDERS -->
		<tr>
			<th><?php _e( 'When do you want to visit', BPCHK_TEXT_DOMAIN );?></th>
			<td><input type="text" id="bpchk-add-place-visit-date" name="bpchk-add-place-visit-date" required placeholder="<?php _e( 'Date to visit the place', BPCHK_TEXT_DOMAIN );?>" /></td>
		</tr>
		<!-- SAVE THE PLACE -->
		<tr>
			<?php wp_nonce_field( 'bpchk-add-place', 'bpchk-add-place-to-visit-nonce' );?>
			<td colspan="2">
				<input type="submit" name="bpchk-save-place-to-visit" id="bpchk-save-place-to-visit" value="<?php _e( 'Save Place', BPCHK_TEXT_DOMAIN );?>" />
				<input type="hidden" name="bpchk-redirect" value="<?php echo $bpchk_redirect_url;?>" />
			</td>
		</tr>
	</table>
</form>