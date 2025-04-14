<?php

namespace wgbl\framework\analytics;

defined('ABSPATH') || exit();

class AnalyticsTracker
{
    private $service_url = "http://usage-tracking.ithemelandco.com/index.php";
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public function __construct()
    {
        add_action('admin_init', [$this, 'ithemeland_analytics_check_field']);
        add_action('init', [$this, 'schedule_weekly_analytics']);
    }

    public function schedule_weekly_analytics()
    {
        $transient_name = 'ithemeland_wgbl_analytics_send';

        if (false === get_transient($transient_name)) {
            $this->send();
            set_transient($transient_name, 'sent', WEEK_IN_SECONDS);
        }
    }

    public static function ithemeland_analytics_check_field($fields)
    {
        if (false === get_option('ithemeland_usage_track')) {
            update_option('ithemeland_usage_track', 1);
        }

        register_setting(
            'general',
            'ithemeland_usage_track',
            array(
                'type' => 'boolean',
                'sanitize_callback' => [__CLASS__, 'usage_sanitize_checkbox'],
                'default' => 1
            )
        );

        add_settings_field(
            'ithemeland_usage_track',
            __('Enable Usage Tracking', 'ithemeland-free-gifts-for-woo'),
            [__CLASS__, 'ithemeland_usage_tracking_checkbox'],
            'general',
            'default',
            array(
                'label_for' => 'ithemeland_usage_track_general',
                'description' => __('Allow anonymous usage data tracking to help improve our plugin.', 'ithemeland-free-gifts-for-woo')
            )
        );
    }

    public static function usage_sanitize_checkbox($input)
    {
        return (isset($input) && $input == '1') ? 1 : 0;
    }

    public static function ithemeland_usage_tracking_checkbox($args)
    {
        $option = get_option('ithemeland_usage_track');
        $description = $args['description'] ?? '';

        include WGBL_FW_DIR . 'analytics/views/usage_track_checkbox.php';
    }

    public function send()
    {

        if (get_option('ithemeland_usage_track') != 1 || get_option('ithemeland_onboarding_allowed') !== 'yes') {
            return;
        }

        $analytics_stats = AnalyticsStats::instance();
        $stats = $analytics_stats->get_stats();

        $stats['service'] = 'analytics';

        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'method' => 'POST',
            'timeout' => '45',
            'httpversion' => '1.0',
            'body' => $stats
        ]);

        if (!is_wp_error($response)) {

            set_transient('ithemeland_wgbl_analytics_send', true, WEEK_IN_SECONDS);
        } else {
            error_log('ithemeland Analytics Error: ' . $response->get_error_message());
        }
    }
}
