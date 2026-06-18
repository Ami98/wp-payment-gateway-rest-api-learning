<?php


/*
        Admin Login
        ↓
        WordPress Loads Plugin
        ↓
        settings-page.php loaded
        ↓
        Register Hooks
        ↓
        Admin Menu Hook
        ↓
        Create Menu
        ↓
        Admin Init Hook
        ↓
        Register Settings
        ↓
        User Opens Settings Page
        ↓
        Show Form
        ↓
        User Clicks Save
        ↓
        Save into wp_options table
    


*/





if (!defined('ABSPATH')) {
    exit;
}


/*
------------------------------------
Add Menu
------------------------------------
*/

add_action('admin_menu', 'wppgral_add_settings_menu');

function wppgral_add_settings_menu()
{
    add_options_page(
        'WP Payment Gateway REST API Learning',
        'WP Payment Gateway',
        'manage_options',
        'wppgral-settings',
        'wppgral_settings_page'
    );
}


/*
------------------------------------
Register Settings
------------------------------------
*/

add_action('admin_init', 'wppgral_register_settings');


function wppgral_register_settings()
{
    register_setting('wppgral_settings_group', 'wppgral_razorpay_key_id');
    register_setting('wppgral_settings_group', 'wppgral_razorpay_key_secret');
}


/*
------------------------------------
Settings Page UI
------------------------------------
*/

function wppgral_settings_page()
{

?>

    <div class="wrap">
        <h1>WP Payment Gateway REST API Learning </h1>

        <form method="post" action="options.php">

            <?php
            settings_fields('wppgral_settings_group');
            ?>


            <table class="form-table">
                <tr>
                    <th>Razorpay Key ID</th>
                    <td>
                        <input type="text" name="wppgral_razorpay_key_id" value="<?php echo esc_attr(get_option('wppgral_razorpay_key_id')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>Razorpay Key Secret</th>
                    <td>
                        <input type="password" name="wppgral_razorpay_key_secret" value="<?php echo esc_attr(get_option('wppgral_razorpay_key_secret')); ?>" class="regular-text">
                    </td>
                </tr>
            </table>

            <?php

            submit_button();

            ?>


        </form>

    </div>

<?php

}
