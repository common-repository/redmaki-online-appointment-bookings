<?php
/**
 * Plugin Name:       Redmaki Online Appointment Bookings
 * Plugin URI:        https://www.redmaki.com/wordpress
 * Description:       Add the Redmaki Book now button to your website. Accept bookings everywhere on your site.
 * Version:           1.3
 * Requires at least: 4.7
 * Requires PHP:      5.2
 * Author:            Marco Stuijvenberg
 * Author URI:        https://www.redmaki.com
 * License:           GPL v2 or later
 * License URI:       https://www.redmaki.com/terms
 * Text Domain:       redmaki-online-appointment-bookings
 * Domain Path:       /languages/
 */

include plugin_dir_path(__FILE__) . "admin.php";

function add_redmaki_button() {
    
    $clientHash = get_option("redmaki-button-client-hash", "");
    $enabled = get_option("redmaki-button-enabled", "no");

    if ($enabled == "yes") {

            wp_register_script('redmakiButton','https://cdn.redmaki.com/button.js', null, null, true);
            wp_enqueue_script('redmakiButton');

        ?>
            <div id="redmakiPluginButton" apikey="<?php echo esc_attr($clientHash); ?>"></div>
        <?php
    }    
}

add_action('wp_footer', 'add_redmaki_button');