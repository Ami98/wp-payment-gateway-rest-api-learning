<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
------------------------------------
Add Admin Menu
------------------------------------
*/

add_action('admin_menu', 'wppgral_admin_payment_menu');


function wppgral_admin_payment_menu()
{
    add_menu_page(
        'Payments',
        'WP Payments',
        'manage_options',
        'wppgral-payments',
        'wppgral_payment_page',
        'dashicons-money-alt',
        26
    );
}

function wppgral_payment_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'wppgral_payments';
    $payments = $wpdb->get_results(" SELECT * FROM {$table} ORDER BY id DESC ");

?>

    <div class="wrap">
        <h1> Payments </h1>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Order ID</th>
                    <th>Payment ID</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($payments) : foreach ($payments as $payment) :

                ?>
                        <tr>
                            <td>
                                <?php echo esc_html($payment->id); ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->name);   ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->email); ?>
                            </td>
                            <td>
                                ₹
                                <?php echo esc_html($payment->amount); ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->razorpay_order_id); ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->razorpay_payment_id); ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->payment_status);
                                ?>
                            </td>
                            <td>
                                <?php echo esc_html($payment->created_at); ?>
                            </td>
                        </tr>

                    <?php

                    endforeach;

                else :

                    ?>

                    <tr>
                        <td colspan="8">
                            No payments found
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>
<?php

}
