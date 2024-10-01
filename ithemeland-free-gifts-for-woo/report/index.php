<?php

class WC_Customer_History
{
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'plugin_registration_hook'));
    }

    public function plugin_registration_hook()
    {
        if (!function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $result = $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gift_track_report (
                        id              BIGINT(20) NOT NULL AUTO_INCREMENT,
                        product_id      BIGINT(20),
                        rule_id         BIGINT(20),
                        user_id         BIGINT(20),
                        order_id         BIGINT(20),
                        reg_date        TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY     (id)
                    ) $charset_collate"); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

    }
}
