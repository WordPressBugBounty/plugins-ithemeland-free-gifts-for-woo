<?php include WGBL_VIEWS_DIR . "layout/reports_header.php"; ?>
<div class="wgbl-wrap">
    <div class="wgbl-tab-middle-content wgbl-skeleton">
        <div class="wgbl-row">
            <div class="wgbl-col-3">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-top-boxes">
                    <div class="wgbl-boxes-icon"><i class="lni lni-gift purple"></i></div>
                    <div class="wgbl-boxes-text">
                        <div class="wgbl-widget-title"><strong id="wgbl-reports-dashboard-total-gift-count">0</strong></div>
                        <div class="wgbl-widget-subtitle"><span><?php esc_html_e('Total Gifts No.', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></span></div>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-3">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-top-boxes">
                    <div class="wgbl-boxes-icon"><i class="lni lni-users blue"></i></div>
                    <div class="wgbl-boxes-text">
                        <div class="wgbl-widget-title"><strong id="wgbl-reports-dashboard-total-customers">0</strong></div>
                        <div class="wgbl-widget-subtitle"><span><?php esc_html_e('Total Customers No.', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></span></div>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-3">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-top-boxes">
                    <div class="wgbl-boxes-icon"><i class="lni lni-layers green"></i></div>
                    <div class="wgbl-boxes-text">
                        <div class="wgbl-widget-title"><strong id="wgbl-reports-dashboard-number-of-used-rule">0</strong></div>
                        <div class="wgbl-widget-subtitle"><span><?php esc_html_e('Used Rule No.', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></span></div>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-3">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-top-boxes">
                    <div class="wgbl-boxes-icon"><i class="lni lni-cart-full orange"></i></div>
                    <div class="wgbl-boxes-text">
                        <div class="wgbl-widget-title"><strong id="wgbl-reports-dashboard-number-of-orders">0</strong></div>
                        <div class="wgbl-widget-subtitle"><span><?php esc_html_e('Orders No.', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-4">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-pt0 wgbl-pr0 wgbl-pl0">
                    <div class="wgbl-chart-filter-buttons" id="wgbl-chart2-buttons">
                        <strong><?php esc_html_e('Top gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                        <button type="button" class="chart2-filter-item active" value="product"><?php esc_html_e('Product', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                        <button type="button" class="chart2-filter-item" value="category"><?php esc_html_e('Category', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                    </div>
                    <div id="amchart2" class="amchart"></div>
                </div>
            </div>
            <div class="wgbl-col-8">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-pt0 wgbl-pr0 wgbl-pl0">
                    <div class="wgbl-chart-filter-buttons" id="wgbl-chart1-buttons">
                        <strong><?php esc_html_e('Gift per', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                        <button type="button" class="chart1-filter-item" value="day"><?php esc_html_e('Day', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                        <button type="button" class="chart1-filter-item" value="week"><?php esc_html_e('Week', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                        <button type="button" class="chart1-filter-item active" value="month"><?php esc_html_e('Month', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                        <button type="button" class="chart1-filter-item" value="year"><?php esc_html_e('Year', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
                    </div>
                    <div id="amchart1" class="amchart"></div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-12">
                <strong class="wgbl-section-title"><?php esc_html_e('Recent 10 orders used the gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
            </div>
            <div class="wgbl-col-12">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table">
                    <div class="wgbl-table table-responsive">
                        <table class="table table-striped table-bordered" id="wgbl-dashboard-recent-orders-used-gift">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Order ID', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Date', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Status', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Rules name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Gifts', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 methods', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-methods">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Method', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 rules', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-rules">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Rule name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 gifts', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-gifts">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Gift product', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 categories', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-categories">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Category name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 countries', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-countries">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Country name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-4">
                <div class="wgbl-col-12">
                    <strong class="wgbl-section-title"><?php esc_html_e('Top 5 states', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
                </div>
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table wgbl-mini-data-grid-box">
                    <div class="wgbl-table">
                        <table class="wgbl-default-table" id="wgbl-dashboard-top-states">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('State name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-12">
                <strong class="wgbl-section-title"><?php esc_html_e('Recent 10 customers get gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
            </div>
            <div class="wgbl-col-12">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table">
                    <div class="wgbl-table table-responsive">
                        <table class="table table-striped table-bordered" id="wgbl-dashboard-recent-customers-get-gift">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Email', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Username', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Used gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Order ID', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Date', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="wgbl-row">
            <div class="wgbl-col-12">
                <strong class="wgbl-section-title"><?php esc_html_e('Recent 10 Used gifts', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong>
            </div>
            <div class="wgbl-col-12">
                <div class="wgbl-widget-box wgbl-skeleton-loading wgbl-skeleton-table">
                    <div class="wgbl-table table-responsive">
                        <table class="table table-striped table-bordered" id="wgbl-dashboard-used-gifts">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Product name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Sku', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <?php if ($it_brands_is_active) : ?>
                                        <th class="it-product-brands"><?php esc_html_e('Brand', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <?php endif; ?>
                                    <th><?php esc_html_e('Category', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                    <th><?php esc_html_e('Count', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4"><?php esc_html_e('No item', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include WGBL_VIEWS_DIR . "layout/reports_footer.php"; ?>