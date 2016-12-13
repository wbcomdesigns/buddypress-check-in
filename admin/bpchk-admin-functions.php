<?php 
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

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
    'Zoo'
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
        
        echo '<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong>BP Checkins Settings Saved.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
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