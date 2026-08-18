<?php

namespace ITFreeGift\framework\pro_version_alert;

defined('ABSPATH') || exit();

class ProVersionAlert
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
            add_action('wp_ajax_wgb_pro_version_alert_dismiss', [$this, 'dismiss']);

            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        }
    }

    private function should_show_alert()
    {
        return true;
    }

    public function display_alert()
    {
        $dismissed = get_option('wgb_pro_version_alert_dismissed', false);
        if (empty($dismissed)) {
            include_once WGBL_FW_DIR . 'pro_version_alert/views/alert.php';
        }
    }

    public function dismiss()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        update_option('wgb_pro_version_alert_dismissed', 'yes');
        wp_send_json([
            'success' => true
        ]);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('wgb-pro-version-alert', WGBL_FW_URL . 'pro_version_alert/assets/css/style.css', [], WGBL_VERSION);

        wp_enqueue_script('wgb-pro-version-alert', WGBL_FW_URL . 'pro_version_alert/assets/js/pro-version-alert.js', [], WGBL_VERSION); //phpcs:ignore
        wp_localize_script('wgb-pro-version-alert', 'WGBL_PRO_VERSION_ALERT', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wgb_ajax_nonce'),
        ]);
    }
}
