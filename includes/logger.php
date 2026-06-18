<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
------------------------------------
Save Log
------------------------------------
*/

function wppgral_log($order_id, $event, $message)
{
    global $wpdb;
    $table = $wpdb->prefix . 'wppgral_logs';

    $wpdb->insert(
        $table,
        [
            'razorpay_order_id' => $order_id,
            'event_name' => $event,
            'message' => $message
        ]
    );
}
