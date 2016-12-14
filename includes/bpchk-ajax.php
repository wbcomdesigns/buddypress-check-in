<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//Class to serve AJAX Calls
if( !class_exists( 'BpchkAjax' ) ) {
    class BpchkAjax{

        //Constructor
        function __construct() {
            //Get Location
            add_action( 'wp_ajax_bpchk_get_locations', array( $this, 'bpchk_get_locations' ) );
            add_action( 'wp_ajax_nopriv_bpchk_get_locations', array( $this, 'bpchk_get_locations' ) );

            //Get location detail
            add_action( 'wp_ajax_bpchk_get_location_detail', array( $this, 'bpchk_get_location_detail' ) );
            add_action( 'wp_ajax_nopriv_bpchk_get_location_detail', array( $this, 'bpchk_get_location_detail' ) );

            //Cancel temp location
            add_action( 'wp_ajax_bpchk_cancel_temp_location', array( $this, 'bpchk_cancel_temp_location' ) );
            add_action( 'wp_ajax_nopriv_bpchk_cancel_temp_location', array( $this, 'bpchk_cancel_temp_location' ) );
        }

        //Actions performed to cancel temp location
        function bpchk_cancel_temp_location() {
            if (isset($_POST['action']) && $_POST['action'] === 'bpchk_cancel_temp_location') {
                global $wpdb;
                $tbl = $wpdb->prefix."options";
                $wpdb->delete( $tbl, array( 'option_name' => 'temp_location' ), array( '%s' ) );
                echo 'Checkin Location Cancelled!';
                die;
            }
        }

        //Actions performed to update group types
        function bpchk_get_locations() {
            if (isset($_POST['action']) && $_POST['action'] === 'bpchk_get_locations') {
                $lat = sanitize_text_field( $_POST['latitude'] );
                $lon = sanitize_text_field( $_POST['longitude'] );
                
                $bpchk_settings = get_option( 'bpchk_settings', true );
                if( !empty( $bpchk_settings ) ) {
                    $api_key = $bpchk_settings['api_key'];
                    $radius = $bpchk_settings['range'];
                    $place_type = $bpchk_settings['place_types'];
                }

                if( $api_key == '' ) {
                    $err_arr = array(
                        'found' => 'no',
                        'msg' => 'API Key Missing! If admin, check the settings in administrator panel or ask site administrator to save the API key.',
                    );
                    echo json_encode( $err_arr );
                    die;
                }

                if( $radius == '' ) {
                    $radius = 1000;
                }
                
                $type = implode(',', $place_type);
                $output = "json"; //OR it can be XML
                $parameters = "location=$lat,$lon&radius=$radius&type=$type&key=$api_key";
                $curl = curl_init();

                $curl_url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$lat,$lon&radius=$radius&type=$type&heading=false&title=false&key=$api_key";
                
                curl_setopt( $curl, CURLOPT_URL, $curl_url );
                curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                $location_data = curl_exec($curl);
                curl_close($curl);
                
                if( $location_data ) {
                    $locations = json_decode( $location_data );
                    
                    if( !empty( $locations->results ) ) {
                        $loc_arr = array();
                        
                        foreach( $locations->results as $index => $location ){
                            $arr = array(
                                'name' => $location->name,
                                'place_id' => $location->place_id,
                                'reference' => $location->reference,
                                'vicinity' => $location->vicinity,
                                'icon' => $location->icon,
                            );
                            $loc_arr[] = $arr;
                        }
                        echo json_encode( $loc_arr );
                        die;
                    } else {
                        $err_arr = array(
                            'found' => 'no',
                            'msg' => 'No Locations Found!',
                        );
                        echo json_encode( $err_arr );
                        die;
                    }
                } else {
                    $err_arr = array(
                        'found' => 'no',
                        'msg' => 'Error in getting locations',
                    );
                    echo json_encode( $err_arr );
                    die;
                }
            }
        }

        //Actions performed to get location detail from place id
        function bpchk_get_location_detail() {
            if (isset($_POST['action']) && $_POST['action'] === 'bpchk_get_location_detail') {
                $place_id = sanitize_text_field( $_POST['place_id'] );
                $reference = sanitize_text_field( $_POST['reference'] );
                $output = "json"; //OR it can be XML

                $bpchk_settings = get_option( 'bpchk_settings', true );
                if( !empty( $bpchk_settings ) ) {
                    $api_key = $bpchk_settings['api_key'];
                }
                
                $curl = curl_init();
                $curl_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$place_id&key=$api_key";

                curl_setopt( $curl, CURLOPT_URL, $curl_url );
                curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false);
                $location = curl_exec($curl);
                curl_close($curl);

                if( !empty( $location ) ) {
                    $location_detail = json_decode( $location );
                    //echo '<pre>'; print_r( $location_detail ); die("here");
                    if( !empty( $location_detail->result ) ) {

                        $lat = $location_detail->result->geometry->location->lat;
                        $lng = $location_detail->result->geometry->location->lng;
                        $location_name = preg_replace('/\s+/', '+', $location_detail->result->name);
                        $href = 'http://maps.google.com/maps/place/'.$location_name."/@$lat,$lng";
                        $html = "<a title='".$location_detail->result->name."' href='".$href."' target='_blank' id='bpchk-temp-location'>".$location_detail->result->name."</a>";
                        $html .= '<a href="javascript:void(0);" title="Cancel This Location!" id="bpchk-cancel-place"><i class="fa fa-times"></i></a>';
                        $place = array(
                            'place_name' => $location_detail->result->name,
                            'place_id' => $place_id,
                            'place_reference' => $reference,
                            'lat' => $lat,
                            'lng' => $lng,
                        );
                        
                        //Update options table to save the checked in data temporarily
                        global $wpdb;
                        $tbl = $wpdb->prefix."options";
                        $qry = "SELECT `option_id`,`option_value` from $tbl where `option_name` = 'temp_location'";
                        $result = $wpdb->get_results( $qry );
                        //If this temp location is empty, the first time
                        if( empty( $result ) ){
                            //Insert this location
                            $wpdb->insert( 
                                $tbl, 
                                array( 
                                    'option_name' => 'temp_location', 
                                    'option_value' => serialize( $place ), 
                                ), 
                                array( 
                                    '%s', 
                                    '%s' 
                                ) 
                            );
                        } else {
                            //Update this location
                            $option_id = $result[0]->option_id;
                            $wpdb->update( 
                                $tbl, 
                                array( 'option_value' => serialize( $place ) ), 
                                array( 'option_id' => $option_id ), 
                                array( '%s' ), 
                                array( '%d' ) 
                            );
                        }
                        
                        echo $html;
                        die;
                    } else {
                        echo "No Locations Found!";
                        die;
                    }
                } else {
                    echo "Error in getting location details!";
                    die;
                }
            }
        }
    }
    new BpchkAjax();
}