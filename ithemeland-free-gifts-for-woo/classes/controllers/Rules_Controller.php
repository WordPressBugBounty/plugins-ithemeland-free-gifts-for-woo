<?php

namespace wgbl\classes\controllers;

use wgbl\classes\repositories\Flush_Message;
use wgbl\classes\repositories\Rule;
use wgbl\classes\repositories\Setting;

class Rules_Controller
{
    private $page_title;
    private $doc_link;

    public function __construct()
    {
        $this->page_title = esc_html__('iThemeland Free Gifts For WooCommerce', 'ithemeland-free-gifts-for-woocommerce-lite');
        $this->doc_link = "https://ithemelandco.com/support-center/";
    }

    public function index()
    {
        $tabs_title = [
            'rules' => esc_html__('Rules', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'settings' => esc_html__('Settings', 'ithemeland-free-gifts-for-woocommerce-lite'),
        ];
        $tabs_content = [
            'rules' => WGBL_VIEWS_DIR . "rules/main.php",
            'settings' => $this->get_setting_view(),
        ];

        $flush_message_repository = new Flush_Message();
        $flush_message = $flush_message_repository->get();

        $rule_repository = Rule::get_instance();
        $rules = $rule_repository->get();
        $option_values = $rule_repository->get_all_options();
        $rule_methods = $rule_repository->get_rule_methods();
        $rule_methods_grouped = $rule_repository->get_rule_methods_grouped();

        $setting_repository = Setting::get_instance();
        $settings = $setting_repository->get();
        $localization = $setting_repository->get_localization();

        include_once WGBL_VIEWS_DIR . "layout/rules.php";
    }

    private function get_setting_view()
    {
        $setting_pages = $this->get_setting_pages();
        $active_page = (!empty($_GET['sub-page'])) ? sanitize_text_field($_GET['sub-page']) : 'general'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return (!empty($setting_pages[$active_page])) ? $setting_pages[$active_page] : '';
    }

    private function get_setting_pages()
    {
        return [
            'general' => WGBL_VIEWS_DIR . 'setting/general.php',
            'localization' => WGBL_VIEWS_DIR . 'setting/localization.php'
        ];
    }
}
