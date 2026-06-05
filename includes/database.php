<?php

if (!defined('ABSPATH')) {
    exit;
}

function wppgral_create_table()
{

    global $wpdb;

    $table = $wpdb->prefix . 'wppgral_payments';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        payment_id VARCHAR(255) NOT NULL,
        status VARCHAR(50) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta($sql);
}
