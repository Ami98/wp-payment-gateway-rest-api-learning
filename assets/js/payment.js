jQuery(document).ready(function ($) {

    $("#wppgral-payment-form").on("submit", function (e) {

        e.preventDefault();

        let name = $("#name").val();
        let email = $("#email").val();
        let amount = $("#amount").val();

        var options = {

            key: "rzp_test_Sx7LedFQb4CUpq",
            amount: amount * 100,
            currency: "INR",
            name: "WP Payment Gateway REST API Learning",
            description: "Learning Payment",

            handler: function (response) {

                fetch(wppgral.rest_url, {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({
                        name: name,
                        email: email,
                        amount: amount,
                        payment_id: response.razorpay_payment_id
                    })

                })
                .then(res => res.json())
                .then(data => {

                    $("#payment-response").html(
                        "<p>Payment Successful</p>"
                    );

                    $("#wppgral-payment-form")[0].reset();
                })
                .catch(error => {

                    $("#payment-response").html(
                        "<p>Error saving payment.</p>"
                    );

                    console.log(error);
                });

            }

        };

        var rzp = new Razorpay(options);

        rzp.open();

    });

});