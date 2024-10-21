<?php

namespace wgbl\classes\bootstrap;

use wgbl\classes\controllers\Rules_Controller;
use wgbl\classes\controllers\Reports_Controller;
use wgbl\classes\controllers\WGBL_Ajax;
use wgbl\classes\controllers\WGBL_Post;
use wgbl\classes\repositories\Order;
use wgbl\classes\repositories\Rule;
use wgbl\classes\repositories\Setting;
use wgbl\classes\services\render\Condition_Render;
use wgbl\classes\services\render\Product_Buy_Render;

class WGBL
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
        if (!current_user_can('manage_woocommerce')) {
            return;
        }
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        WGBL_Top_Banners::register();
        WGBL_Ajax::register_callback();
        WGBL_Post::register_callback();
        (new WGBL_Custom_Queries())->init();

        $settings_repository = Setting::get_instance();
        if (!$settings_repository->has_settings()) {
            $settings_repository->set_default_settings();
        }
    }

    public static function wgbl_woocommerce_required()
    {
        include WGBL_VIEWS_DIR . 'alerts/wgbl_woocommerce_required.php';
    }

    public static function wgbl_wp_init()
    {
        // set plugin version
        $version = get_option('wgbl_version');
        if (empty($version) || $version != WGBL_VERSION) {
            update_option('wgbl_version', WGBL_VERSION);
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
        $locale = apply_filters('plugin_locale', $locale, 'ithemeland-free-gifts-for-woocommerce-lite');

        // Unload the text domain if other plugins/themes loaded the same text domain by mistake.
        unload_textdomain('ithemeland-free-gifts-for-woocommerce-lite');
        // Load the text domain from the "wp-content" languages folder. we have handles the plugin folder in languages folder for easily handle it.
        load_textdomain('ithemeland-free-gifts-for-woocommerce-lite', WP_LANG_DIR . '/ithemeland-free-gifts-for-woo/ithemeland-free-gifts-for-woocommerce-lite-' . $locale . '.mo');
        // Load the text domain from the current plugin languages folder.
        load_plugin_textdomain('ithemeland-free-gifts-for-woocommerce-lite', false, WGBL_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        add_menu_page(esc_html__('Woo Free Gift', 'ithemeland-free-gifts-for-woocommerce-lite'), esc_html__('Woo Free Gift', 'ithemeland-free-gifts-for-woocommerce-lite'), 'manage_woocommerce', 'wgbl', [new Rules_Controller, 'index'], WGBL_IMAGES_URL . 'wgbl_icon.svg', 2);
        add_submenu_page('wgbl', esc_html__('Rules | Settings', 'ithemeland-free-gifts-for-woocommerce-lite'), esc_html__('Rules | Settings', 'ithemeland-free-gifts-for-woocommerce-lite'), 'manage_woocommerce', 'wgbl');
        add_submenu_page('wgbl', esc_html__('Reports', 'ithemeland-free-gifts-for-woocommerce-lite'), esc_html__('Reports', 'ithemeland-free-gifts-for-woocommerce-lite'), 'manage_woocommerce', 'wgbl-reports', [new Reports_Controller, 'index']);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && in_array($_GET['page'], ['wgbl', 'wgbl-reports'])) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $rule_repository = Rule::get_instance();
            // Styles
            wp_enqueue_style('wgbl-reset', WGBL_CSS_URL . 'common/reset.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-LineIcons', WGBL_CSS_URL . 'common/LineIcons.min.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-select2', WGBL_CSS_URL . 'common/select2.min.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-tipsy', WGBL_CSS_URL . 'common/jquery.tipsy.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-sweetalert', WGBL_CSS_URL . 'common/sweetalert.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-datetimepicker', WGBL_CSS_URL . 'common/jquery.datetimepicker.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-main', WGBL_CSS_URL . 'common/style.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-responsive', WGBL_CSS_URL . 'common/responsive.css', [], WGBL_VERSION);

            // Scripts
            wp_enqueue_script('wgbl-functions', WGBL_JS_URL . 'common/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-tipsy', WGBL_JS_URL . 'common/jquery.tipsy.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-datetimepicker', WGBL_JS_URL . 'common/jquery.datetimepicker.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-select2', WGBL_JS_URL . 'common/select2.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-sweetalert', WGBL_JS_URL . 'common/sweetalert.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-main', WGBL_JS_URL . 'common/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_localize_script('wgbl-main', 'WGBL_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('wgbl_ajax_nonce'),
                'ruleMethods' => $rule_repository->get_rule_methods(),
                'ruleMethodOptions' => $rule_repository->get_rule_method_options(),
                'loadingImage' => '<span class="wgbl-button-loading"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="34px" height="34px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="17.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate></rect><rect x="42.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate></rect><rect x="67.5" y="30" width="15" height="40" fill="#ffffff"><animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate><animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate></rect></svg></span>',
            ]);
        }

        // load assets for rules | settings 
        if (!empty($_GET['page']) && $_GET['page'] == 'wgbl') { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            // Styles
            wp_enqueue_style('wgbl-rules-main', WGBL_CSS_URL . 'rules/style.css', [], WGBL_VERSION);

            // Scripts
            wp_enqueue_script('wgb-rules-form-conditions', WGBL_JS_URL . 'rules/form_data/common/conditions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-form-get', WGBL_JS_URL . 'rules/form_data/common/get.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-form-products_buy', WGBL_JS_URL . 'rules/form_data/common/products_buy.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-form-quantities', WGBL_JS_URL . 'rules/form_data/common/quantities.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-form-methods', WGBL_JS_URL . 'rules/form_data/methods.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-functions', WGBL_JS_URL . 'rules/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgb-rules-main', WGBL_JS_URL . 'rules/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_localize_script('wgb-rules-main', 'WGBL_RULES_DATA', $this->get_rules_js_data()); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('jquery-ui-sortable');
        }

        // load assets for reports
        if (!empty($_GET['page']) && $_GET['page'] == 'wgbl-reports') { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            // Styles
            wp_enqueue_style('wgbl-reports-bootstrap', WGBL_CSS_URL . 'common/bootstrap.dataTables.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-reports-datatable', WGBL_CSS_URL . 'common/dataTables.bootstrap4.min.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-reports-daterangepicker', WGBL_CSS_URL . 'common/daterangepicker.css', [], WGBL_VERSION);
            wp_enqueue_style('wgbl-reports-main', WGBL_CSS_URL . 'reports/style.css', [], WGBL_VERSION);

            // Scripts
            wp_enqueue_script('wgbl-reports-amcharts-core', WGBL_JS_URL . 'common/amcharts/core.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-amcharts-charts', WGBL_JS_URL . 'common/amcharts/charts.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-amcharts-animated', WGBL_JS_URL . 'common/amcharts/animated.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-datatable', WGBL_JS_URL . 'common/jquery.dataTables.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-datatable-buttons', WGBL_JS_URL . 'common/dataTables.buttons.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-pdfmake', WGBL_JS_URL . 'common/pdfmake.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-jszip', WGBL_JS_URL . 'common/jszip.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-vfs-fonts', WGBL_JS_URL . 'common/vfs_fonts.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-buttons-html5', WGBL_JS_URL . 'common/buttons.html5.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-buttons-print', WGBL_JS_URL . 'common/buttons.print.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-buttons-flash', WGBL_JS_URL . 'common/buttons.flash.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-moment', WGBL_JS_URL . 'common/moment.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-daterangepicker', WGBL_JS_URL . 'common/daterangepicker.min.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-functions', WGBL_JS_URL . 'reports/functions.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            wp_enqueue_script('wgbl-reports-main', WGBL_JS_URL . 'reports/main.js', ['jquery'], WGBL_VERSION); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter

            wp_localize_script('wgbl-reports-main', 'WGBL_REPORTS_DATA', $this->get_reports_js_data());
        }
    }

    public static function wp_loaded()
    {
        //    
    }

    public static function activate()
    {
        //
    }

    public static function deactivate()
    {
        //
    }

    private function get_reports_js_data()
    {
        $order_repository = Order::get_instance();
        return [
            'subPage' => (!empty($_GET['sub-page'])) ? sanitize_text_field($_GET['sub-page']) : 'dashboard', //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            'subMenu' => (!empty($_GET['sub-menu'])) ? sanitize_text_field($_GET['sub-menu']) : '', //phpcs:ignore WordPress.Security.NonceVerification.Recommended
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
        $rule_repository = Rule::get_instance();
        $rule_methods_grouped = $rule_repository->get_rule_methods_grouped();

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
            ]
        ];
    }
}
