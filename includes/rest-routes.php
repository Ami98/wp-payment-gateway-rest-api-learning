<?php

/*********************
STEP 10 : The plugin defines a REST API route /wppgral/v1/save-payment that listens for POST requests. When a request is made to this endpoint, it triggers the wppgral_save_payment function, which processes the incoming payment data and saves it to the database. The permission_callback is set to __return_true, allowing anyone to access this endpoint without authentication, which is suitable for processing payments from the frontend.
 
This file will register:

/wp-json/wppgral/v1/create-order
/wp-json/wppgral/v1/verify-payment

and connect them to the callback functions.
This file is the bridge between the browser and your PHP functions.

Without this file:

JavaScript
   ↓
/wp-json/wppgral/v1/create-order
   ❌
WordPress doesn't know what to do

With this file:

JavaScript
   ↓
REST URL
   ↓
WordPress Route
   ↓
PHP Callback Function
 **********************/


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API Routes
 */
add_action('rest_api_init', 'wppgral_register_rest_routes');

function wppgral_register_rest_routes()
{

    /**
     * Create Razorpay Order
     */
    register_rest_route(
        'wppgral/v1',
        '/create-order',
        [
            'methods'  => 'POST',
            'callback' => 'wppgral_create_order',
            'permission_callback' => 'wppgral_rest_permission'
        ]
    );

    /**
     * Verify Razorpay Payment
     */
    register_rest_route(
        'wppgral/v1',
        '/verify-payment',
        [
            'methods'  => 'POST',
            'callback' => 'wppgral_verify_payment',
            'permission_callback' => 'wppgral_rest_permission'
        ]
    );
}


/**
 * Verify REST Nonce
 */
function wppgral_rest_permission()
{

    $nonce = '';

    if (isset($_SERVER['HTTP_X_WP_NONCE'])) {
        $nonce = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WP_NONCE']));
    }

    if (!wp_verify_nonce($nonce, 'wp_rest')) {

        return new WP_Error(
            'invalid_nonce',
            'Security check failed',
            [
                'status' => 403
            ]
        );
    }

    return true;
}
