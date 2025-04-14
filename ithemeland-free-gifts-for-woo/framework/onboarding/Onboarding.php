<?php

namespace wgbl\framework\onboarding;

use wgbl\framework\email_subscription\EmailSubscription;
use wgbl\framework\analytics\AnalyticsTracker;

defined('ABSPATH') || exit();

class Onboarding
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            new self();
        }
    }

    public function __construct()
    {
        add_action('wp_ajax_ithemeland_onboarding_plugin', [$this, 'ithemeland_onboarding_plugin']);
    }

    public function ithemeland_onboarding_plugin()
    {

        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'ithemeland_onboarding_action')) {
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

        $activation_type = sanitize_text_field($_POST['activation_type']);
        $message = __('Error! Please try again.', 'ithemeland-free-gifts-for-woo');


        if ($activation_type === 'skip') {
            $this->update_ithemeland_onboarding_allowed('skipped');

            self::update_opt_in(0);
            self::update_ithemeland_usage_track(0);

            wp_send_json_success([
                'redirect' => WGBL_MAIN_PAGE,
                'message' => __('Activation skipped', 'ithemeland-free-gifts-for-woo')
            ]);
            exit;
        }

        if ($activation_type === 'allow') {

            $opt_in = !empty($_POST['ithemeland_opt_in']);
            $usage_tracking = !empty($_POST['ithemeland_usage_track']);

            self::update_opt_in($opt_in);
            self::update_ithemeland_usage_track($usage_tracking);

            if ($opt_in && class_exists('wgbl\framework\email_subscription\EmailSubscription')) {
                $email_subscription_service = new EmailSubscription();
                $admin_email = get_option('admin_email');
                $info = $email_subscription_service->add_subscription([
                    'email' => $admin_email,
                    'domain' => sanitize_text_field($_SERVER['SERVER_NAME']),
                    'product_id' => 'wgbl',
                    'product_name' => WGBL_LABEL,
                    'industry' => 'wordpress'
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
            $this->update_ithemeland_onboarding_allowed('yes');

            if ($usage_tracking) {
                $tracker = new AnalyticsTracker;
                $tracker->send();
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
        $allowed = get_option('ّithemeland_onboarding_allowed', 'no');
        return ($allowed == 'yes' || $allowed == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('ّithemeland_onboarding_allowed', 'no');

        return $skipped == 'skipped';
    }

    public static function update_opt_in($data)
    {
        update_option('ithemeland_opt_in', $data);
    }
    public static function update_ithemeland_usage_track($data)
    {
        update_option('ithemeland_usage_track', $data);
    }

    public static function update_ithemeland_onboarding_allowed($data)
    {
        update_option('ithemeland_onboarding_allowed', $data);
    }

    public static function get_admin_email()
    {
        return get_option('admin_email');
    }
}
