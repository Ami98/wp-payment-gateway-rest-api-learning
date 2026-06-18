<?php

/*********************
  
    This is the most important file because it creates the Razorpay Order on the server using wp_remote_post().

    After this file works, your flow becomes:

    User Clicks Pay
        ↓
    REST API
        ↓
    wppgral_create_order()
        ↓
    wp_remote_post()
        ↓
    Razorpay Order Created
        ↓
    order_id returned
        ↓
    JavaScript receives order_id
        ↓
    Checkout Opens

 **********************/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create Razorpay Order
 */
function wppgral_create_order($request)
{
    //convert json data into PHP array
    $data = $request->get_json_params();

    $name = isset($data['name'])
        ? sanitize_text_field($data['name'])
        : '';

    $email = isset($data['email'])
        ? sanitize_email($data['email'])
        : '';

    $amount = isset($data['amount'])
        ? floatval($data['amount'])
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Basic Validation
    |--------------------------------------------------------------------------
    */

    if (empty($name)) {

        return new WP_Error(
            'invalid_name',
            'Name is required',
            ['status' => 400]
        );
    }

    if (empty($email)) {

        return new WP_Error(
            'invalid_email',
            'Email is required',
            ['status' => 400]
        );
    }

    if ($amount <= 0) {

        return new WP_Error(
            'invalid_amount',
            'Invalid amount',
            ['status' => 400]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Razorpay Credentials comes from setting page
    |--------------------------------------------------------------------------
    */

    $key_id = get_option('wppgral_razorpay_key_id');
    $key_secret = get_option('wppgral_razorpay_key_secret');

    if (empty($key_id) || empty($key_secret)) {

        return new WP_Error(
            'missing_credentials',
            'Razorpay credentials are not configured.',
            [
                'status' => 500
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Razorpay Requires Amount in Paise
    |--------------------------------------------------------------------------
    */

    $amount_in_paise = $amount * 100;

    if (empty($key_id) || empty($key_secret)) {
        return new WP_Error(
            'missing_credentials',
            'Razorpay credentials are not configured.',
            [
                'status' => 500
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Unique Receipt
    |--------------------------------------------------------------------------
    */

    $receipt = 'receipt_' . time();  //Used for identifying the transaction. You can generate it as per your requirement.

    /*
    |--------------------------------------------------------------------------
    | Create Order via Razorpay API
    |--------------------------------------------------------------------------
    */

    $response = wp_remote_post(
        'https://api.razorpay.com/v1/orders',
        [
            'headers' => [
                'Authorization' =>
                'Basic ' .
                    base64_encode(
                        $key_id . ':' . $key_secret
                    ),

                'Content-Type' => 'application/json'
            ],

            'body' => wp_json_encode([
                'amount'   => $amount_in_paise,
                'currency' => 'INR',
                'receipt'  => $receipt
            ]),

            'timeout' => 30
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Handle HTTP Error
    |--------------------------------------------------------------------------
    */

    if (is_wp_error($response)) {

        return new WP_Error(
            'razorpay_error',
            $response->get_error_message(),
            ['status' => 500]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get API Response
    |--------------------------------------------------------------------------
    */
    // Converts JSON → PHP array

    $body = json_decode(
        wp_remote_retrieve_body($response),
        true
    );


    /* ------------------------------------
        Save Pending Payment
    ------------------------------------  */

    global $wpdb;

    $table = $wpdb->prefix . 'wppgral_payments';

    /*
    |--------------------------------------------------------------------------
    | Razorpay API Error
    |--------------------------------------------------------------------------
    */

    if (isset($body['error'])) {
        wppgral_log('', 'razorpay_error', $body['error']['description']);
        return new WP_Error(
            'razorpay_api_error',
            'Razorpay Error: ' . $body['error']['description'],
            ['status' => 500]
        );
    }

    $wpdb->insert(
        $table,
        [
            'name' => $name,
            'email' => $email,
            'amount' => $amount,
            'razorpay_order_id' => $body['id'],
            'razorpay_payment_id' => '',
            'payment_status' => 'pending'
        ]
    );

    wppgral_log(
        $body['id'],
        'create_order',
        'Pending payment created'
    );


    /*
            |--------------------------------------------------------------------------
    | Return Order Data To JavaScript
    |--------------------------------------------------------------------------
    */

    return rest_ensure_response([
        'success'   => true,
        'name'      => $name,
        'email'     => $email,
        'amount'    => $amount_in_paise,
        'order_id'  => $body['id']
    ]);
}
