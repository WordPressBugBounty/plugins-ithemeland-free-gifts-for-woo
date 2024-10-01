<?php include WGBL_VIEWS_DIR . "layout/header.php"; ?>
<div id="wgbl-body">
    <div class="wgbl-menu wgbl-menu-main">
        <div class="wgbl-menu-navigation">
            <nav class="wgbl-menu-navbar">
                <ul class="wgbl-menu-list" data-content-id="wgbl-main-menu-contents">
                    <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'dashboard'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo ((empty($_GET['sub-page'])) || ($_GET['sub-page'] == 'dashboard')) ? 'selected' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                ?>"><?php esc_html_e('Dashboard', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a></li>
                    <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'rules'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'rules') ? 'selected' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                            ?>"><?php esc_html_e('Rules', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a></li>
                    <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'orders'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'orders') ? 'selected' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                            ?>"><?php esc_html_e('Orders', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a></li>
                    <li>
                        <a href="javascript:;" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers') ? 'selected' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                        ?>">
                            <?php esc_html_e('Customers', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                            <i class="lni lni-chevron-down"></i>
                        </a>
                        <ul class="wgbl-sub-menu">
                            <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'customers', 'sub-menu' => 'all-customers'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'all-customers') ? 'selected-sub-menu' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                                                        ?>"><?php esc_html_e('All customers', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a>
                            <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'customers', 'sub-menu' => 'used-rules-by-customer'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'customers' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'used-rules-by-customer') ? 'selected-sub-menu' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                                                                ?>"><?php esc_html_e('Used rules by customer', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products') ? 'selected' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                ?>">
                            <?php esc_html_e('Gifts/Products', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                            <i class="lni lni-chevron-down"></i>
                        </a>
                        <ul class="wgbl-sub-menu">
                            <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products', 'sub-menu' => 'products'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'products') ? 'selected-sub-menu' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                                                ?>"><?php esc_html_e('Products', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a>
                            <li><a href="<?php echo esc_url(add_query_arg(["sub-page" => 'products', 'sub-menu' => 'gotten-gifts-by-customer'], WGBL_REPORTS_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'products' && !empty($_GET['sub-menu']) && $_GET['sub-menu'] == 'gotten-gifts-by-customer') ? 'selected-sub-menu' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                                                                                ?>"><?php esc_html_e('Received gifts by customer', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></a>
                        </ul>
                    </li>
                    <?php if (empty($_GET['sub-page']) || (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'dashboard')) : //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                    ?>
                        <li class="wgbl-main-date-filter">
                            <label>
                                <i class="lni lni-calendar"></i>
                                <input type="text" id="wgbl-main-date-filter" class="wgbl-reports-daterangepicker" value="" data-from="<?php echo (!empty($dashboard_date)) ? esc_attr($dashboard_date) : ''; ?>" data-to="<?php echo (!empty($dashboard_date)) ? esc_attr(gmdate('Y/m/d', time())) : ''; ?>" placeholder="<?php esc_html_e('Date ...', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>" title="<?php esc_html_e('Select date', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>">
                            </label>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div class="wgbl-reports-content">