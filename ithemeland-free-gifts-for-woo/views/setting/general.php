<div class="wgbl-wrap">
    <div class="wgbl-tab-middle-content">
        <?php
        include_once WGBL_VIEWS_DIR . 'setting/tabs.php';
        if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'settings') {
            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
        }
        ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="wgbl_save_settings_general">
            <?php wp_nonce_field('wgbl_post_nonce'); ?>
            <div id="wgbl-settings" class="wgbl-col-12">
                <div class="wgbl-form-group">
                    <label for="wgbl-settings-dashboard_date-in-cart"><?php esc_html_e('Dashboard date', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <select name="settings[dashboard_date]" id="wgbl-settings-dashboard_date-in-cart">
                        <option value="one_month_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'one_month_ago') ? 'selected' : '' ?>>
                            <?php esc_html_e("One month ago", 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="the_last_three_months" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_three_months') ? 'selected' : '' ?>>
                            <?php esc_html_e('The last three months', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="the_last_six_months" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_six_months') ? 'selected' : '' ?>>
                            <?php esc_html_e('The last six months', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="nine_months_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'nine_months_ago') ? 'selected' : '' ?>>
                            <?php esc_html_e('Nine months ago', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="once_year_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'once_year_ago') ? 'selected' : '' ?>>
                            <?php esc_html_e('Once Year Ago', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="the_last_two_years" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_two_years') ? 'selected' : '' ?>>
                            <?php esc_html_e('The last two years', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="the_last_three_years" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_three_years') ? 'selected' : '' ?>>
                            <?php esc_html_e('The last three years', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                    </select>
                    <p class="wgbl-description"><?php esc_html_e('Set date for dashboard report', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></p>
                </div>
                <div class="wgbl-form-group">
                    <label for="wgbl-settings-display_price-in-cart"><?php esc_html_e('Display price for gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <select name="settings[display_price]" id="wgbl-settings-display_price-in-cart">
                        <option value="no" <?php echo (!empty($settings['display_price']) && $settings['display_price'] == 'beside_coupon') ? 'selected' : '' ?>>
                            <?php esc_html_e("Don't Display Price", 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="yes" <?php echo (!empty($settings['display_price']) && $settings['display_price'] == 'yes') ? 'selected' : '' ?>>
                            <?php esc_html_e('Strike and Display the Price', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                    </select>
                    <p class="wgbl-description"><?php esc_html_e('Display price for gifts are added to cart', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></p>
                </div>
                <div class="wgbl-form-group">
                    <label for="wgbl-settings-position-in-cart"><?php esc_html_e("'Your Gifts' position", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <select name="settings[position]" id="wgbl-settings-position-in-cart">
                        <option value="beside_coupon" <?php echo (!empty($settings['position']) && $settings['position'] == 'beside_coupon') ? 'selected' : '' ?>>
                            <?php esc_html_e('Beside of Coupon Button', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="bottom_cart" <?php echo (!empty($settings['position']) && $settings['position'] == 'bottom_cart') ? 'selected' : '' ?>>
                            <?php esc_html_e('Bottom of Cart', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="none" <?php echo (!empty($settings['position']) && $settings['position'] == 'none') ? 'selected' : '' ?>>
                            <?php esc_html_e('None', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <!--
						<option value="popup" <?php echo (!empty($settings['position']) && $settings['position'] == 'popup') ? 'selected' : '' ?>>
                            <?php esc_html_e('Popup in The Cart/Checkout Page ', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
						-->
                    </select>
                    <p class="wgbl-description"><?php esc_html_e("Where do you want to display available gift in cart page?", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></p>
                </div>
                <div class="wgbl-form-group" data-type="layout-select-box">
                    <label for="wgbl-settings-layout"><?php esc_html_e('Layout', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <select name="settings[layout]" id="wgbl-settings-layout">
                        <option value="grid" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'grid') ? 'selected' : '' ?>>
                            <?php esc_html_e('Grid', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="carousel" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'carousel') ? 'selected' : '' ?>>
                            <?php esc_html_e('Carousel', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="datatable" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'datatable') ? 'selected' : '' ?>>
                            <?php esc_html_e('Datatable', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <option value="dropdown" data-type="beside_coupon" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'dropdown') ? 'selected' : '' ?>>
                            <?php esc_html_e('Dropdown', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
                        <!--
						<option value="button" data-type="beside_coupon" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'button') ? 'selected' : '' ?>>
                            <?php esc_html_e('Button (Display Popup)', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                        </option>
						-->
                    </select>
                </div>
                <div class="wgbl-form-group position-dependency" data-type="bottom_cart">
                    <label for="wgbl-settings-show-variations"><?php esc_html_e('Show Variations', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="checkbox" name="settings[child]" id="wgbl-settings-show-variations" value="true" <?php echo (!empty($settings['child']) && $settings['child'] == 'true') ? 'checked="checked"' : '' ?>>
                    <p class="wgbl-description"><?php esc_html_e('Leave it if you want to show variations gifts in popup', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></p>
                </div>
                <div class="wgbl-form-group position-dependency" data-type="bottom_cart">
                    <label for="wgbl-settings-show-stock-quantity"><?php esc_html_e('Show Stock Quantity', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="checkbox" name="settings[show_stock_quantity]" id="wgbl-settings-show-stock-quantity" value="true" <?php echo (!empty($settings['show_stock_quantity']) && $settings['show_stock_quantity'] == 'true') ? 'checked="checked"' : '' ?>>
                    <p class="wgbl-description"><?php esc_html_e("If checked, 'stock status' is shown for each gift.", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></p>
                </div>
                <div id="wgbl-settings-view-gift-in-cart-dependencies">
                    <div class="wgbl-settings-dependency-item" data-type="grid">
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Number Per Page', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_html_e('Gift Number Per Page', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." min="1" name="settings[view_gift_in_cart][number_per_page]" value="<?php echo (isset($settings['view_gift_in_cart']['number_per_page'])) ? esc_attr($settings['view_gift_in_cart']['number_per_page']) : '4' ?>">
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Desktop Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <select name="settings[view_gift_in_cart][desktop_columns]" id="desktop_columns">
                                <option value="wgb-col-md-2" data-type="beside_coupon" <?php echo (!empty($settings['view_gift_in_cart']['desktop_columns']) && $settings['view_gift_in_cart']['desktop_columns'] == 'wgb-col-md-2') ? 'selected' : '' ?>>
                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-md-3" data-type="beside_coupon" <?php echo (!empty($settings['view_gift_in_cart']['desktop_columns']) && $settings['view_gift_in_cart']['desktop_columns'] == 'wgb-col-md-3') ? 'selected' : '' ?>>
                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-md-4" data-type="bottom_cart" <?php echo (!empty($settings['view_gift_in_cart']['desktop_columns']) && $settings['view_gift_in_cart']['desktop_columns'] == 'wgb-col-md-4') ? 'selected' : '' ?>>
                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-md-6" data-type="bottom_cart" <?php echo (!empty($settings['view_gift_in_cart']['desktop_columns']) && $settings['view_gift_in_cart']['desktop_columns'] == 'wgb-col-md-6') ? 'selected' : '' ?>>
                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-md-12" data-type="wgb-col-md-12" <?php echo (!empty($settings['view_gift_in_cart']['desktop_columns']) && $settings['view_gift_in_cart']['desktop_columns'] == 'wgb-col-md-12') ? 'selected' : '' ?>>
                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                            </select>
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Tablet Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <select name="settings[view_gift_in_cart][tablet_columns]" id="tablet_columns">
                                <option value="wgb-col-sm-2" data-type="wgb-col-sm-2" <?php echo (!empty($settings['view_gift_in_cart']['tablet_columns']) && $settings['view_gift_in_cart']['tablet_columns'] == 'wgb-col-sm-2') ? 'selected' : '' ?>>
                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-sm-3" data-type="wgb-col-sm-3" <?php echo (!empty($settings['view_gift_in_cart']['tablet_columns']) && $settings['view_gift_in_cart']['tablet_columns'] == 'wgb-col-sm-3') ? 'selected' : '' ?>>
                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-sm-4" data-type="wgb-col-sm-4" <?php echo (!empty($settings['view_gift_in_cart']['tablet_columns']) && $settings['view_gift_in_cart']['tablet_columns'] == 'wgb-col-sm-4') ? 'selected' : '' ?>>
                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-sm-6" data-type="wgb-col-sm-6" <?php echo (!empty($settings['view_gift_in_cart']['tablet_columns']) && $settings['view_gift_in_cart']['tablet_columns'] == 'wgb-col-sm-6') ? 'selected' : '' ?>>
                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-sm-12" data-type="wgb-col-sm-12" <?php echo (!empty($settings['view_gift_in_cart']['tablet_columns']) && $settings['view_gift_in_cart']['tablet_columns'] == 'wgb-col-sm-12') ? 'selected' : '' ?>>
                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                            </select>
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Mobile Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <select name="settings[view_gift_in_cart][mobile_columns]" id="mobile_columns">
                                <option value="wgb-col-2" data-type="wgb-col-2" <?php echo (!empty($settings['view_gift_in_cart']['mobile_columns']) && $settings['view_gift_in_cart']['mobile_columns'] == 'wgb-col-2') ? 'selected' : '' ?>>
                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-3" data-type="wgb-col-3" <?php echo (!empty($settings['view_gift_in_cart']['mobile_columns']) && $settings['view_gift_in_cart']['mobile_columns'] == 'wgbl-col-3') ? 'selected' : '' ?>>
                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-4" data-type="wgb-col-4" <?php echo (!empty($settings['view_gift_in_cart']['mobile_columns']) && $settings['view_gift_in_cart']['mobile_columns'] == 'wgb-col-4') ? 'selected' : '' ?>>
                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-6" data-type="wgb-col-6" <?php echo (!empty($settings['view_gift_in_cart']['mobile_columns']) && $settings['view_gift_in_cart']['mobile_columns'] == 'wgb-col-6') ? 'selected' : '' ?>>
                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                                <option value="wgb-col-12" data-type="wgb-col-12" <?php echo (!empty($settings['view_gift_in_cart']['mobile_columns']) && $settings['view_gift_in_cart']['mobile_columns'] == 'wgb-col-12') ? 'selected' : '' ?>>
                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="wgbl-settings-dependency-item" data-type="carousel">
                        <div class="wgbl-form-group">
                            <label for="wgbl-settings-carousel-loop"><?php esc_html_e('Loop', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="checkbox" name="settings[view_gift_in_cart][carousel][loop]" id="wgbl-settings-carousel-loop" value="true" <?php echo (!empty($settings['view_gift_in_cart']['carousel']['loop']) && $settings['view_gift_in_cart']['carousel']['loop'] == 'true') ? 'checked="checked"' : '' ?>>
                        </div>
                        <div class="wgbl-form-group">
                            <label for="wgbl-settings-carousel-dots"><?php esc_html_e('Show Dots', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="checkbox" name="settings[view_gift_in_cart][carousel][dots]" id="wgbl-settings-carousel-dots" value="true" <?php echo (!empty($settings['view_gift_in_cart']['carousel']['dots']) && $settings['view_gift_in_cart']['carousel']['dots'] == 'true') ? 'checked="checked"' : '' ?>>
                        </div>
                        <div class="wgbl-form-group">
                            <label for="wgbl-settings-carousel-nav"><?php esc_html_e('Show Nav', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="checkbox" name="settings[view_gift_in_cart][carousel][nav]" id="wgbl-settings-carousel-nav" value="true" <?php echo (!empty($settings['view_gift_in_cart']['carousel']['nav']) && $settings['view_gift_in_cart']['carousel']['nav'] == 'true') ? 'checked="checked"' : '' ?>>
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Slide Speed in Milliseconds', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_html_e('Slide Speed', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="settings[view_gift_in_cart][carousel][speed]" value="<?php echo (isset($settings['view_gift_in_cart']['carousel']['speed'])) ? esc_attr($settings['view_gift_in_cart']['carousel']['speed']) : '5000'; ?>">
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Number Items in Mobile', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="settings[view_gift_in_cart][carousel][mobile]" value="<?php echo (isset($settings['view_gift_in_cart']['carousel']['mobile'])) ? esc_attr($settings['view_gift_in_cart']['carousel']['mobile']) : '1' ?>">
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Number Items in Tablet', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="settings[view_gift_in_cart][carousel][tablet]" value="<?php echo (isset($settings['view_gift_in_cart']['carousel']['tablet'])) ? esc_attr($settings['view_gift_in_cart']['carousel']['tablet']) : '3' ?>">
                        </div>
                        <div class="wgbl-form-group">
                            <label><?php esc_html_e('Number Items in Desktop', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="settings[view_gift_in_cart][carousel][desktop]" value="<?php echo (isset($settings['view_gift_in_cart']['carousel']['desktop'])) ? esc_attr($settings['view_gift_in_cart']['carousel']['desktop']) : '5' ?>">
                        </div>
                    </div>
                </div>
                <div class="wgbl-form-group">
                    <input type="hidden" name="settings[enable_ajax_add_to_cart]" value="false">
                    <label for="wgbl-settings-enable-ajax-add-to-cart"><?php esc_html_e('Ajax Manual Gift Products Add To Cart', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                    <input type="checkbox" name="settings[enable_ajax_add_to_cart]" id="wgbl-settings-enable-ajax-add-to-cart" value="true" <?php echo (!empty($settings['enable_ajax_add_to_cart']) && $settings['enable_ajax_add_to_cart'] == 'true') ? 'checked="checked"' : '' ?>>
                </div>
            </div>
            <div class="wgbl-col-12">
                <button type="submit" class="wgbl-button wgbl-button-blue wgbl-float-left wgbl-mt10"><?php esc_html_e("Save Changes", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
            </div>
        </form>
    </div>
</div>