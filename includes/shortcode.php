<?php
/*
|--------------------------------------------------------------------------
| Payment Form Shortcode
|--------------------------------------------------------------------------
|
| Usage:
| [wppgral_payment_form]
|
*/

function wppgral_payment_form_shortcode()
{

    ob_start();
?>

    <form id="wppgral-payment-form">

        <p>
            <label>Name</label><br>
            <input
                type="text"
                id="wppgral-name"
                required>
        </p>

        <p>
            <label>Email</label><br>
            <input
                type="email"
                id="wppgral-email"
                required>
        </p>

        <p>
            <label>Amount (INR)</label><br>
            <input
                type="number"
                id="wppgral-amount"
                value="500"
                min="1"
                required>
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

add_shortcode(
    'wppgral_payment_form',
    'wppgral_payment_form_shortcode'
);
