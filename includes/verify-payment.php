<?php

/*********************
    This is the most important security file of the whole plugin.
    This file is responsible for verifying the Razorpay payment on the server. It receives the payment details from the frontend, 
    generates a signature using the Razorpay secret key, and compares it with the signature sent from the frontend. 
    If the signatures match, it saves the payment details to the database and returns a success response.
    If the signatures do not match, it returns an error response indicating that the payment verification failed. 
    
    Payment Success ≠ Payment Verified

    Many beginners think:

    Payment Popup Closed Successfully
            ↓
    Payment Verified

    ❌ Wrong

    Instead:

    Payment Success
        ↓
    Razorpay returns

    payment_id
    order_id
    signature

        ↓
    Server verifies signature

        ↓
    Payment Verified

        ↓
    Save in DB

    Only after verification should we save payment.




 ***********************/



if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verify Razorpay Payment
 */
function wppgral_verify_payment($request)
{

    global $wpdb;

    $table = $wpdb->prefix . 'wppgral_payments';

    /*
    |--------------------------------------------------------------------------
    | Get JSON Data
    |--------------------------------------------------------------------------
    */

    $data = $request->get_json_params();

    $order_id = sanitize_text_field(
        $data['razorpay_order_id']
    );

    $payment_id = sanitize_text_field(
        $data['razorpay_payment_id']
    );

    $signature = sanitize_text_field(
        $data['razorpay_signature']
    );

    /*
    |--------------------------------------------------------------------------
    | Razorpay Secret Key comes from setting page
    |--------------------------------------------------------------------------
    */

    $secret = get_option('wppgral_razorpay_key_secret');

    if (empty($secret)) {

        return new WP_Error(

            'missing_secret',
            'Razorpay secret key is missing.',
            [
                'status' => 500
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Signature
    |--------------------------------------------------------------------------
    */

    $generated_signature = hash_hmac(
        'sha256',
        $order_id . '|' . $payment_id,
        $secret
    );

    /*
    |--------------------------------------------------------------------------
    | Compare Signatures
    |--------------------------------------------------------------------------
    */

    if (!hash_equals($generated_signature, $signature)) {
        wppgral_log($order_id, 'signature_failed', 'Signature mismatch');


        /*
        --------------------------------
        Update Failed Status
        --------------------------------
        */

        $wpdb->update(
            $table,
            [
                'payment_status' => 'failed',
                'failure_reason' => 'Signature mismatch'
            ],

            [
                'razorpay_order_id' => $order_id
            ]

        );



        return new WP_Error(
            'invalid_signature',
            'Signature mismatch. Payment could not be verified.',
            [
                'status' => 400
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Successful Payment
    |--------------------------------------------------------------------------
    */

    $result = $wpdb->update(
        $table,
        [
            'razorpay_payment_id' => $payment_id,
            'payment_status' => 'success',
            'failure_reason' => ''
        ],
        [
            'razorpay_order_id' => $order_id
        ]
    );
    /*
    ------------------------------------
    Database Error
    ------------------------------------
    */
    if ($result === 0) {

        return new WP_Error(

            'payment_not_found',

            'Pending payment record not found.',

            [

                'status' => 404

            ]

        );
    }
    if ($result === false) {
        wppgral_log(

            $order_id,

            'database_error',

            'Unable to update payment'

        );
        return new WP_Error(
            'database_error',
            'Unable to update  payment.',
            [
                'status' => 500
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Success
    |--------------------------------------------------------------------------
    */

    wppgral_log(

        $order_id,

        'payment_success',

        'Payment verified successfully'

    );

    return rest_ensure_response([
        'success' => true,
        'message' => 'Payment verified successfully'
    ]);
}
