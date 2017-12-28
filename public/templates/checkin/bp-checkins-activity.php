<?php
if( !defined( 'ABSPATH' ) ) exit;

global $bp_checkins;
$apikey = $bp_checkins->apikey;

$bpchk_fav_places = get_user_meta( bp_displayed_user_id(),'bpchk_fav_places',true);

if( !empty( $bpchk_fav_places ) ) {
	$bpchk_fav_places = array_reverse($bpchk_fav_places);
}
else {
	$bpchk_fav_places = $bpchk_fav_places;
}

if($bpchk_fav_places){
	?>
	<div id="accordion" class="bpchk-fav-loc-map-container">
	<?php
	foreach ($bpchk_fav_places as $key => $fav_places) {
	$map_url = 'https://www.google.com/maps/embed/v1/place?key='.$apikey.'&q='.$fav_places['formatted_address'];
?>

	<h3><?php echo $fav_places['place']; ?></h3>
	<div>
		<div class="bpchk-fav-loc-map">
			<iframe frameborder="0" style="border:0" src="<?php echo $map_url;?>" allowfullscreen></iframe>
		</div>
		<div class="bpchk-fav-loc-map-details">
			<?php if($fav_places['formatted_address']){ ?>

				<div class="bpchk-fav-loc-row">
					<label><?php _e( 'Address', BPCHK_TEXT_DOMAIN ); ?></label>
					<span><?php echo $fav_places['formatted_address']; ?></span>
				</div>

			<?php } ?>
			<?php if($fav_places['street']){ ?>

				<div class="bpchk-fav-loc-row">
					<label><?php _e( 'Street', BPCHK_TEXT_DOMAIN ); ?></label>
					<span><?php echo $fav_places['street']; ?></span>
				</div>

			<?php } ?>
			<?php if($fav_places['postal_code']){ ?>

				<div class="bpchk-fav-loc-row">
					<label><?php _e( 'Postal Code', BPCHK_TEXT_DOMAIN ); ?></label>
					<span><?php echo $fav_places['postal_code']; ?></span>
				</div>

			<?php } ?>
			<?php if($fav_places['city']){ ?>

				<div class="bpchk-fav-loc-row">
					<label><?php _e( 'City', BPCHK_TEXT_DOMAIN ); ?></label>
					<span><?php echo $fav_places['city']; ?></span>
				</div>

			<?php } ?>
			<?php if($fav_places['visit_date']){ ?>

				<div class="bpchk-fav-loc-row">
					<label><?php _e( 'Visited Date', BPCHK_TEXT_DOMAIN ); ?></label>
					<span><?php echo $fav_places['visit_date']; ?></span>
				</div>

			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
	}
	?></div><?php
}
