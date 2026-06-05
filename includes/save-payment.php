<?php

if (!defined('ABSPATH')) {
    exit;
}

function wppgral_save_payment($request)
{

    global $wpdb;

    $table = $wpdb->prefix . 'wppgral_payments';

    $data = $request->get_json_params();

    $name = sanitize_text_field($data['name']);
    $email = sanitize_email($data['email']);
    $amount = floatval($data['amount']);
    $payment_id = sanitize_text_field($data['payment_id']);

    $wpdb->insert(
        $table,
        [
            'name'       => $name,
            'email'      => $email,
            'amount'     => $amount,
            'payment_id' => $payment_id,
            'status'     => 'success',
        ]
    );

    return rest_ensure_response([
        'success' => true,
        'message' => 'Payment saved successfully'
    ]);
}
