<?php

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
