<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Add admin page for saving API Key Settings
if( !class_exists( 'BpchkAdmin' ) ) {
    class BpchkAdmin{

        //Constructor
        function __construct() {
            add_action( 'admin_menu', array( $this, 'bpchk_menu_page' ) );
        }

        //Actions performed on loading admin_menu
        function bpchk_menu_page() {
            $icon_url = BPCHK_PLUGIN_URL.'admin/assets/images/checkin.png';
            add_menu_page( __('BuddyPress Checkins', 'bp-checkins'), __('BP Checkins', 'bp-checkins'), 'manage_options', 'bp-checkins', array($this, 'bpchk_admin_options_page'), $icon_url, 56);
        }

        function bpchk_admin_options_page() {
            include 'bpchk-admin-options-page.php';
        }
    }
    new BpchkAdmin();
}