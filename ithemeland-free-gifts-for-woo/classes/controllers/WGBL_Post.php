<?php

namespace ITFreeGift\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use ITFreeGift\classes\helpers\Sanitizer;
use ITFreeGift\classes\repositories\Flush_Message;
use ITFreeGift\classes\repositories\OfferRule;
use ITFreeGift\classes\repositories\Rule;
use ITFreeGift\classes\repositories\Setting;

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
        add_action('admin_post_wgb_save_rules', [$this, 'save_rules']);
        add_action('admin_post_wgb_save_offer_rules', [$this, 'save_offer_rules']);
        add_action('admin_post_wgb_save_settings_general', [$this, 'save_settings_general']);
        add_action('admin_post_wgb_save_settings_localization', [$this, 'save_settings_localization']);
        add_action('admin_post_wgb_save_settings_cart_page', [$this, 'save_settings_cart_page']);
        add_action('admin_post_wgb_save_settings_checkout_page', [$this, 'save_settings_checkout_page']);
        add_action('admin_post_wgb_save_settings_notification', [$this, 'save_settings_notification']);
        add_action('admin_post_wgb_save_settings_promotion', [$this, 'save_settings_promotion']);
        add_action('admin_post_wgb_addons_requests', [$this, 'addons_requests']);
    }

    public function save_offer_rules()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wgb_post_nonce')) {
            die('403 Forbidden');
        }

        $option_values = (!empty($_POST['option_values'])) ? json_decode(sanitize_text_field(wp_unslash($_POST['option_values'])), true) : [];
        if (empty($option_values) && !is_array($option_values)) {
            $this->redirect('rules', [
                'message' => esc_html__('Error', 'ithemeland-free-gifts-for-woo'),
                'color' => 'red'
            ]);
        }

        $rule_repository = OfferRule::get_instance();
        $rule_repository->update([
            'items' => (!empty($_POST['rule'])) ? Sanitizer::array($_POST['rule']) : [], //phpcs:ignore
            'option_values' => Sanitizer::array($option_values),
            'time' => time(),
        ]);

        $this->redirect('offer_rules', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_rules()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wgb_post_nonce')) {
            die('403 Forbidden');
        }

        $option_values = json_decode(wp_unslash($_POST['option_values']), true);  //phpcs:ignore
        if (empty($option_values) && !is_array($option_values)) {
            $this->redirect('rules', [
                'message' => esc_html__('Error', 'ithemeland-free-gifts-for-woo'),
                'color' => 'red'
            ]);
        }

        $rule_repository = Rule::get_instance();
        $rule_repository->update([
            'items' => (!empty($_POST['rule'])) ? Sanitizer::array($_POST['rule']) : [], //phpcs:ignore
            'option_values' => Sanitizer::array($option_values),
            'time' => time(),
        ]);

        $this->redirect('rules', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_general()
    {
        $this->verify_settings_nonce();
        $this->save_posted_settings(['popup_pages']);

        $this->redirect('settings&sub-tab=general', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_localization()
    {
        $this->verify_settings_nonce();
        $this->save_posted_localization();

        $this->redirect('settings&sub-tab=localization', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_cart_page()
    {
        $this->verify_settings_nonce();
        $this->save_posted_settings();
        $this->save_posted_localization();

        $this->redirect('settings&sub-tab=cart_page', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_checkout_page()
    {
        $this->verify_settings_nonce();
        $this->save_posted_settings();
        $this->save_posted_localization();

        $this->redirect('settings&sub-tab=checkout_page', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_notification()
    {
        $this->verify_settings_nonce();
        $this->save_posted_settings();
        $this->save_posted_localization();

        $this->redirect('settings&sub-tab=display', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function save_settings_promotion()
    {
        $this->verify_settings_nonce();
        $this->save_posted_settings(['promotion_pages']);
        $this->save_posted_localization();

        $this->redirect('settings&sub-tab=promotion', [
            'message' => esc_html__('Your changes have been successfully saved.', 'ithemeland-free-gifts-for-woo'),
            'color' => 'green'
        ]);
    }

    public function addons_requests()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wgb_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['activate'])) {
            activate_plugin(sanitize_text_field($_POST['activate'])); //phpcs:ignore
        }

        if (isset($_POST['deactivate'])) {
            deactivate_plugins(sanitize_text_field($_POST['deactivate'])); //phpcs:ignore
        }

        $this->redirect('rules');
    }

    private function verify_settings_nonce()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wgb_post_nonce')) {
            die('403 Forbidden');
        }
    }

    private function save_posted_settings($empty_array_fields = [])
    {
        if (empty($_POST['settings']) || !is_array($_POST['settings'])) { //phpcs:ignore
            return;
        }

        $posted_settings = Sanitizer::array($_POST['settings']); //phpcs:ignore
        if (!is_array($posted_settings)) {
            return;
        }

        // Multi-select fields are omitted from the request when all options are
        // deselected. Store an empty array so their previous values are cleared.
        foreach ($empty_array_fields as $field_name) {
            if (!isset($_POST['settings'][$field_name])) { //phpcs:ignore
                $posted_settings[$field_name] = [];
            }
        }

        Setting::get_instance()->update_general_settings($posted_settings);
    }

    private function save_posted_localization()
    {
        if (empty($_POST['localization']) || !is_array($_POST['localization'])) { //phpcs:ignore
            return;
        }

        foreach (Sanitizer::array($_POST['localization']) as $field_name => $field_value) { //phpcs:ignore
            update_option('itg_localization_' . sanitize_key($field_name), sanitize_text_field($field_value));
        }

        if (function_exists('itfreegift_reset_localization_cache')) {
            itfreegift_reset_localization_cache();
        }
    }

    private function redirect($active_tab = null, $message = null)
    {
        if (empty($active_tab)) {
            $active_tab = 'rules';
        }
        if (!empty($message['message'])) {
            $flush_message_repository = new Flush_Message();
            $params = [
                'message' => $message['message'],
                'color' => (!empty($message['color'])) ? $message['color'] : 'green',
            ];
            $flush_message_repository->set($params);
        }

        return wp_safe_redirect(WGBL_MAIN_PAGE . '&tab=' . $active_tab);
    }
}
