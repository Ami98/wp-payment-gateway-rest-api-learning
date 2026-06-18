<?php
/*
Plugin Name: WP Payment Gateway REST API Learning
Description: A learning project demonstrating WordPress REST API, AJAX, payment gateway integration, and custom database operations.
Version: 1.0
Author: Your Name
*/

/*********************
 STEP 1 : The plugin starts by defining its metadata, including the name, description, version, and author. It then checks if the ABSPATH constant is defined to ensure that the plugin is being accessed within the WordPress environment. If not, it exits to prevent direct access.
 **********************/

if (!defined('ABSPATH')) {
    exit;
}


/*********************
 STEP 2: The plugin includes several PHP files that contain the core functionality of the plugin. 
These files handle database operations, define REST API routes, process payment saving logic, and create a shortcode for displaying the payment form.
 **********************/

require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-routes.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/create-order.php';
require_once plugin_dir_path(__FILE__) . 'includes/verify-payment.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-payments.php';
require_once plugin_dir_path(__FILE__) . 'includes/logger.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-logs.php';


/*********************
 STEP 3 : The plugin registers an activation hook that calls the wppgral_create_table function when the plugin is "activated". This function creates a custom database table to store payment records, including fields for the user's name, email, amount, payment ID, status, and creation timestamp.
 **********************/
register_activation_hook(__FILE__, 'wppgral_create_table');





/*********************
 STEP 5: Frontend Page Loads : The plugin enqueues necessary JavaScript files, including the Razorpay checkout script and a custom payment script. It also localizes the custom script to pass the REST API endpoint URL for saving payments. This allows the JavaScript code to make AJAX requests to the correct endpoint when processing payments.
 **********************/

/**
 * Load JS , | Enqueue Scripts
 */
function wppgral_enqueue_scripts()
{

    wp_enqueue_script(
        'razorpay-checkout',
        'https://checkout.razorpay.com/v1/checkout.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'wppgral-payment',
        plugin_dir_url(__FILE__) . 'assets/js/payment.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script(
        'wppgral-payment',
        'wppgral',
        [
            'rest_url' => rest_url('wppgral/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),

            // Test Key ID only
            'razorpay_key' => get_option('wppgral_razorpay_key_id')
        ]
    );
}
add_action('wp_enqueue_scripts', 'wppgral_enqueue_scripts');
