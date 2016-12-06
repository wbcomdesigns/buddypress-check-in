<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//Class to add custom scripts and styles
if( !class_exists( 'BpchkScriptsStyles' ) ) {
    class BpchkScriptsStyles{

        //Constructor
        function __construct() {
            $curr_url = $_SERVER['REQUEST_URI'];
            if( strpos($curr_url, 'bp-checkins') !== false ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'bpchk_admin_variables' ) );
            }
            add_action( 'wp_enqueue_scripts', array( $this, 'bpchk_site_variables' ) );
        }

        //Actions performed for enqueuing scripts and styles for admin panel
        function bpchk_admin_variables() {
            wp_enqueue_script('bpchk-js-admin', BPCHK_PLUGIN_URL.'admin/assets/js/bpchk-admin.js', array('jquery'));
            wp_enqueue_style('bpchk-css-admin', BPCHK_PLUGIN_URL.'admin/assets/css/bpchk-admin.css');
        }

        //Actions performed for enqueuing scripts and styles for site front
        function bpchk_site_variables() {
            wp_enqueue_style('bpchk-css-front', BPCHK_PLUGIN_URL.'assets/css/bpchk-front.css');
            wp_enqueue_script('bpchk-js-front', BPCHK_PLUGIN_URL.'assets/js/bpchk-front.js', array('jquery'));

            //Font Awesome
            wp_enqueue_style('bpchk-fa-css', BPCHK_PLUGIN_URL.'assets/css/font-awesome.min.css');
        }
    }
    new BpchkScriptsStyles();
}