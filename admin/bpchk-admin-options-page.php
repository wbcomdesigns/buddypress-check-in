<?php 
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

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

//Save Deails
if( isset( $_POST['save_bpchk_settings'] ) && wp_verify_nonce( $_POST['save_checkin_settings_data_nonce'], 'bp-checkins' ) ) {
    $api = sanitize_text_field( $_POST['api_key'] );
    $range = sanitize_text_field( $_POST['range'] ) * 1000;
    $selected_place_types = wp_unslash( $_POST['google_place_types'] );
    
    $bpchk_settings = array(
        'api_key' => $api,
        'range' => $range,
        'place_types' => $selected_place_types
    );

    update_option( 'bpchk_settings', $bpchk_settings );

    //Save Success Message
    $msg = '<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">';
    $msg .= '<p><strong>BP Checkins Settings Saved.</strong></p>';
    $msg .= '<button class="notice-dismiss" type="button">';
    $msg .= '<span class="screen-reader-text">Dismiss this notice.</span>';
    $msg .= '</button>';
    $msg .= '</div>';
    echo $msg;
}

$saved_api = $saved_range = '';
$bpchk_settings = get_option( 'bpchk_settings', true );
if( $bpchk_settings != '' ) {
    $saved_api = $bpchk_settings['api_key'];
    $saved_range = $bpchk_settings['range'];
    if( $saved_range ) {
        $saved_range = $saved_range / 1000;
    }
    $saved_place_types = $bpchk_settings['place_types'];
}

?>
<div class="wrap">
    <div class="bpchk-gmap-logo">
        <?php $img = BPCHK_PLUGIN_URL.'admin/assets/images/Google-Maps-icon.png';?>
        <img src="<?php echo $img;?>" alt="Google Places Logo" title="Google Places"> 
    </div>
    
    <h1><?php _e( 'BuddyPress Checkins Settings', 'bp-checkins' );?></h1>
    <form action="" method="post">
        <table class="form-table bpchk-settings-tbl">
            <tbody>
                <!-- API Key -->
                <tr>
                    <th scope="row">
                        <label for="api_key">
                            <?php _e( 'API KEY', 'bp-checkins' );?>
                        </label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="api_key" placeholder="API KEY" value="<?php echo $saved_api;?>">
                        <a href="javascript:void(0);" onClick="window.open('https://developers.google.com/places/web-service/','pagename','resizable,height=600,width=700'); return false;">
                            <?php _e( 'Don\'t have it? Get it here!', 'bp-checkins' );?>
                        </a>
                    </td>
                </tr>
                
                <!-- Range -->
                <tr>
                    <th scope="row">
                        <label for="range">
                            <?php _e( 'Range', 'bp-checkins' );?>
                        </label>
                    </th>
                    <td>
                        <input value="<?php echo $saved_range;?>" id="google_range" type="range" class="regular-text" name="distance" min="0" max="10">
                        <span id="range_disp">
                            <?php if( $saved_range ) echo "$saved_range kms.";?>
                        </span>
                        <?php $hidden_rnge = ( $saved_range == '' ) ? 5 : $saved_range;?>
                        <input type="hidden" name="range" value="<?php echo $hidden_rnge;?>" id="hidden_range">
                    </td>
                </tr>
                
                <!-- Place Types -->
                <tr>
                    <th scope="row">
                        <label for="place_types">
                            <?php _e( 'Place Types', 'bp-checkins' );?>
                        </label>
                        <p>
                            <small>
                                    <?php _e( 'This lists the supported values for the types property in the Google Places API. You can select any type here to get the results when user tries to checkin activity.', 'bp-checkins' );?>
                            </small>
                        </p>
                    </th>
                    <td>
                        <p>
                            <input type="checkbox" id="bpchk_select_all_place_types"><?php _e( 'Select All', 'bp-checkins' );?>
                        </p>
                        <?php foreach( $place_types as $place_type ) {?>
                            <?php $place_type_slug = str_replace( ' ', '_', strtolower( $place_type ) );?>
                            <p>
                                <input type="checkbox" class="place_types" name="google_place_types[]" value="<?php echo $place_type_slug;?>" <?php if( !empty( $saved_place_types ) && in_array( $place_type_slug, $saved_place_types ) ) echo "checked='checked'";?>><?php echo $place_type;?>
                            </p>
                        <?php }?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="submit">
            <?php wp_nonce_field( 'bp-checkins', 'save_checkin_settings_data_nonce'); ?>
            <input type="submit" value="Save Settings" class="button button-primary" name="save_bpchk_settings">
        </p>
    </form>
</div>