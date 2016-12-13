<?php 
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
include 'bpchk-admin-functions.php';
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
                        <input type="hidden" name="range" value="5" id="hidden_range">
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