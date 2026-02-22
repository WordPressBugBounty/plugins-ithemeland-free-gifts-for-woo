<?php

namespace wgb\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\languages\WGBL_Language;
use wgb\classes\repositories\Flush_Message;
use wgb\classes\repositories\OfferRule;
use wgb\classes\repositories\Rule;
use wgb\classes\repositories\Setting;
use wgb\classes\repositories\User;
use wgb\framework\onboarding\Onboarding;

class Rules_Controller
{
    private $page_title;
    private $doc_link;

    public function __construct()
    {
        $this->page_title = esc_html__('GIFTiT – iThemeland Free Gifts for Woo Lite', 'ithemeland-free-gifts-for-woo');
        $this->doc_link = "https://ithemelandco.com/support-center";
    }

    public function index()
    {
        if (!isset($_GET['tab'])) { //phpcs:ignore
            $_GET['tab'] = 'rules'; //phpcs:ignore
        }

        $tabs_title = apply_filters('wgb_rules_main_tabs_title', [
            'rules' => esc_html__('Rules', 'ithemeland-free-gifts-for-woo'),
            'settings' => esc_html__('Settings', 'ithemeland-free-gifts-for-woo'),
            'shortcodes' => esc_html__('Shortcodes', 'ithemeland-free-gifts-for-woo'),
            'reports' => esc_html__('Reports', 'ithemeland-free-gifts-for-woo'),
        ]);
        $tabs_content = apply_filters('wgb_rules_main_tabs_content', [
            'rules' => WGBL_VIEWS_DIR . "rules/main.php",
            'settings' => $this->get_setting_view(),
            'shortcodes' => WGBL_VIEWS_DIR . "rules/shortcodes.php",
            'shortcodes' => WGBL_VIEWS_DIR . "rules/shortcodes.php",
        ]);

        switch ($_GET['tab']) { //phpcs:ignore
            case 'rules':
                $this->rules();
                break;
            case 'settings':
                $this->settings();
                break;
            case 'shortcodes':
                $this->shortcodes();
                break;
            case 'reports':
                $this->reports();
                break;
            case 'offer_rules':
                $this->offer_rules();
                break;
            default:
                $_GET['tab'] = 'rules'; //phpcs:ignore
                $this->rules();
        }
    }

    private function rules()
    {
        $flush_message_repository = new Flush_Message();
        if (!Onboarding::is_completed()) {
            return $this->activation_page();
        }
        $flush_message = $flush_message_repository->get();
        $rule_repository = Rule::get_instance();
        $rules = $rule_repository->get();
        $option_values = $rule_repository->get_all_options();
        $user_repository = new User();
        $option_values['user_roles'] = $user_repository->get_user_roles();
        $option_values['user_capabilities'] = $user_repository->get_user_capabilities();
        // Populate brands for selected brand IDs in all rules/conditions
        if (!empty($rules['items'])) {
            foreach ($rules['items'] as $rule_item) {
                if (!empty($rule_item['condition']) && is_array($rule_item['condition'])) {
                    foreach ($rule_item['condition'] as $condition) {
                        if (!empty($condition['brands']) && is_array($condition['brands'])) {
                            if (!isset($option_values['brands'])) {
                                $option_values['brands'] = [];
                            }
                            $terms = get_terms([
                                'taxonomy' => 'product_brand',
                                'include' => $condition['brands'],
                                'hide_empty' => false,
                            ]);
                            if (!is_wp_error($terms)) {
                                foreach ($terms as $term) {
                                    $option_values['brands'][$term->term_id] = $term->name;
                                }
                            }
                        }
                    }
                }
            }
        }
        $rule_methods = $rule_repository->get_rule_methods();
        $rule_methods_grouped = $rule_repository->get_rule_methods_grouped();
        $wgb_language = WGBL_Language::get_instance();
        $site_languages = $wgb_language->get_languages();
        $shipping_methods_options = $rule_repository->get_shipping_methods_options();

        include_once WGBL_VIEWS_DIR . "rules/main.php";
    }

    private function activation_page()
    {
        include_once WGBL_FW_DIR . "onboarding/views/main.php";
    }

    private function settings()
    {
        if (!isset($_GET['sub-tab'])) { //phpcs:ignore
            $_GET['sub-tab'] = 'general'; //phpcs:ignore
        }

        $settings_tabs_title = apply_filters('wgb_rules_settings_tabs_title', [
            'general' => esc_html__('General', 'ithemeland-free-gifts-for-woo'),
            'display' => esc_html__('Display', 'ithemeland-free-gifts-for-woo'),
            'localization' => esc_html__('Localization', 'ithemeland-free-gifts-for-woo'),
            'promotion' => esc_html__('Promotion', 'ithemeland-free-gifts-for-woo')
        ]);

        $flush_message_repository = new Flush_Message();
        $flush_message = $flush_message_repository->get();
        $setting_repository = Setting::get_instance();
        $settings = $setting_repository->get();
        $localization = $setting_repository->get_localization();

        switch ($_GET['sub-tab']) { //phpcs:ignore
            case 'general':
                include_once WGBL_VIEWS_DIR . "settings/general.php";
                break;
            case 'display':
                include_once WGBL_VIEWS_DIR . "settings/display.php";
                break;
            case 'localization':
                include_once WGBL_VIEWS_DIR . "settings/localization.php";
                break;
            case 'promotion':
                include_once WGBL_VIEWS_DIR . "settings/promotion.php";
                break;
            default:
                include_once WGBL_VIEWS_DIR . "settings/general.php";
        }
    }

    private function shortcodes()
    {
        include_once WGBL_VIEWS_DIR . "shortcodes/main.php";
    }

    private function reports()
    {
        $reports_controller = new Reports_Controller();
        $reports_controller->index();
    }

    private function offer_rules()
    {
        $flush_message_repository = new Flush_Message();
        $flush_message = $flush_message_repository->get();

        $offer_rule_repository = OfferRule::get_instance();
        $rules = $offer_rule_repository->get_rules();
        $rule_types = $offer_rule_repository->get_rule_types();
        $option_values = $offer_rule_repository->get_option_values();

        include_once WGBL_VIEWS_DIR . "offer_rules/main.php";
    }

    private function get_setting_view()
    {
        $setting_pages = $this->get_setting_pages();
        $active_page = (!empty($_GET['sub-page'])) ? sanitize_text_field($_GET['sub-page']) : 'general'; //phpcs:ignore
        return (!empty($setting_pages[$active_page])) ? $setting_pages[$active_page] : '';
    }

    private function get_setting_pages()
    {
        return apply_filters('wgb_rules_settings_tabs_content', [
            'general' => WGBL_VIEWS_DIR . 'setting/general.php',
            'notification' => WGBL_VIEWS_DIR . 'setting/display.php',
            'localization' => WGBL_VIEWS_DIR . 'setting/localization.php',
            'promotion' => WGBL_VIEWS_DIR . 'settings/promotion.php'
        ]);
    }
}
