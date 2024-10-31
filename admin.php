<?php

function load_redmaki_plugin_textdomain() {
    load_plugin_textdomain('redmaki-online-appointment-bookings', FALSE, basename( dirname( __FILE__ ) ) . '/languages/');
}
  
add_action('plugins_loaded', 'load_redmaki_plugin_textdomain');

function redmaki_setup_menu() {
    add_menu_page( 'Redmaki Settings', 'Redmaki', 'manage_options', 'redmaki-online-appointment-bookings', 'redmaki_admin_init' );
}

add_action('admin_menu', 'redmaki_setup_menu');

function redmaki_admin_init() {

    echo "<h1>";
    esc_html_e('Redmaki button settings', 'redmaki-online-appointment-bookings');
    echo "</h1>";
    
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['redmaki_nonce']) &&
        wp_verify_nonce($_POST['redmaki_nonce'], 'redmaki_nonce')
    ) {

        // Don't actually read so we don't need to sanitize
        if (isset($_POST['enabled'])) {
            update_option("redmaki-button-enabled", "yes");
        } else {
            update_option("redmaki-button-enabled", "no");
        }

        $clientHash = sanitize_text_field($_POST['clientHash']);
        if (isset($clientHash)) {
            update_option("redmaki-button-client-hash", $clientHash);
        } else {
            update_option("redmaki-button-client-hash", "");
        }
    }

    $clientHash = get_option("redmaki-button-client-hash", "");
    $enabled = get_option("redmaki-button-enabled", "no");

    redmaki_renderForm($enabled, $clientHash);
}

function redmaki_renderForm($enabled, $clientHash) {
    ?>
    <style>
        input[type=submit] {
            margin-top: 10px;
            margin-left: 145px;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
        }
        .formRow {
            margin-top: 10px;            
        }
        label {
            display: inline-block;
            width: 140px;
            text-align: right;
        }​
    </style>
    <form method="post">
        <input type="hidden" name="redmaki_nonce" value="<?php echo wp_create_nonce('redmaki_nonce') ?>">
        <div class="formRow">
            <label><?php esc_html_e('Enabled', 'redmaki-online-appointment-bookings'); ?></label>
            <input type="checkbox" name="enabled" value="<?php echo esc_attr($enabled); ?>" <?php if (redmaki_isEnabled($enabled)) { echo "checked"; } ?>> 
        </div>
        <div class="formRow">
            <label><?php esc_html_e('Api key', 'redmaki-online-appointment-bookings'); ?></label>
            <input type="text" name="clientHash" size="50" value="<?php echo esc_attr($clientHash); ?>">
        </div>
        <input type="submit" value="<?php esc_html_e('Save changes', 'redmaki-online-appointment-bookings'); ?>" style="font-size: 16px">
    </form>
    <p>
        <?php 
            printf(
                __(
                    'You can find your Api key on this page in Redmaki: <a href=%s target="_blank">Your website</a>',
                    'redmaki-online-appointment-bookings'
                ),
                'https://go.redmaki.com/your-website/floating-button'
            );
        ?>
    <p>
    <?php
}

function redmaki_isEnabled($enabled) {
    return $enabled == "yes";
}