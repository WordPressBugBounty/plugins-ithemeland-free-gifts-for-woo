<?php

namespace wgbl\classes\controllers;

use wgbl\classes\helpers\Sanitizer;
use wgbl\classes\repositories\Flush_Message;
use wgbl\classes\repositories\Rule;
use wgbl\classes\repositories\Setting;

class WGBL_Post
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_post_wgbl_save_rules', [$this, 'save_rules']);
        add_action('admin_post_wgbl_save_settings_general', [$this, 'save_settings_general']);
        add_action('admin_post_wgbl_save_settings_localization', [$this, 'save_settings_localization']);
    }

    public function save_rules()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wgbl_post_nonce')) {
            die('403 Forbidden');
        }

        $rule_repository = Rule::get_instance();
        $rule_repository->update([
            'items' => Sanitizer::array($_POST['rule']),
            'time' => time(),
        ]);
        $this->redirect('rules', esc_html__('Success !', 'ithemeland-free-gifts-for-woocommerce-lite'));
    }

    public function save_settings_general()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wgbl_post_nonce')) {
            die('403 Forbidden');
        }

        $setting_repository = Setting::get_instance();
        $setting_repository->update(Sanitizer::array($_POST['settings']));
        $this->redirect(['sub_page' => 'general', 'hash' => 'settings'], esc_html__('Success !', 'ithemeland-free-gifts-for-woocommerce-lite'));
    }

    public function save_settings_localization()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wgbl_post_nonce')) {
            die('403 Forbidden');
        }

        if (!empty($_POST['localization']) || is_array($_POST['localization'])) {
            $prefix = "itg_localization_";
            foreach ($_POST['localization'] as $field_name => $field_value) {
                update_option($prefix . sanitize_text_field($field_name), sanitize_text_field($field_value));
            }
        }

        $this->redirect(['sub_page' => 'localization', 'hash' => 'settings'], esc_html__('Success !', 'ithemeland-free-gifts-for-woocommerce-lite'));
    }

    private function redirect($active_tab = null, $message = null)
    {
        $hash = '';
        $sub_page = '';
        if (!is_null($active_tab)) {
            $hash = (is_array($active_tab) && !empty($active_tab['hash'])) ? sanitize_text_field($active_tab['hash']) : $active_tab;
            $sub_page = (is_array($active_tab) && !empty($active_tab['sub_page'])) ? '&sub-page=' . sanitize_text_field($active_tab['sub_page']) : '';
        }

        if (!is_null($message)) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set(['message' => $message, 'hash' => $hash]);
        }

        return wp_redirect(WGBL_MAIN_PAGE . $sub_page . '#' . $hash);
    }
}
