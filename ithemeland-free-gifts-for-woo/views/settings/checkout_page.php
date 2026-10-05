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
            <div class="wgb-tab-content-item" data-content="settings">
                <div class="wgb-tab-middle-content">
                    <div class="wgb-wrap">
                        <?php
                        if (!empty($flush_message) && is_array($flush_message)) {
                            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
                        }

                        include_once WGBL_VIEWS_DIR . "settings/tabs.php";
                        ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                            <?php wp_nonce_field('wgb_post_nonce'); ?>
                            <input type="hidden" name="action" value="wgb_save_settings_checkout_page">
                            <div id="wgb-settings" class="wgb-col-12">
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-position-in-checkout"><?php esc_html_e("Free Gifts display position", 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[checkout_position]" id="wgb-settings-position-in-checkout">
                                        <option value="none" <?php echo (!empty($itfreegift_settings['checkout_position']) && $itfreegift_settings['checkout_position'] == 'none') ? 'selected' : '' ?>>
                                            <?php esc_html_e('None', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="before_checkout_form" <?php echo (!empty($itfreegift_settings['checkout_position']) && $itfreegift_settings['checkout_position'] == 'before_checkout_form') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Before Checkout Form', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="after_checkout_billing_form" <?php echo (!empty($itfreegift_settings['checkout_position']) && $itfreegift_settings['checkout_position'] == 'after_checkout_billing_form') ? 'selected' : '' ?>>
                                            <?php esc_html_e('After Checkout Billing Form', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                    <p class="wgb-description"><?php esc_html_e("Where do you want to display available gift in cart page?", 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-settings-checkout-page-position-dependency">
                                    <div class="wgb-form-group">
                                        <label for="wgb-settings-layout"><?php esc_html_e('Layout', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <select name="settings[checkout_layout]" id="wgb-settings-layout">
                                            <option value="grid" <?php echo (!empty($itfreegift_settings['checkout_layout']) && $itfreegift_settings['checkout_layout'] == 'grid') ? 'selected' : '' ?>>
                                                <?php esc_html_e('Grid', 'ithemeland-free-gifts-for-woo'); ?>
                                            </option>
                                            <option value="carousel" <?php echo (!empty($itfreegift_settings['checkout_layout']) && $itfreegift_settings['checkout_layout'] == 'carousel') ? 'selected' : '' ?>>
                                                <?php esc_html_e('Carousel', 'ithemeland-free-gifts-for-woo'); ?>
                                            </option>
                                            <option value="datatable" <?php echo (!empty($itfreegift_settings['checkout_layout']) && $itfreegift_settings['checkout_layout'] == 'datatable') ? 'selected' : '' ?>>
                                                <?php esc_html_e('Datatable', 'ithemeland-free-gifts-for-woo'); ?>
                                            </option>
                                        </select>
                                    </div>
                                    <div id="wgb-settings-view-gift-in-checkout-dependencies">
                                        <div class="wgb-settings-dependency-item" data-type="grid">
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Number Per Page', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="number" placeholder="<?php esc_html_e('Gift Number Per Page', 'ithemeland-free-gifts-for-woo'); ?> ..." min="1" name="settings[checkout_number_per_page]" value="<?php echo (isset($itfreegift_settings['checkout_number_per_page'])) ? esc_attr($itfreegift_settings['checkout_number_per_page']) : '4' ?>">
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Desktop Columns', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                                <select name="settings[checkout_desktop_columns]" id="checkout_desktop_columns">
                                                    <option value="wgb-col-md-2" data-type="beside_coupon" <?php echo (!empty($itfreegift_settings['checkout_desktop_columns']) && $itfreegift_settings['checkout_desktop_columns'] == 'wgb-col-md-2') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-md-3" data-type="beside_coupon" <?php echo (!empty($itfreegift_settings['checkout_desktop_columns']) && $itfreegift_settings['checkout_desktop_columns'] == 'wgb-col-md-3') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-md-4" data-type="bottom_cart" <?php echo (!empty($itfreegift_settings['checkout_desktop_columns']) && $itfreegift_settings['checkout_desktop_columns'] == 'wgb-col-md-4') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-md-6" data-type="bottom_cart" <?php echo (!empty($itfreegift_settings['checkout_desktop_columns']) && $itfreegift_settings['checkout_desktop_columns'] == 'wgb-col-md-6') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-md-12" data-type="wgb-col-md-12" <?php echo (!empty($itfreegift_settings['checkout_desktop_columns']) && $itfreegift_settings['checkout_desktop_columns'] == 'wgb-col-md-12') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Tablet Columns', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                                <select name="settings[checkout_tablet_columns]" id="checkout_tablet_columns">
                                                    <option value="wgb-col-sm-2" data-type="wgb-col-sm-2" <?php echo (!empty($itfreegift_settings['checkout_tablet_columns']) && $itfreegift_settings['checkout_tablet_columns'] == 'wgb-col-sm-2') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-sm-3" data-type="wgb-col-sm-3" <?php echo (!empty($itfreegift_settings['checkout_tablet_columns']) && $itfreegift_settings['checkout_tablet_columns'] == 'wgb-col-sm-3') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-sm-4" data-type="wgb-col-sm-4" <?php echo (!empty($itfreegift_settings['checkout_tablet_columns']) && $itfreegift_settings['checkout_tablet_columns'] == 'wgb-col-sm-4') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-sm-6" data-type="wgb-col-sm-6" <?php echo (!empty($itfreegift_settings['checkout_tablet_columns']) && $itfreegift_settings['checkout_tablet_columns'] == 'wgb-col-sm-6') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-sm-12" data-type="wgb-col-sm-12" <?php echo (!empty($itfreegift_settings['checkout_tablet_columns']) && $itfreegift_settings['checkout_tablet_columns'] == 'wgb-col-sm-12') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Mobile Columns', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                                <select name="settings[checkout_mobile_columns]" id="checkout_mobile_columns">
                                                    <option value="wgb-col-2" data-type="wgb-col-2" <?php echo (!empty($itfreegift_settings['checkout_mobile_columns']) && $itfreegift_settings['checkout_mobile_columns'] == 'wgb-col-2') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-3" data-type="wgb-col-3" <?php echo (!empty($itfreegift_settings['checkout_mobile_columns']) && $itfreegift_settings['checkout_mobile_columns'] == 'wgb-col-3') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-4" data-type="wgb-col-4" <?php echo (!empty($itfreegift_settings['checkout_mobile_columns']) && $itfreegift_settings['checkout_mobile_columns'] == 'wgb-col-4') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-6" data-type="wgb-col-6" <?php echo (!empty($itfreegift_settings['checkout_mobile_columns']) && $itfreegift_settings['checkout_mobile_columns'] == 'wgb-col-6') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                    <option value="wgb-col-12" data-type="wgb-col-12" <?php echo (!empty($itfreegift_settings['checkout_mobile_columns']) && $itfreegift_settings['checkout_mobile_columns'] == 'wgb-col-12') ? 'selected' : '' ?>>
                                                        <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="wgb-settings-dependency-item" data-type="carousel">
                                            <div class="wgb-form-group">
                                                <label for="wgb-settings-carousel-loop"><?php esc_html_e('Loop', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[checkout_carousel][loop]" value="false">
                                                <input type="checkbox" name="settings[checkout_carousel][loop]" id="wgb-settings-carousel-loop" value="true" <?php echo (!empty($itfreegift_settings['checkout_carousel']['loop']) && $itfreegift_settings['checkout_carousel']['loop'] == 'true') ? 'checked="checked"' : '' ?>>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label for="wgb-settings-carousel-dots"><?php esc_html_e('Show Dots', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[checkout_carousel][dots]" value="false">
                                                <input type="checkbox" name="settings[checkout_carousel][dots]" id="wgb-settings-carousel-dots" value="true" <?php echo (!empty($itfreegift_settings['checkout_carousel']['dots']) && $itfreegift_settings['checkout_carousel']['dots'] == 'true') ? 'checked="checked"' : '' ?>>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label for="wgb-settings-carousel-nav"><?php esc_html_e('Show Nav', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[checkout_carousel][nav]" value="false">
                                                <input type="checkbox" name="settings[checkout_carousel][nav]" id="wgb-settings-carousel-nav" value="true" <?php echo (!empty($itfreegift_settings['checkout_carousel']['nav']) && $itfreegift_settings['checkout_carousel']['nav'] == 'true') ? 'checked="checked"' : '' ?>>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label for="wgb-settings-carousel-rtl"><?php esc_html_e('Right To Left', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[checkout_carousel][rtl]" value="false">
                                                <input type="checkbox" name="settings[checkout_carousel][rtl]" id="wgb-settings-carousel-rtl" value="true" <?php echo (!empty($itfreegift_settings['checkout_carousel']['rtl']) && $itfreegift_settings['checkout_carousel']['rtl'] == 'true') ? 'checked="checked"' : '' ?>>
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Slide Speed in Milliseconds', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="number" placeholder="<?php esc_html_e('Slide Speed', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[checkout_carousel][speed]" value="<?php echo (isset($itfreegift_settings['checkout_carousel']['speed'])) ? esc_attr($itfreegift_settings['checkout_carousel']['speed']) : '5000'; ?>">
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Number Items in Mobile', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[checkout_carousel][mobile]" value="<?php echo (isset($itfreegift_settings['checkout_carousel']['mobile'])) ? esc_attr($itfreegift_settings['checkout_carousel']['mobile']) : '1' ?>">
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Number Items in Tablet', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[checkout_carousel][tablet]" value="<?php echo (isset($itfreegift_settings['checkout_carousel']['tablet'])) ? esc_attr($itfreegift_settings['checkout_carousel']['tablet']) : '3' ?>">
                                            </div>
                                            <div class="wgb-form-group">
                                                <label><?php esc_html_e('Number Items in Desktop', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[checkout_carousel][desktop]" value="<?php echo (isset($itfreegift_settings['checkout_carousel']['desktop'])) ? esc_attr($itfreegift_settings['checkout_carousel']['desktop']) : '5' ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wgb-form-group">
                                        <label for="wgb-settings-enabled-qty-gift"><?php esc_html_e('Enable Quantity For Gift', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                        <input type="hidden" name="settings[checkout-enabled-qty]" value="false">
                                        <input type="checkbox" name="settings[checkout-enabled-qty]" id="wgb-settings-qty" value="true" <?php echo (!empty($itfreegift_settings['checkout-enabled-qty']) && $itfreegift_settings['checkout-enabled-qty'] == 'true') ? 'checked="checked"' : '' ?>>
                                        <p class="wgb-description"><?php esc_html_e('If checked ,customers can set quantity for each gift', 'ithemeland-free-gifts-for-woo'); ?> *(just for Ajax Manual Add To Cart)</p>
                                    </div>
                                    <div class="wgb-form-group">
                                        <label for="wgb-settings-show-stock-quantity"><?php esc_html_e('Show Available gift Quantity', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <input type="hidden" name="settings[checkout_show_stock_quantity]" value="false">
                                        <input type="checkbox" name="settings[checkout_show_stock_quantity]" id="wgb-settings-show-stock-quantity" value="true" <?php echo (!empty($itfreegift_settings['checkout_show_stock_quantity']) && $itfreegift_settings['checkout_show_stock_quantity'] == 'true') ? 'checked="checked"' : '' ?>>
                                        <p class="wgb-description"><?php esc_html_e("If checked, Number of available gift will be shown for each gift.", 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-notice-checkout"><?php esc_html_e('Display Free Gifts Notice', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[show_notice_checkout]" value="false">
                                    <input type="checkbox" name="settings[show_notice_checkout]" id="wgb-settings-show-notice-checkout" value="true" <?php echo (!empty($itfreegift_settings['show_notice_checkout']) && $itfreegift_settings['show_notice_checkout'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Display Free Gifts If were Available', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div id="wgb-settings-notification-show-notice-checkout-dependencies" style="display: <?php echo (!empty($itfreegift_settings['show_notice_checkout']) && $itfreegift_settings['show_notice_checkout'] == 'true') ? 'block' : 'none'; ?>;">
                                    <div class="wgb-form-group">
                                        <label><?php esc_html_e('Free Gift Notice', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                        <input type="text" placeholder="<?php esc_html_e('Based on your Current Cart Contents, you are eligible for Free Gift(s). Choose your gift', 'ithemeland-free-gifts-for-woo'); ?> [popup_link]" name="localization[notice_checkout_message]" value="<?php echo (isset($localization['itg_localization_notice_checkout_message'])) ? esc_attr($localization['itg_localization_notice_checkout_message']) : 'Based on your Current Cart Contents, you are eligible for Free Gift(s). Choose your gift' . ' ' . '[popup_link]' ?>">
                                        <p class="wgb-description-under-value">[popup_link] : Popup in notice <br /> [cart_link] : link for Redirect to the cart page</p>
                                    </div>
                                    <div class="wgb-form-group">
                                        <label><?php esc_html_e('[popup_link]|[cart_link] Shortcode Label ', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                        <input type="text" placeholder="<?php esc_html_e('Here', 'ithemeland-free-gifts-for-woo'); ?> [popup_link]" name="localization[notice_checkout_message_link_here]" value="<?php echo (isset($localization['itg_localization_notice_checkout_message_link_here'])) ? esc_attr($localization['itg_localization_notice_checkout_message_link_here']) : 'Here' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="wgb-col-12">
                                <button type="submit" class="wgb-button wgb-button-blue wgb-float-left wgb-mt10"><?php esc_html_e("Save Changes", 'ithemeland-free-gifts-for-woo'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>
