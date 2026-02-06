<?php

namespace wgb\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\helpers\Plugin_Helper;
use wgb\classes\repositories\Rule;
use wgb\classes\repositories\Setting;
use wgb\framework\onboarding\Onboarding;

class Reports_Controller
{
    private $rule_repository;
    private $rule_methods;
    private $page_title;
    private $doc_link;

    public function __construct()
    {
        $this->rule_repository = Rule::get_instance();
        $this->rule_methods = $this->rule_repository->get_rule_methods();

        $this->page_title = esc_html__('GIFTiT – iThemeland Free Gifts for Woo Lite', 'ithemeland-free-gifts-for-woo');
        $this->doc_link = "https://ithemelandco.com/support-center";
    }

    public function index()
    {
        $methods = $this->get_page_methods();
        if (!Onboarding::is_completed()) {
            return $this->activation_page();
        }
        $method = (!empty($_GET['sub-page']) && !empty($methods[$_GET['sub-page']])) ? $methods[$_GET['sub-page']] : 'dashboard'; //phpcs:ignore

        $this->{$method}();
    }

    private function activation_page()
    {
        include_once WGBL_FW_DIR . "onboarding/views/main.php";
    }

    private function get_page_methods()
    {
        return [
            'dashboard' => 'dashboard',
            'rules' => 'rules',
            'orders' => 'orders',
            'customers' => 'customers',
            'products' => 'products',
        ];
    }

    // dashboard page
    private function dashboard()
    {
        $it_brands_is_active = Plugin_Helper::it_brands_is_active();
        $setting_repository = Setting::get_instance();
        $dashboard_date = $setting_repository->get_dashboard_date();

        include_once WGBL_VIEWS_DIR . "reports/dashboard.php";
    }

    // rules page
    private function rules()
    {
        include_once WGBL_VIEWS_DIR . "reports/rules.php";
    }

    // customers page
    private function customers()
    {
        $methods = $this->get_customer_methods();
        $method = (empty($_GET['sub-menu']) || !isset($methods[$_GET['sub-menu']]) || !method_exists($this, $methods[$_GET['sub-menu']])) ? 'usage_customer' : $methods[$_GET['sub-menu']]; //phpcs:ignore
        $this->{$method}();
    }

    private function get_customer_methods()
    {
        return [
            'all-customers' => 'all_customers',
            'used-rules-by-customer' => 'used_rules_by_customer',
        ];
    }

    private function all_customers()
    {
        include_once WGBL_VIEWS_DIR . "reports/all_customers.php";
    }

    private function used_rules_by_customer()
    {
        include_once WGBL_VIEWS_DIR . "reports/used_rules_by_customer.php";
    }

    // orders page
    private function orders()
    {
        include_once WGBL_VIEWS_DIR . "reports/orders.php";
    }

    // gifts/products page
    private function products()
    {
        $methods = $this->get_product_methods();
        $method = (empty($_GET['sub-menu']) || !isset($methods[$_GET['sub-menu']]) || !method_exists($this, $methods[$_GET['sub-menu']])) ? 'all_products' : $methods[$_GET['sub-menu']]; //phpcs:ignore
        $this->{$method}();
    }

    private function get_product_methods()
    {
        return [
            'products' => 'all_products',
            'gotten-gifts-by-customer' => 'gotten_gifts_by_customer',
        ];
    }

    private function all_products()
    {
        include_once WGBL_VIEWS_DIR . "reports/products.php";
    }

    private function gotten_gifts_by_customer()
    {
        include_once WGBL_VIEWS_DIR . "reports/gotten_gifts_by_customer.php";
    }
}
