<?php

namespace wgbl\framework\onboarding;

use wgbl\framework\active_plugins\ActivePlugins;
use wgbl\framework\analytics\AnalyticsService;
use wgbl\framework\email_subscription\EmailSubscription;

defined('ABSPATH') || exit();

class Onboarding
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public function __construct()
    {
        add_action('wp_ajax_wgbl_ithemeland_onboarding_plugin', [$this, 'onboarding_action']);
    }

    public function onboarding_action()
    {
        // Verify nonce
        if (
            !isset($_POST['_wpnonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'ithemeland_onboarding_action')
        ) {
            wp_send_json_error([
                'message' => __('Security verification failed', 'ithemeland-free-gifts-for-woo')
            ], 403);
            exit;
        }

        // Check activation type
        if (!isset($_POST['activation_type'])) {
            wp_send_json_error([
                'message' => __('Invalid request', 'ithemeland-free-gifts-for-woo')
            ], 400);
            exit;
        }

        $activation_type = sanitize_text_field(wp_unslash($_POST['activation_type']));
        $message = __('Error! Please try again.', 'ithemeland-free-gifts-for-woo');

        if ($activation_type === 'skip') {
            self::update_opt_in('no');
            self::update_usage_track('no');
            self::onboarding_complete('skipped');
            wp_send_json_success([
                'redirect' => WGBL_MAIN_PAGE,
                'message' => __('Activation skipped', 'ithemeland-free-gifts-for-woo')
            ]);
            exit;
        }

        if ($activation_type === 'allow') {
            $opt_in = !empty($_POST['ithemeland_opt_in']) ? 'yes' : 'no';
            $usage_tracking = !empty($_POST['ithemeland_usage_track']) ? 'yes' : 'no';

            self::update_opt_in($opt_in);
            self::update_usage_track($usage_tracking);

            if ($opt_in == 'yes' && class_exists('wgbl\framework\email_subscription\EmailSubscription')) {
                ActivePlugins::update('wgbl', 'gift:free');
                $email_subscription_service = new EmailSubscription();
                $admin_email = get_option('admin_email');
                $info = $email_subscription_service->add_subscription([
                    'email' => sanitize_email($admin_email),
                    'domain' => isset($_SERVER['SERVER_NAME']) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME'])) : '',
                    'product_id' => 'wgbl',
                    'product_name' => WGBL_LABEL
                ]);

                if (is_array($info)) {
                    if (!empty($info['success']) && $info['success'] === true) {
                        update_option('ithemeland_activation_email', $admin_email);
                        $message = __('Plugin activated successfully!', 'ithemeland-free-gifts-for-woo');
                    } else {
                        $message = $info['message'] ?? __('Activation failed', 'ithemeland-free-gifts-for-woo');
                        wp_send_json_error(['message' => $message], 400);
                        exit;
                    }
                }
            }

            self::onboarding_complete('allowed');

            if ($usage_tracking == 'yes') {
                $analytics_service = AnalyticsService::get_instance();
                $analytics_service->send();
            }

            wp_send_json_success([
                'message' => $message,
                'redirect' => WGBL_MAIN_PAGE
            ]);
            exit;
        }

        wp_send_json_error(['message' => $message], 400);
        exit;
    }

    public static function is_completed()
    {
        return (get_option('wgb_onboarding', 'no') != 'no');
    }

    public static function update_opt_in($data)
    {
        update_option('wgb_opt_in', sanitize_text_field($data));
    }

    public static function update_usage_track($data)
    {
        update_option('wgb_usage_track', sanitize_text_field($data));
    }

    public static function onboarding_complete($data)
    {
        update_option('wgb_onboarding', sanitize_text_field($data));
    }

    public static function opt_in_is_allowed()
    {
        return get_option('wgb_opt_in', 'no') == 'yes';
    }

    public static function usage_track_is_allowed()
    {
        return get_option('wgb_usage_track', 'no') == 'yes';
    }
}
