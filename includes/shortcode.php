<?php

/*********************
STEP 6 : The plugin defines a shortcode [wppgral_payment_form] that renders a payment form on the frontend. The form includes fields for the user's name, email, and payment amount, along with a "Pay Now" button. When the form is submitted, it triggers JavaScript code that processes the payment using Razorpay's checkout and sends the payment details to the REST API endpoint for saving in the database. The shortcode allows users to easily embed the payment form on any page or post by simply adding the shortcode to the content.
 **********************/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode
 * [wppgral_payment_form]
 */


function wppgral_payment_form_shortcode()
{

    ob_start();
?>

    <form id="wppgral-payment-form">

        <p>
            <input type="text" id="name" placeholder="Name" required>
        </p>

        <p>
            <input type="email" id="email" placeholder="Email" required>
        </p>

        <p>
            <input type="number" id="amount" value="500" required>
        </p>

        <p>
            <button type="submit">
                Pay Now
            </button>
        </p>

    </form>

    <div id="payment-response"></div>

<?php

    return ob_get_clean();
}
add_shortcode('wppgral_payment_form', 'wppgral_payment_form_shortcode');
