<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wgb-reports-sub-tabs">
    <ul class="wgb-menu-list">
        <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'dashboard'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo ((empty($_GET['sub-page'])) || ($_GET['sub-page'] == 'dashboard')) ? 'selected' : ''; //phpcs:ignore ?>"><?php esc_html_e('Dashboard', 'ithemeland-free-gifts-for-woo'); ?></a></li>
        <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'rules'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'rules') ? 'selected' : ''; //phpcs:ignore ?>"><?php esc_html_e('Rules', 'ithemeland-free-gifts-for-woo'); ?></a></li>
        <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'orders'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'orders') ? 'selected' : ''; //phpcs:ignore ?>"><?php esc_html_e('Orders', 'ithemeland-free-gifts-for-woo'); ?></a></li>
        <li>
            <a href="javascript:;" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers') ? 'selected' : ''; //phpcs:ignore ?>">
                <?php esc_html_e('Customers', 'ithemeland-free-gifts-for-woo'); ?>
                <i class="lni lni-chevron-down"></i>
            </a>
            <ul class="wgb-sub-menu">
                <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'customers', 'sub-menu' => 'all-customers'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'all-customers') ? 'selected-sub-menu' : '';  //phpcs:ignore ?>"><?php esc_html_e('All customers', 'ithemeland-free-gifts-for-woo'); ?></a>
                <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'customers', 'sub-menu' => 'used-rules-by-customer'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'used-rules-by-customer') ? 'selected-sub-menu' : '';  //phpcs:ignore ?>"><?php esc_html_e('Used rules by customer', 'ithemeland-free-gifts-for-woo'); ?></a>
            </ul>
        </li>
        <li>
            <a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products') ? 'selected' : ''; //phpcs:ignore ?>">
                <?php esc_html_e('Gifts/Products', 'ithemeland-free-gifts-for-woo'); ?>
                <i class="lni lni-chevron-down"></i>
            </a>
            <ul class="wgb-sub-menu">
                <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products', 'sub-menu' => 'products'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'products') ? 'selected-sub-menu' : '';  //phpcs:ignore ?>"><?php esc_html_e('Products', 'ithemeland-free-gifts-for-woo'); ?></a>
                <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products', 'sub-menu' => 'gotten-gifts-by-customer'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'gotten-gifts-by-customer') ? 'selected-sub-menu' : '';  //phpcs:ignore ?>"><?php esc_html_e('Received gifts by customer', 'ithemeland-free-gifts-for-woo'); ?></a>
            </ul>
        </li>
        <?php if (empty($_GET['sub-page']) || (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'dashboard')) :  //phpcs:ignore ?>
            <li class="wgb-main-date-filter">
                <label>
                    <i class="lni lni-calendar"></i>
                    <input type="text" id="wgb-main-date-filter" class="wgb-reports-daterangepicker" value="" data-from="<?php echo (!empty($dashboard_date)) ? esc_attr($dashboard_date) : ''; ?>" data-to="<?php echo (!empty($dashboard_date)) ? esc_attr(gmdate('Y/m/d', time())) : ''; ?>" placeholder="<?php esc_html_e('Date ...', 'ithemeland-free-gifts-for-woo'); ?>" title="<?php esc_html_e('Select date', 'ithemeland-free-gifts-for-woo'); ?>">
                </label>
            </li>
        <?php endif; ?>
    </ul>
</div>