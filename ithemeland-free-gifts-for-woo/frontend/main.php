<?php
define('plugin_dir_url_wc_advanced_gift', plugin_dir_url(__FILE__));
define('plugin_dir_path_wc_adv_gift', plugin_dir_path(__FILE__));

class iThemeland_woocommerce_advanced_gift
{
    public function __construct()
    {
        //functions
        require dirname(__FILE__) . '/functions/condition-functions.php';
        require dirname(__FILE__) . '/functions/core_functions.php';
        require dirname(__FILE__) . '/functions/operation.php';
        //require dirname(__FILE__) . '/functions/store-api-functions.php';

        //classes
        require dirname(__FILE__) . '/classes/services/apply_rule/CheckRuleCondition.php';
        require dirname(__FILE__) . '/classes/services/apply_rule/helpers/AjaxHandler.php';
        require dirname(__FILE__) . '/classes/services/views/generator/Pagination.php';
        require dirname(__FILE__) . '/classes/admin-order.php';
        require dirname(__FILE__) . '/classes/enqueue-js-css.php';
        require dirname(__FILE__) . '/classes/front-order.php';
        require dirname(__FILE__) . '/classes/cart_hook.php';
        require dirname(__FILE__) . '/classes/shortcodes.php';
        require dirname(__FILE__) . '/classes/front-hide-product.php';
    }
}

new iThemeland_woocommerce_advanced_gift();
