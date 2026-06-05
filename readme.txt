
. WordPress Plugin Development
. Custom Database Table
. WordPress REST API
. Razorpay Test Mode
. JavaScript Fetch API
. Payment Data Storage

What You'll Build
User Form
   ↓
AJAX / REST API
   ↓
Create Order
   ↓
Open Razorpay Checkout
   ↓
Payment Success
   ↓
Verify Payment
   ↓
Save in Custom Table
   ↓
Show Success Message

Next Improvement

For a more realistic implementation, the next version should:

Create a Razorpay Order on the server.
Verify the Razorpay signature in PHP.
Use a WordPress nonce.
Add an admin menu to view payments.
Store API keys in a settings page.


Actual Runtime Sequence:

Plugin Activated
   ↓
wppgral_create_table()

User Opens Page
   ↓
wp_enqueue_scripts
   ↓
wppgral_enqueue_scripts()

Shortcode Found
   ↓
wppgral_payment_form_shortcode()

Browser Loads JS
   ↓
payment.js

User Clicks Pay
   ↓
Razorpay Popup

Payment Success
   ↓
handler()

fetch()
   ↓
REST API Route

register_rest_route()
   ↓
wppgral_save_payment()

$wpdb->insert()

REST Response
   ↓
JS .then()

Payment Successful