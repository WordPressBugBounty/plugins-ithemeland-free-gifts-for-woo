<?php
define('plugin_dir_url_wc_advanced_gift', plugin_dir_url(__FILE__));
define('plugin_dir_path_wc_adv_gift', plugin_dir_path(__FILE__));

class iThemeland_woocommerce_advanced_gift
{
    public function __construct()
    {
        //Function
        require dirname(__FILE__) . '/functions/condition-functions.php';
        require dirname(__FILE__) . '/functions/core_functions.php';
        require dirname(__FILE__) . '/functions/operation.php';

        //Class
        require dirname(__FILE__) . '/classes/check_rule_condition.php';
        require dirname(__FILE__) . '/classes/admin-order.php';
        require dirname(__FILE__) . '/classes/front-order.php';
        require dirname(__FILE__) . '/classes/shortcodes.php';
    }
}

new iThemeland_woocommerce_advanced_gift();
