<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//Include the checked in place while posting update
add_action('bp_activity_posted_update','bpchk_update_meta_on_post_update',10,3);
function bpchk_update_meta_on_post_update( $content, $user_id, $activity_id ) {
	global $wpdb;
	$tbl = $wpdb->prefix."options";
	$qry = "SELECT `option_value` from $tbl where `option_name` = 'temp_location'";
	$result = $wpdb->get_results( $qry );
	if( !empty( $result ) ){
		$place = $result[0]->option_value;
		bp_activity_update_meta( $activity_id, 'checkedin_location', $place );

		//Update content on post update
		$unserialized_place = unserialize( $place );
		$location = $unserialized_place['place_name'];
		$lng = $unserialized_place['lng'];
		$lat = $unserialized_place['lat'];
		$locname = preg_replace('/\s+/', '+', $location);
		$location_html = '-at <a class=checkin-loc href="http://maps.google.com/maps/place/'.$locname.'/@'.$lat.','.$lng.'" target="_blank" title="'.$location.'">'.$location.'</a>';

		//Get the content of the activity
		$activity_tbl = $wpdb->prefix."bp_activity";
		$activity_qry = "SELECT * from $activity_tbl where `id` = $activity_id";
		$activity_result = $wpdb->get_results( $activity_qry );

		$pos = strpos( $activity_result[0]->content, '-at <a class="checkin-loc"' );

		if( $pos === false ){
			$wpdb->update( 
				$activity_tbl, 
				array( 'content' => $activity_result[0]->content.$location_html ), 
				array( 'id' => $activity_id ), 
				array( '%s' ), 
				array( '%d' ) 
			);
		}

		//Delete the temp location in `options` table
		$wpdb->delete( $tbl, array( 'option_name' => 'temp_location' ) );
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery('.checkin-panel').hide();
				jQuery('#bpchk-close').hide();
			});
		</script>
		<?php
	}
}