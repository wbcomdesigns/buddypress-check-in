<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/admin
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $bp_checkins;
$apikey = $bp_checkins->apikey;

$bpchk_fav_places = get_user_meta( bp_displayed_user_id(), 'bpchk_fav_places', true );

if ( ! empty( $bpchk_fav_places ) ) {
	$bpchk_fav_places = array_reverse( $bpchk_fav_places );
} else {
	$bpchk_fav_places = $bpchk_fav_places;
}

if ( $bpchk_fav_places ) { ?>
	<div id="accordion" class="bpchk-fav-loc-map-container">
	<?php
	foreach ( $bpchk_fav_places as $key => $fav_places ) {
		$map_url = 'https://www.google.com/maps/embed/v1/place?key=' . $apikey . '&q=' . $fav_places['formatted_address'];
		?>
		<h3><?php echo esc_attr( $fav_places['place'] ); ?>
			<?php if ( bp_displayed_user_id() === get_current_user_id() ) { ?>
				<span class="checkin-trash"><i class="fa fa-trash bpcp-checkin-trash" attr-key="<?php echo esc_attr( $fav_places['activity_id'] ); ?>"></i></span>
			<?php } ?>
		</h3>
		<div>
			<div class="bpchk-fav-loc-map">
				<iframe frameborder="0" style="border:0" src="<?php echo esc_url( $map_url ); ?>" allowfullscreen></iframe>
			</div>
			<div class="bpchk-fav-loc-map-details">
				<?php if ( $fav_places['formatted_address'] ) { ?>

					<div class="bpchk-fav-loc-row">
						<label><?php esc_html_e( 'Address', 'bp-checkins' ); ?></label>
						<span><?php echo esc_attr( $fav_places['formatted_address'] ); ?></span>
					</div>

				<?php } ?>
				<?php if ( $fav_places['street'] ) { ?>

					<div class="bpchk-fav-loc-row">
						<label><?php esc_html_e( 'Street', 'bp-checkins' ); ?></label>
						<span><?php echo esc_attr( $fav_places['street'] ); ?></span>
					</div>

				<?php } ?>
				<?php if ( $fav_places['postal_code'] ) { ?>

					<div class="bpchk-fav-loc-row">
						<label><?php esc_html_e( 'Postal Code', 'bp-checkins' ); ?></label>
						<span><?php echo esc_attr( $fav_places['postal_code'] ); ?></span>
					</div>

				<?php } ?>
				<?php if ( $fav_places['city'] ) { ?>

					<div class="bpchk-fav-loc-row">
						<label><?php esc_html_e( 'City', 'bp-checkins' ); ?></label>
						<span><?php echo esc_attr( $fav_places['city'] ); ?></span>
					</div>

				<?php } ?>
				<?php if ( $fav_places['visit_date'] ) { ?>

					<div class="bpchk-fav-loc-row">
						<label><?php esc_html_e( 'Visited Date', 'bp-checkins' ); ?></label>
						<span><?php echo esc_attr( $fav_places['visit_date'] ); ?></span>
					</div>

				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
	</div>
	<?php
} else {
	?>
	<aside class="bp-feedback bp-messages info">
	<span class="bp-icon" aria-hidden="true"></span>
	<p><?php esc_html_e( 'Sorry, there was no location found. Please try adding a location.', 'bp-checkins' ); ?></p>
	</aside>
	<?php
}
