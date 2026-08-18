<?php

namespace ITFreeGift\framework\renew_license_alert;

defined('ABSPATH') || exit();

class RenewLicenseAlert
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if ($this->should_show_alert()) {
            add_action('admin_notices', [$this, 'display_alert']);
            add_action('wp_ajax_wgb_renew_license_alert_dismiss', [$this, 'dismiss']);

            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        }
    }

    private function should_show_alert()
    {
        if (empty(get_option('wgb_egift'))) {
            update_option('wgb_egift', strtotime('+ ' . wp_rand(10, 20) . ' days'));
        }

        $pro_version = get_option('wgb-version');
        if (!empty($pro_version) && version_compare($pro_version, '4.1.0', '>=')) {
            return false;
        }

        return true;
    }

    public function display_alert()
    {
        include_once WGBL_FW_DIR . 'renew_license_alert/views/alert.php';
    }

    public function dismiss()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        // update_option('wgb_renew_license_alert_dismissed', 'yes');
        wp_send_json([
            'success' => true
        ]);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('wgb-renew-license-alert', WGBL_FW_URL . 'renew_license_alert/assets/css/style.css', [], WGBL_VERSION);

        wp_enqueue_script('wgb-renew-license-alert', WGBL_FW_URL . 'renew_license_alert/assets/js/renew-license-alert.js', [], WGBL_VERSION); //phpcs:ignore
        wp_localize_script('wgb-renew-license-alert', 'WGBL_PRO_VERSION_ALERT', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wgb_ajax_nonce'),
        ]);
    }

    public static function remove()
    {
        delete_option('wgb_egift');
        delete_option('wgb_renew_license_alert_dismissed');
    }
}
