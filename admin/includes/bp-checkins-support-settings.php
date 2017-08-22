<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bpchk-adming-setting">
    <div class="bpchk-tab-header"><h3><?php _e( 'Have some questions?', BPCHK_TEXT_DOMAIN );?></h3></div>

    <div class="bpchk-admin-settings-block">
        <div id="bpchk-settings-tbl">
            <div class="bpchk-admin-row">
                <div>
                    <button class="bpchk-accordion"><?php _e( 'What plugin does this plugin require?', BPCHK_TEXT_DOMAIN );?></button>
                    <div class="panel">
                        <p><?php _e( 'As the name of the plugin justifies, this plugin helps the site members to <strong>checkin, during their post updates</strong>, this plugin requires <strong>BuddyPress</strong> plugin to be installed and active.', BPCHK_TEXT_DOMAIN );?></p>
                        <p><?php _e( 'You\'ll also get an admin notice and the plugin will become ineffective if the required plugin will not be there.', BPCHK_TEXT_DOMAIN );?></p>
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