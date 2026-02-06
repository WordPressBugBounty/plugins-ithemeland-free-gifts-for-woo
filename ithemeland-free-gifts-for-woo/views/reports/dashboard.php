<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php include WGBL_VIEWS_DIR . "layout/header.php"; ?>
<div id="wgb-body">
    <div class="wgb-tabs wgb-tabs-main">
        <div class="wgb-tabs-navigation">
            <nav class="wgb-tabs-navbar">
                <ul class="wgb-tabs-list" data-content-id="wgb-main-tabs-contents">
                    <?php include WGBL_VIEWS_DIR . 'layout/tabs_title.php' ?>
                </ul>
            </nav>
        </div>
        <div class="wgb-tabs-contents" id="wgb-main-tabs-contents">
            <div class="wgb-tab-content-item">
                <div class="wgb-tab-middle-content">
                    <div class="wgb-wrap">
                        <?php
                        if (!empty($flush_message) && is_array($flush_message)) {
                            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
                        }

                        include_once WGBL_VIEWS_DIR . "reports/tabs_title.php";
                        ?>
                        <div class="wgb-tab-middle-content wgb-skeleton">
                            <div class="wgb-row">
                                <div class="wgb-col-3">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-top-boxes">
                                        <div class="wgb-boxes-icon"><i class="lni lni-gift purple"></i></div>
                                        <div class="wgb-boxes-text">
                                            <div class="wgb-widget-title"><strong id="wgb-reports-dashboard-total-gift-count">0</strong></div>
                                            <div class="wgb-widget-subtitle"><span><?php esc_html_e('Total Gifts No.', 'ithemeland-free-gifts-for-woo'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-3">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-top-boxes">
                                        <div class="wgb-boxes-icon"><i class="lni lni-users blue"></i></div>
                                        <div class="wgb-boxes-text">
                                            <div class="wgb-widget-title"><strong id="wgb-reports-dashboard-total-customers">0</strong></div>
                                            <div class="wgb-widget-subtitle"><span><?php esc_html_e('Total Customers No.', 'ithemeland-free-gifts-for-woo'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-3">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-top-boxes">
                                        <div class="wgb-boxes-icon"><i class="lni lni-layers green"></i></div>
                                        <div class="wgb-boxes-text">
                                            <div class="wgb-widget-title"><strong id="wgb-reports-dashboard-number-of-used-rule">0</strong></div>
                                            <div class="wgb-widget-subtitle"><span><?php esc_html_e('Used Rule No.', 'ithemeland-free-gifts-for-woo'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-3">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-top-boxes">
                                        <div class="wgb-boxes-icon"><i class="lni lni-cart-full orange"></i></div>
                                        <div class="wgb-boxes-text">
                                            <div class="wgb-widget-title"><strong id="wgb-reports-dashboard-number-of-orders">0</strong></div>
                                            <div class="wgb-widget-subtitle"><span><?php esc_html_e('Orders No.', 'ithemeland-free-gifts-for-woo'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-4">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-pt0 wgb-pr0 wgb-pl0">
                                        <div class="wgb-chart-filter-buttons" id="wgb-chart2-buttons">
                                            <strong><?php esc_html_e('Top gift', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                            <button type="button" class="chart2-filter-item active" value="product"><?php esc_html_e('Product', 'ithemeland-free-gifts-for-woo'); ?></button>
                                            <button type="button" class="chart2-filter-item" value="category"><?php esc_html_e('Category', 'ithemeland-free-gifts-for-woo'); ?></button>
                                        </div>
                                        <canvas id="wgb-report-dashboard-chart2"></canvas>
                                    </div>
                                </div>
                                <div class="wgb-col-8">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-pt0 wgb-pr0 wgb-pl0">
                                        <div class="wgb-chart-filter-buttons" id="wgb-chart1-buttons">
                                            <strong><?php esc_html_e('Gift per', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                            <button type="button" class="chart1-filter-item" value="day"><?php esc_html_e('Day', 'ithemeland-free-gifts-for-woo'); ?></button>
                                            <button type="button" class="chart1-filter-item" value="week"><?php esc_html_e('Week', 'ithemeland-free-gifts-for-woo'); ?></button>
                                            <button type="button" class="chart1-filter-item active" value="month"><?php esc_html_e('Month', 'ithemeland-free-gifts-for-woo'); ?></button>
                                            <button type="button" class="chart1-filter-item" value="year"><?php esc_html_e('Year', 'ithemeland-free-gifts-for-woo'); ?></button>
                                        </div>
                                        <canvas id="wgb-report-dashboard-chart1"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-12">
                                    <strong class="wgb-section-title"><?php esc_html_e('Recent 10 orders used the gift', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                </div>
                                <div class="wgb-col-12">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table">
                                        <div class="wgb-table table-responsive">
                                            <table class="table table-striped table-bordered" id="wgb-dashboard-recent-orders-used-gift">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Order ID', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Date', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Status', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Rules name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Gifts', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="5"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 methods', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-methods">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Method', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 rules', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-rules">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Rule name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 gifts', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-gifts">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Gift product', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 categories', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-categories">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Category name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 countries', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-countries">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Country name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-col-4">
                                    <div class="wgb-col-12">
                                        <strong class="wgb-section-title"><?php esc_html_e('Top 5 states', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                    </div>
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table wgb-mini-data-grid-box">
                                        <div class="wgb-table">
                                            <table class="wgb-default-table" id="wgb-dashboard-top-states">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('State name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-12">
                                    <strong class="wgb-section-title"><?php esc_html_e('Recent 10 customers get gift', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                </div>
                                <div class="wgb-col-12">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table">
                                        <div class="wgb-table table-responsive">
                                            <table class="table table-striped table-bordered" id="wgb-dashboard-recent-customers-get-gift">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Email', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Username', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Used gift', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Order ID', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Date', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-row">
                                <div class="wgb-col-12">
                                    <strong class="wgb-section-title"><?php esc_html_e('Recent 10 Used gifts', 'ithemeland-free-gifts-for-woo'); ?></strong>
                                </div>
                                <div class="wgb-col-12">
                                    <div class="wgb-widget-box wgb-skeleton-loading wgb-skeleton-table">
                                        <div class="wgb-table table-responsive">
                                            <table class="table table-striped table-bordered" id="wgb-dashboard-used-gifts">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Product name', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Sku', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <?php if ($it_brands_is_active) : ?>
                                                            <th class="it-product-brands"><?php esc_html_e('Brand', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <?php endif; ?>
                                                        <th><?php esc_html_e('Category', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                        <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woo'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woo'); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>