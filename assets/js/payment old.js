jQuery(document).ready(function ($) {

    /*********************
    STEP 7 : The JavaScript code listens for the submission of the payment form with the ID wppgral-payment-form.
     When the form is submitted, it prevents the default form submission behavior and retrieves the values entered by the user for name, 
     email, and amount.
    **********************/


    $("#wppgral-payment-form").on("submit", function (e) {

        e.preventDefault();

        let name = $("#wppgral-name").val();
        let email = $("#wppgral-email").val();
        let amount = $("#wppgral-amount").val();

        /*********************
        STEP 9 : The JavaScript code then creates a configuration object for the Razorpay payment, 
        which includes the API key, amount (converted to paise), currency, name, description, 
        and a handler function that will be called when the payment is completed.
        The handler function sends a POST request to the REST API endpoint with the payment details, including the name, email, amount, and Razorpay payment ID.
        The response from the server is then processed to display a success message or handle any errors that may occur during the payment saving process.
        // Creates configuration object for Razorpay payment 
        **********************/


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
                    
                    // Sends a POST request to the REST API endpoint with the payment details, including the name, email, amount, and Razorpay payment ID. The response from the server is then processed to display a success message or handle any errors that may occur during the payment saving process.
                    // body: JSON.stringify({
                    //         name:"Ami",
                    //         email:"ami@test.com",
                    //         amount:"500",
                    //         payment_id:"pay_ABC123"
                    //    }

                })



               /**********
                STEP 13: browser receives the response from the REST API endpoint after sending the payment details. 
                The response is expected to be in JSON format, which is processed using the .json() method to extract the data for
                further handling. This step is crucial for determining whether the payment details were successfully saved in the database and for providing
                feedback to the user based on the response received from the server.
                
                res.json() converts that JSON response into a JavaScript object.
                ************/


                // Browser Receives Response 
                .then(res => res.json())

                /*
                  Runs after REST success response is received and processes the JSON response from the server. If the payment 
                  details are successfully saved, it displays a success message to the user and resets the payment form.
                  If there is an error during the saving process, 
                  it displays an error message and logs the error to the console for debugging purposes.
                */

                .then(data => {
                //  console.log(data);
                // console.log(data.success);
                // console.log(data.message);

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


        /*********************
        STEP 8 : Razorpay Opens : The JavaScript code initializes the Razorpay payment interface with the above configuration object and opens it to the user to complete the payment. When the user completes the payment, the handler function is triggered, which sends the payment details to the REST API endpoint for saving in the database and processes the response to display appropriate messages to the user.
        **********************/        

        var rzp = new Razorpay(options);

        // Opens the Razorpay payment interface popup for the user to complete the payment. 
        rzp.open();

    });

});