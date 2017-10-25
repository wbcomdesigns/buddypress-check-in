<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bpchk-adming-setting">
    <div class="bpchk-tab-header"><h3><?php _e( 'FAQ(s)', BPCHK_TEXT_DOMAIN );?></h3></div>

    <div class="bpchk-admin-settings-block">
        <div id="bpchk-settings-tbl">
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'What plugin does this plugin require?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'As the name of the plugin justifies, this plugin helps the site members to <strong>check-in, during their post updates</strong>, this plugin requires <strong>BuddyPress</strong> plugin to be installed and active.', BPCHK_TEXT_DOMAIN );?></p>
                        <p><?php _e( 'You\'ll also get an admin notice and the plugin will become ineffective if the required plugin will not be there.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'What is the use of API Key option provided in general settings section?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'With the help of Google Places Api Key, user can check-in with places autocomplete while updating post in buddypress and list checked in location in google map.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'How to check-in using Location Autocomplete option provided in general settings section?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'Location Autocomplete setting provide interface at activity page to post an update using google places autocomplete.', BPCHK_TEXT_DOMAIN );?></p>
                        <p><?php _e( 'Also there is an option [Add as my location] to set current check-in location as favourite location. ', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'How to check-in using Place Types option provided in general settings section?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'Place Types setting provide interface at activity page to post an update using selected google places types.Range option will set the range in kilometers for fetching the places while check-in.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'Where can I see all check-ins activity?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'Check-ins filter option is provided in BuddyPress filter dropdown option to list all check-ins activity.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'Where can I see favourite locations?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'All favourite locations are listed under Check-ins tab at BuddyPress profile page.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'How to set location at profile page?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'The plugin provides extended xprofile location field to set location at BuddyPress edit profile page.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'How to go for any custom development?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'If you need additional help you can contact us for <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Custom Development by Wbcom Designs">Custom Development</a>.', BPCHK_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>