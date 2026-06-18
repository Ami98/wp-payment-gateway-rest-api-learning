
/*
    Page Opens
        ↓    
    payment.js loaded
        ↓
    User clicks Pay    
        ↓    
    preventDefault()    
        ↓    
    Read form values    
        ↓    
    fetch()    
    create-order    
        ↓    
    WordPress    
        ↓    
    wppgral_create_order()    
        ↓    
    wp_remote_post()    
        ↓    
    Razorpay API    
        ↓    
    order_id returned    
        ↓    
    JS receives order_id    
        ↓    
    new Razorpay()    
        ↓    
    rzp.open()    
        ↓    
    User pays    
        ↓    
    Razorpay returns    
    payment_id    
    order_id    
    signature    
        ↓    
    verifyPayment()    
        ↓    
    fetch()    
    verify-payment    
        ↓    
    WordPress    
        ↓    
    wppgral_verify_payment()    
        ↓    
    hash_hmac()    
        ↓    
    hash_equals()    
        ↓    
    Valid?    
    YES    
        ↓    
    $wpdb->insert()    
        ↓    
    Success Message
    
*/



jQuery(document).ready(function ($) {

    $('#wppgral-payment-form').on('submit', function (e) {

            e.preventDefault();
            let name = $('#wppgral-name').val();
            let email = $('#wppgral-email').val();
            let amount = $('#wppgral-amount').val();

            /*
            ------------------------------------
            Create Razorpay Order
            ------------------------------------
            */

            fetch(
                wppgral.rest_url + 'create-order',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': wppgral.nonce
                    },

                    body: JSON.stringify({
                        name: name,
                        email: email,
                        amount: amount
                    })

                }

            )

            // .then(response => response.json())
            
            .then(response => {
                if (!response.ok) {
                    return response.json()
                .then(error => {  throw error; });
                }
                return response.json();
 })

            .then(
                orderData => {
                    if (!orderData.success){
                        $('#payment-response').html('Unable to create order.');
                        return;
                    }

                    /*
                    -------------------------------
                    Razorpay Checkout
                    -------------------------------
                    */

                    let options = {

                        key:wppgral.razorpay_key,
                        amount: orderData.amount,
                        currency: 'INR',
                        name: 'WP Payment Gateway REST API Learning',
                        description: 'Learning Payment',
                        order_id: orderData.order_id,
                        prefill: {
                            name:name,
                            email:email
                        },

                        handler:
                         function (response) {
                            verifyPayment(response);
                        }
                    };

                    let rzp = new Razorpay(options);
                    rzp.open();
                }

            )

            .catch(error => {

                let message = error.message || 'Something went wrong';

                $('#payment-response').html( '<p>' + message + '</p>');

            });
        }
    );



    /*
    ------------------------------------
    Verify Payment
    ------------------------------------
    */

    function verifyPayment(
        response       
    ) 

    {

        fetch(wppgral.rest_url + 'verify-payment',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': wppgral.nonce
                },
                body: JSON.stringify({
                    
                    razorpay_order_id: response.razorpay_order_id, 
                    razorpay_payment_id: response.razorpay_payment_id, 
                    razorpay_signature: response.razorpay_signature
                })
            }
        )

        .then(response =>response.json())
        .then(data => {
            if (data.success)
                 {
                    $('#payment-response').html('<p>Payment Successful</p>');
                    $('#wppgral-payment-form')[0].reset();
                }

           else {
              $('#payment-response').html('<p>'+ (data.message || 'Payment Failed') + '</p>');
              }
            }
        )

        .catch(error => {
           console.error(error);
           $('#payment-response').html('<p>Something went wrong.</p>');

         });
    }
});