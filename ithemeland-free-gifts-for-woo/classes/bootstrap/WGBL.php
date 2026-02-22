<?php

namespace wgb\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\frontend\blocks\WGBL_Blocks;
use wgb\classes\api\Api_Handler;
use wgb\classes\controllers\Rules_Controller;
use wgb\classes\controllers\WGBL_Ajax;
use wgb\classes\controllers\WGBL_Post;
use wgb\classes\languages\WGBL_Language;
use wgb\classes\repositories\OfferRule;
use wgb\classes\repositories\Order;
use wgb\classes\repositories\Rule;
use wgb\classes\repositories\Setting;
use wgb\classes\services\render\Condition_Render;
use wgb\classes\services\render\Product_Buy_Render;
use wgb\framework\analytics\AnalyticsTracker;
use wgb\framework\onboarding\Onboarding;

class WGBL
{
    private static $instance;
    private static $is_initable;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'display';
            return $styles;
        });

        WGBL_Blocks::init();

        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_menu']);
            AnalyticsTracker::register();
            Onboarding::register();
        }

        Api_Handler::init();
        WGBL_Ajax::register_callback();
        WGBL_Post::register_callback();
        (new WGBL_Custom_Queries())->init();

        $settings_repository = Setting::get_instance();
        if (!$settings_repository->has_settings()) {
            $settings_repository->set_default_settings();
        }
    }

    public static function woocommerce_required()
    {
        include WGBL_VIEWS_DIR . 'alerts/woocommerce_required.php';
    }

    public function add_menu()
    {
        add_menu_page(esc_html__('GIFTiT', 'ithemeland-free-gifts-for-woo'), esc_html__('GIFTiT', 'ithemeland-free-gifts-for-woo'), 'manage_woocommerce', 'wgb', [new Rules_Controller, 'index'], WGBL_IMAGES_URL . 'giftit-icon-wh20.svg', 59);
        add_submenu_page('wgb', esc_html__('Rules | Settings', 'ithemeland-free-gifts-for-woo'), esc_html__('Rules | Settings', 'ithemeland-free-gifts-for-woo'), 'manage_woocommerce', 'wgb');
    }

    public static function wgb_wp_init()
    {
        if (!self::is_initable()) {
            return false;
        }

        $version = get_option('wgbl-version');
        if (empty($version) || $version != WGBL_VERSION) {

            $rule_repository = Rule::get_instance();
            $rule_repository->maybe_sync();

            $setting_repository = Setting::get_instance();
            $setting_repository->maybe_sync();

            update_option('wgbl-version', WGBL_VERSION);
        }

        if (function_exists('determine_locale')) {
            $locale = determine_locale();
        } else {
            // @todo Remove when start supporting WP 5.0 or later.
            $locale = is_admin() ? get_user_locale() : get_locale();
        }
        /**
         * This hook is used to alter the plugin locale.
         * 
         * @since 1.0
         */
        $locale = apply_filters('plugin_locale', $locale, 'ithemeland-free-gifts-for-woo');

        // Unload the text domain if other plugins/themes loaded the same text domain by mistake.
        unload_textdomain('ithemeland-free-gifts-for-woo');
        // Load the text domain from the "wp-content" languages folder. we have handles the plugin folder in languages folder for easily handle it.
        load_textdomain('ithemeland-free-gifts-for-woo', WP_LANG_DIR . '/ithemeland-free-gifts-for-woo/ithemeland-free-gifts-for-woo-' . $locale . '.mo');
        // Load the text domain from the current plugin languages folder.
        // load_plugin_textdomain('ithemeland-free-gifts-for-woo', false, WGBL_LANGUAGES_DIR);
    }

    private function main_load_assets()
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wgb') { //phpcs:ignore
            $rule_repository = Rule::get_instance();
            // Styles
            wp_enqueue_style('wgb-reset', WGBL_CSS_URL . 'common/reset.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-LineIcons', WGBL_CSS_URL . 'common/LineIcons.min.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-select2', WGBL_CSS_URL . 'common/select2.min.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-tipsy', WGBL_CSS_URL . 'common/jquery.tipsy.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-sweetalert', WGBL_CSS_URL . 'common/sweetalert.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-datetimepicker', WGBL_CSS_URL . 'common/jquery.datetimepicker.css', [], WGBL_VERSION);
            wp_enqueue_style('wgb-main', WGBL_CSS_URL . 'common/style.css', [], WGBL_VERSION);

            // Scripts
            wp_enqueue_script('wgb-functions', WGBL_JS_URL . 'common/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_enqueue_script('wgb-tipsy', WGBL_JS_URL . 'common/jquery.tipsy.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_enqueue_script('wgb-datetimepicker', WGBL_JS_URL . 'common/jquery.datetimepicker.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_enqueue_script('wgb-select2', WGBL_JS_URL . 'common/select2.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_enqueue_script('wgb-sweetalert', WGBL_JS_URL . 'common/sweetalert.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_enqueue_script('wgb-main', WGBL_JS_URL . 'common/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
            wp_localize_script('wgb-main', 'WGBL_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('wgb_ajax_nonce'),
                'ruleMethods' => $rule_repository->get_rule_methods(),
                'translate' => [
                    'quantities_and_settings' => esc_html__('Quantities & Settings', 'ithemeland-free-gifts-for-woo'),
                    'select_shipping' => esc_html__('Select shipping', 'ithemeland-free-gifts-for-woo'),
                ],
                'loadingImage' => '<span class="wgb-button-loading"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="34px" height="34px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="17.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate></rect><rect x="42.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate></rect><rect x="67.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate></rect></svg></span>',
            ]);

            // load assets for rules | settings 
            if (empty($_GET['tab']) || (!empty($_GET['tab']) && in_array($_GET['tab'], ['rules', 'settings', 'shortcodes']))) { //phpcs:ignore
                // Styles
                wp_enqueue_style('wgb-rules-main', WGBL_CSS_URL . 'rules/style.css', [], WGBL_VERSION);

                // Scripts
                wp_enqueue_script('wgb-rules-form-conditions', WGBL_JS_URL . 'rules/form_data/common/conditions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-form-get', WGBL_JS_URL . 'rules/form_data/common/get.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-form-products_buy', WGBL_JS_URL . 'rules/form_data/common/products_buy.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-form-quantities', WGBL_JS_URL . 'rules/form_data/common/quantities.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-form-methods', WGBL_JS_URL . 'rules/form_data/methods.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-functions', WGBL_JS_URL . 'rules/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-rules-main', WGBL_JS_URL . 'rules/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_localize_script('wgb-rules-main', 'WGBL_RULES_DATA', $this->get_rules_js_data());
                wp_enqueue_script('jquery-ui-sortable');
            }

            // load assets for reports
            if (!empty($_GET['tab']) && $_GET['tab'] == 'reports') { //phpcs:ignore
                // Styles
                wp_enqueue_style('wgb-reports-bootstrap', WGBL_CSS_URL . 'common/bootstrap.dataTables.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-reports-datatable', WGBL_CSS_URL . 'common/dataTables.bootstrap4.min.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-reports-daterangepicker', WGBL_CSS_URL . 'common/daterangepicker.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-reports-main', WGBL_CSS_URL . 'reports/style.css', [], WGBL_VERSION);

                // Scripts
                wp_enqueue_script('moment');
                wp_enqueue_script('wgb-chartjs', WGBL_JS_URL . 'common/chartjs/chart.umd.min.js', [], WGBL_VERSION, true);
                wp_enqueue_script('wgb-chartjs-adapter-date-fns', WGBL_JS_URL . 'common/chartjs/chartjs-adapter-date-fns.js', ['wgb-chartjs'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-datatable', WGBL_JS_URL . 'common/jquery.dataTables.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-datatable-buttons', WGBL_JS_URL . 'common/dataTables.buttons.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-pdfmake', WGBL_JS_URL . 'common/pdfmake.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-jszip', WGBL_JS_URL . 'common/jszip.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-vfs-fonts', WGBL_JS_URL . 'common/vfs_fonts.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-buttons-html5', WGBL_JS_URL . 'common/buttons.html5.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-buttons-print', WGBL_JS_URL . 'common/buttons.print.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-buttons-flash', WGBL_JS_URL . 'common/buttons.flash.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-daterangepicker', WGBL_JS_URL . 'common/daterangepicker.min.js', ['jquery', 'moment'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-functions', WGBL_JS_URL . 'reports/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-reports-main', WGBL_JS_URL . 'reports/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore

                wp_localize_script('wgb-reports-main', 'WGBL_REPORTS_DATA', $this->get_reports_js_data());
            }

            // load assets for addons
            if (!empty($_GET['page']) && $_GET['page'] == 'wgb-addons') { //phpcs:ignore
                wp_enqueue_style('wgb-reset', WGBL_CSS_URL . 'common/reset.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-LineIcons', WGBL_CSS_URL . 'common/LineIcons.min.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-tipsy', WGBL_CSS_URL . 'common/jquery.tipsy.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-sweetalert', WGBL_CSS_URL . 'common/sweetalert.css', [], WGBL_VERSION);
                wp_enqueue_style('wgb-addons', WGBL_CSS_URL . 'addons/style.css', [], WGBL_VERSION);

                // Scripts
                wp_enqueue_script('wgb-functions', WGBL_JS_URL . 'common/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-tipsy', WGBL_JS_URL . 'common/jquery.tipsy.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-sweetalert', WGBL_JS_URL . 'common/sweetalert.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-main', WGBL_JS_URL . 'common/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_localize_script('wgb-main', 'WGBL_DATA', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'wp_nonce' => wp_create_nonce(),
                    'loadingImage' => '<span class="wgb-button-loading"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="34px" height="34px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="17.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate></rect><rect x="42.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate></rect><rect x="67.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate></rect></svg></span>',
                ]);
            }

            if (!empty($_GET['tab']) && $_GET['tab'] == 'offer_rules') { //phpcs:ignore
                wp_enqueue_style('wgb-offer-rules-main', WGBL_CSS_URL . 'offer_rules/style.css', [], WGBL_VERSION);

                wp_enqueue_script('wgb-offer-rules-functions', WGBL_JS_URL . 'offer_rules/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-offer-rules-conditions', WGBL_JS_URL . 'offer_rules/conditions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_enqueue_script('wgb-offer-rules-main', WGBL_JS_URL . 'offer_rules/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
                wp_localize_script('wgb-offer-rules-main', 'WGBL_OFFER_RULES_DATA', $this->get_offer_rules_js_data());
                wp_enqueue_script('jquery-ui-sortable');
            }

            wp_enqueue_style('wgb-responsive', WGBL_CSS_URL . 'common/responsive.css', [], WGBL_VERSION);
        }
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && in_array($_GET['page'], ['wgb', 'wgb-reports'])) { //phpcs:ignore
            if (Onboarding::is_completed()) {
                $this->main_load_assets();
            } else {
                $this->activation_load_assets();
            }
        }
    }

    private function activation_load_assets()
    {
        wp_enqueue_style('wgb-reset', WGBL_CSS_URL . 'common/reset.css', [], WGBL_VERSION);
        wp_enqueue_style('wgb-sweetalert', WGBL_CSS_URL . 'common/sweetalert.css', [], WGBL_VERSION);
        wp_enqueue_style('wgb-onboarding', WGBL_FW_URL . 'onboarding/assets/css/onboarding.css', [], WGBL_VERSION);

        wp_enqueue_script('wgb-sweetalert', WGBL_JS_URL . 'common/sweetalert.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore
        wp_enqueue_script('wgb-onboarding', WGBL_FW_URL . 'onboarding/assets/js/onboarding.js', ['jquery'], WGBL_VERSION); //phpcs:ignore

        wp_localize_script('wgb-onboarding', 'ithemeland_onboarding', [
            'nonce' => wp_create_nonce('ithemeland_onboarding_action'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'redirecting_text' => esc_html__('Redirecting...', 'ithemeland-free-gifts-for-woo'),
            'skip_text' => esc_html__('Skip', 'ithemeland-free-gifts-for-woo')
        ]);
    }

    public static function wp_loaded()
    {
        if (!self::is_initable()) {
            return false;
        }
    }

    public static function activate()
    {
        $cart_page_id = get_option('woocommerce_cart_page_id');
        $checkout_page_id = get_option('woocommerce_checkout_page_id');

        if ($cart_page_id) {
            $post = get_post($cart_page_id);
            if ($post) {
                if (strpos($post->post_content, 'wp-block-wgb-wc-gift') === false && strpos($post->post_content, '<!-- wp:woocommerce/cart -->') !== false) {
                    $pattern = '/(<\/div>\s*<!--\s*\/wp:woocommerce\/cart-items-block\s*-->)/i';

                    $wgb_block = <<<HTML
                        \n<!-- wp:wgb/wc-gift -->
                        <div class="wp-block-wgb-wc-gift"></div>
                        <!-- /wp:wgb/wc-gift -->\n
                    HTML;

                    if (preg_match($pattern, $post->post_content)) {
                        $new_content = preg_replace($pattern, $wgb_block . '$1', $post->post_content, 1);
                    } else {
                        $new_content = $post->post_content . $wgb_block;
                    }

                    if ($new_content !== $post->post_content) {
                        wp_update_post(array(
                            'ID' => $post->ID,
                            'post_content' => $new_content,
                        ));
                    }
                }
            }
        }

        if ($checkout_page_id) {
            $post = get_post($checkout_page_id);
            if ($post) {
                if (strpos($post->post_content, 'wp-block-wgb-wc-gift') === false && strpos($post->post_content, '<!-- wp:woocommerce/checkout -->') !== false) {
                    $wgb_block = '<!-- wp:wgb/wc-gift -->
                        <div class="wp-block-wgb-wc-gift"></div>
                        <!-- /wp:wgb/wc-gift -->';

                    $post->post_content .= "\n" . $wgb_block;
                    wp_update_post($post);
                }
            }
        }
    }

    public static function deactivate()
    {
        // 
    }

    private function get_reports_js_data()
    {
        $order_repository = Order::get_instance();
        return [
            'subPage' => (!empty($_GET['sub-page'])) ? sanitize_text_field($_GET['sub-page']) : 'dashboard', //phpcs:ignore
            'subMenu' => (!empty($_GET['sub-menu'])) ? sanitize_text_field($_GET['sub-menu']) : '', //phpcs:ignore
            'orderStatuses' => $order_repository->get_order_statuses(),
            'mainUrl' => WGBL_REPORTS_PAGE,
            'dataTableOptions' => [
                'responsive' => true,
                'dom' => 'lBfrtip',
                'buttons' => [
                    [
                        'buttons' => ['csv', 'excel', 'pdf', 'print'],
                        'text' => '<i class="lni lni-cloud-download"></i>',
                        'extend' => 'collection',
                    ]
                ]
            ]
        ];
    }

    private function get_rules_js_data()
    {
        $rule_repository = Rule::get_instance();
        $shipping_methods_options = $rule_repository->get_shipping_methods_options();
        $setting_repository = Setting::get_instance();
        $settings = $setting_repository->get();

        // get product buy fields
        $product_buy_item = [
            'type' => 'product',
            'method_option' => 'in_list',
            'value' => '',
        ];
        $product_buy_id = 'set_product_buy_id_here';
        $rule_id = 'set_rule_id_here';
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/product-buy/row.php';
        $product_buy_row = ob_get_clean();

        $product_buy_render = Product_Buy_Render::get_instance();
        $product_buy_render->set_data([
            'product_buy_item' => $product_buy_item,
            'rule_id' => $rule_id,
            'product_buy_id' => $product_buy_id,
            'option_values' => '',
            'field_status' => '',
        ]);
        $product_buy_extra_fields = $product_buy_render->get_all_extra_fields();

        // get condition fields
        $condition_item = [
            'type' => 'date',
            'method_option' => 'from',
            'value' => '',
        ];
        $condition_id = 'set_condition_id_here';
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/conditions/row.php';
        $condition_row = ob_get_clean();

        $condition_render = Condition_Render::get_instance();
        $condition_render->set_data([
            'condition_item' => $condition_item,
            'rule_id' => $rule_id,
            'condition_id' => $condition_id,
            'option_values' => '',
            'field_status' => '',
        ]);
        $condition_extra_fields = $condition_render->get_all_extra_fields();

        // new rule item
        $rule_item = [
            'rule_name' => 'New Rule',
            'uid' => 'set_uid_here',
            'method' => 'simple',
            'status' => 'enable',
            'description' => '',
        ];

        $rule_methods_grouped = $rule_repository->get_rule_methods_grouped();
        $wgb_language = WGBL_Language::get_instance();
        $site_languages = $wgb_language->get_languages();

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/rule-item.php';
        $new_rule = ob_get_clean();

        // quantities row
        $rule_item = null;
        $i = "set_row_counter_here";
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/quantities/bulk-quantity/row.php';
        $bulk_quantity_row = ob_get_clean();

        // bulk pricing row
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/quantities/bulk-pricing/row.php';
        $bulk_pricing_row = ob_get_clean();

        // tiered quantity row
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/quantities/tiered-quantity/row.php';
        $tiered_quantity_row = ob_get_clean();

        // get products group row
        $group_id = 'set_group_id_here';
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/get_products_group/row.php';
        $get_products_group_row = ob_get_clean();

        $group_item['type'] = 'set_type_here';
        $class_name = 'set_class_here';
        ob_start();
        include WGBL_VIEWS_DIR . 'rules/get_products_group/value.php';
        $get_products_group_value_field = ob_get_clean();

        return [
            'new_rule' => $new_rule,
            'quantities' => [
                'bulk_quantity' => [
                    'row' => $bulk_quantity_row
                ],
                'bulk_pricing' => [
                    'row' => $bulk_pricing_row
                ],
                'tiered_quantity' => [
                    'row' => $tiered_quantity_row
                ]
            ],
            'product_buy' => [
                'row' => $product_buy_row,
                'extra_fields' => $product_buy_extra_fields,
            ],
            'condition' => [
                'row' => $condition_row,
                'extra_fields' => $condition_extra_fields,
            ],
            'get_products_group' => [
                'row' => $get_products_group_row,
                'value_field' => $get_products_group_value_field
            ],
            'settings' => $settings
        ];
    }

    private function get_offer_rules_js_data()
    {
        $rule_id = 'set_rule_id_here';

        // get condition fields
        $condition_item = [
            'type' => 'date',
            'method_option' => 'from',
            'value' => '',
        ];
        $condition_id = 'set_condition_id_here';
        ob_start();
        include WGBL_VIEWS_DIR . 'offer_rules/conditions/row.php';
        $condition_row = ob_get_clean();

        $condition_render = Condition_Render::get_instance();
        $condition_render->set_data([
            'condition_item' => $condition_item,
            'rule_id' => $rule_id,
            'condition_id' => $condition_id,
            'option_values' => '',
            'field_status' => '',
        ]);
        $condition_extra_fields = $condition_render->get_all_extra_fields();

        // new rule item
        $rule_item = [
            'rule_name' => 'New Offer',
            'uid' => 'set_uid_here',
            'method' => 'simple',
            'status' => 'enable',
            'description' => '',
        ];

        $offer_rule_repository = OfferRule::get_instance();
        $rule_types = $offer_rule_repository->get_rule_types();

        ob_start();
        include WGBL_VIEWS_DIR . 'offer_rules/rule-item.php';
        $new_rule = ob_get_clean();

        return [
            'new_rule' => $new_rule,
            'condition' => [
                'row' => $condition_row,
                'extra_fields' => $condition_extra_fields,
            ]
        ];
    }


    public static function is_initable()
    {
        if (!is_null(self::$is_initable)) {
            return self::$is_initable;
        }

        if (!class_exists('WooCommerce')) {
            self::woocommerce_required();
            self::$is_initable = false;
            return false;
        }

        if (defined('WGB_NAME')) {
            self::$is_initable = false;
            return false;
        }

        self::$is_initable = true;
        return true;
    }
}
