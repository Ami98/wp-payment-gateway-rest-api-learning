<?php

if (!defined('ABSPATH')) {
    exit;
}

/*********************
STEP 11 : The plugin defines a REST API route /wppgral/v1/save-payment that listens for POST requests. When a request is made to this endpoint, it triggers the wppgral_save_payment function, which processes the incoming payment data and saves it to the database. The permission_callback is set to __return_true, allowing anyone to access this endpoint without authentication, which is suitable for processing payments from the frontend.
 **********************/
/**
 * Create Razorpay Order
 */

function wppgral_create_order($request)
{



    global $wpdb;

    $table = $wpdb->prefix . 'wppgral_payments';

    /*
    Retrieves the JSON parameters from the incoming REST API request, which contains the payment details submitted by the user.
    The data is then sanitized to ensure it is safe for database insertion.
    The sanitized data includes the user's name, email, payment amount, and the Razorpay payment ID,
    which are then inserted into the custom database table created for storing payment records. 
    Finally, a success response is returned to indicate that the payment has been saved successfully.
    The function handles the logic for saving the payment details to the database when the REST API endpoint is called.
    reads the JSON parameters from the incoming REST API request, which contains the payment details submitted by the user.
    */


    //get_json_params() : converts the JSON data into a PHP array for further processing like insert form's data into database.
    /*  JSON {
            "name": "Ami",
            "email": "ami@test.com",
            "amount": "500",
            "payment_id": "pay_ABC123"
            }
            to a PHP array for further processing. The data is then sanitized to ensure it is safe for database insertion. 
            The sanitized data includes the user's name, email, payment amount, and the Razorpay payment ID, which are then inserted into the custom database table created for storing payment records. Finally, a success response is returned to indicate that the payment has been saved successfully. The function handles the logic for saving the payment details to the database when the REST API endpoint is called.
            converts the JSON request body into a PHP associative array:
        PHP : Array
            (
                [name] => Ami
                [email] => ami@test.com
                [amount] => 500
                [payment_id] => pay_ABC123
            ) */


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

    /* "WordPress returns JSON:"
    WordPress converts this PHP array into JSON automatically: The response will look like this:

        // {
        //   "success": true,
        //   "message": "Payment saved successfully"
        // }

        // with a 200 OK status code, indicating that the payment details were successfully saved to the database. */

    // and sends it back to the browser.goto step 13 to see how the frontend processes this response.
    return rest_ensure_response([
        'success' => true,
        'message' => 'Payment saved successfully'
    ]);
}
