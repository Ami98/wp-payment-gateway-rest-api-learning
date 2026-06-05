<?php

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
