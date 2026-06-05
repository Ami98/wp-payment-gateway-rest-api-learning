<?php
/*
Plugin Name: WP Payment Gateway REST API Learning
Description: A learning project demonstrating WordPress REST API, AJAX, payment gateway integration, and custom database operations.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-routes.php';
require_once plugin_dir_path(__FILE__) . 'includes/save-payment.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

register_activation_hook(__FILE__, 'wppgral_create_table');

/**
 * Load JS
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
            'rest_url' => rest_url('wppgral/v1/save-payment'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'wppgral_enqueue_scripts');
