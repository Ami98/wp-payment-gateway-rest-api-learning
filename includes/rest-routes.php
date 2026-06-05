<?php

/*********************
STEP 10 : The plugin defines a REST API route /wppgral/v1/save-payment that listens for POST requests. When a request is made to this endpoint, it triggers the wppgral_save_payment function, which processes the incoming payment data and saves it to the database. The permission_callback is set to __return_true, allowing anyone to access this endpoint without authentication, which is suitable for processing payments from the frontend.
 **********************/


if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {

    register_rest_route(
        'wppgral/v1',
        '/save-payment',
        [
            'methods' => 'POST',
            'callback' => 'wppgral_save_payment',
            'permission_callback' => '__return_true'
        ]
    );
});
