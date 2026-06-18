<?php

if (!defined('ABSPATH')) {
    exit;
}


add_action(

    'admin_menu',

    'wppgral_logs_menu'

);


function wppgral_logs_menu()
{

    add_submenu_page(

        'wppgral-payments',

        'Payment Logs',

        'Logs',

        'manage_options',

        'wppgral-logs',

        'wppgral_logs_page'

    );
}

function wppgral_logs_page()
{

    global $wpdb;

    $table =

        $wpdb->prefix .

        'wppgral_logs';


    $logs =

        $wpdb->get_results(

            "

SELECT *

FROM {$table}

ORDER BY id DESC

"

        );

?>

    <div class="wrap">

        <h1>

            Payment Logs

        </h1>


        <table class="widefat striped">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Order ID</th>

                    <th>Event</th>

                    <th>Message</th>

                    <th>Date</th>

                </tr>

            </thead>


            <tbody>

                <?php

                foreach (

                    $logs

                    as

                    $log

                ) :

                ?>

                    <tr>

                        <td>

                            <?php

                            echo esc_html(

                                $log->id

                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo esc_html(

                                $log->razorpay_order_id

                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo esc_html(

                                $log->event_name

                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo esc_html(

                                $log->message

                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo esc_html(

                                $log->created_at

                            );

                            ?>

                        </td>

                    </tr>

                <?php

                endforeach;

                ?>

            </tbody>

        </table>

    </div>

<?php

}
