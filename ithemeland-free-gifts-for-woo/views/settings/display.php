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
                            <input type="hidden" name="action" value="wgb_save_settings_notification">
                            <div id="wgb-settings" class="wgb-col-12">
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-position-in-cart"><?php esc_html_e("'Your Gifts' position", 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[position]" id="wgb-settings-position-in-cart">
                                        <option value="none" <?php echo (!empty($settings['position']) && $settings['position'] == 'none') ? 'selected' : '' ?>>
                                            <?php esc_html_e('None', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="beside_coupon" <?php echo (!empty($settings['position']) && $settings['position'] == 'beside_coupon') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Beside of Coupon Button', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="bottom_cart" <?php echo (!empty($settings['position']) && $settings['position'] == 'bottom_cart') ? 'selected' : '' ?>>
                                            <?php esc_html_e('After The Cart', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <!--<option value="above_cart" <?php echo (!empty($settings['position']) && $settings['position'] == 'above_cart') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Before The Cart', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>-->
                                        <!--
                                        <option value="popup" <?php echo (!empty($settings['position']) && $settings['position'] == 'popup') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Popup in The Cart/Checkout Page ', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        -->
                                    </select>
                                    <p class="wgb-description"><?php esc_html_e("Where do you want to display available gift in cart page?", 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group" data-type="layout-select-box">
                                    <label for="wgb-settings-layout"><?php esc_html_e('Layout', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[layout]" id="wgb-settings-layout">
                                        <option value="grid" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'grid') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Grid', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="carousel" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'carousel') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Carousel', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="datatable" data-type="bottom_cart" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'datatable') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Datatable', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="dropdown" data-type="beside_coupon" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'dropdown') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Dropdown', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <!--
                                        <option value="button" data-type="beside_coupon" <?php echo (!empty($settings['layout']) && $settings['layout'] == 'button') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Button (Display Popup)', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        -->
                                    </select>
                                </div>
                                <div id="wgb-settings-view-gift-in-cart-dependencies">
                                    <div class="wgb-settings-dependency-item" data-type="grid">
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Number Per Page', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" placeholder="<?php esc_html_e('Gift Number Per Page', 'ithemeland-free-gifts-for-woo'); ?> ..." min="1" name="settings[number_per_page]" value="<?php echo (isset($settings['number_per_page'])) ? esc_attr($settings['number_per_page']) : '4' ?>">
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Desktop Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <select name="settings[desktop_columns]" id="desktop_columns">
                                                <option value="wgb-col-md-2" data-type="beside_coupon" <?php echo (!empty($settings['desktop_columns']) && $settings['desktop_columns'] == 'wgb-col-md-2') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-md-3" data-type="beside_coupon" <?php echo (!empty($settings['desktop_columns']) && $settings['desktop_columns'] == 'wgb-col-md-3') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-md-4" data-type="bottom_cart" <?php echo (!empty($settings['desktop_columns']) && $settings['desktop_columns'] == 'wgb-col-md-4') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-md-6" data-type="bottom_cart" <?php echo (!empty($settings['desktop_columns']) && $settings['desktop_columns'] == 'wgb-col-md-6') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-md-12" data-type="wgb-col-md-12" <?php echo (!empty($settings['desktop_columns']) && $settings['desktop_columns'] == 'wgb-col-md-12') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Tablet Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <select name="settings[tablet_columns]" id="tablet_columns">
                                                <option value="wgb-col-sm-2" data-type="wgb-col-sm-2" <?php echo (!empty($settings['tablet_columns']) && $settings['tablet_columns'] == 'wgb-col-sm-2') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-sm-3" data-type="wgb-col-sm-3" <?php echo (!empty($settings['tablet_columns']) && $settings['tablet_columns'] == 'wgb-col-sm-3') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-sm-4" data-type="wgb-col-sm-4" <?php echo (!empty($settings['tablet_columns']) && $settings['tablet_columns'] == 'wgb-col-sm-4') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-sm-6" data-type="wgb-col-sm-6" <?php echo (!empty($settings['tablet_columns']) && $settings['tablet_columns'] == 'wgb-col-sm-6') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-sm-12" data-type="wgb-col-sm-12" <?php echo (!empty($settings['tablet_columns']) && $settings['tablet_columns'] == 'wgb-col-sm-12') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Mobile Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <select name="settings[mobile_columns]" id="mobile_columns">
                                                <option value="wgb-col-2" data-type="wgb-col-2" <?php echo (!empty($settings['mobile_columns']) && $settings['mobile_columns'] == 'wgb-col-2') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('6 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-3" data-type="wgb-col-3" <?php echo (!empty($settings['mobile_columns']) && $settings['mobile_columns'] == 'wgb-col-3') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('4 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-4" data-type="wgb-col-4" <?php echo (!empty($settings['mobile_columns']) && $settings['mobile_columns'] == 'wgb-col-4') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('3 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-6" data-type="wgb-col-6" <?php echo (!empty($settings['mobile_columns']) && $settings['mobile_columns'] == 'wgb-col-6') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('2 Columns', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                                <option value="wgb-col-12" data-type="wgb-col-12" <?php echo (!empty($settings['mobile_columns']) && $settings['mobile_columns'] == 'wgb-col-12') ? 'selected' : '' ?>>
                                                    <?php esc_html_e('1 Column', 'ithemeland-free-gifts-for-woo'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wgb-settings-dependency-item" data-type="carousel">
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-carousel-loop"><?php esc_html_e('Loop', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="hidden" name="settings[carousel][loop]" value="false">
                                            <input type="checkbox" name="settings[carousel][loop]" id="wgb-settings-carousel-loop" value="true" <?php echo (!empty($settings['carousel']['loop']) && $settings['carousel']['loop'] == 'true') ? 'checked="checked"' : '' ?>>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-carousel-dots"><?php esc_html_e('Show Dots', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="hidden" name="settings[carousel][dots]" value="false">
                                            <input type="checkbox" name="settings[carousel][dots]" id="wgb-settings-carousel-dots" value="true" <?php echo (!empty($settings['carousel']['dots']) && $settings['carousel']['dots'] == 'true') ? 'checked="checked"' : '' ?>>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-carousel-nav"><?php esc_html_e('Show Nav', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="hidden" name="settings[carousel][nav]" value="false">
                                            <input type="checkbox" name="settings[carousel][nav]" id="wgb-settings-carousel-nav" value="true" <?php echo (!empty($settings['carousel']['nav']) && $settings['carousel']['nav'] == 'true') ? 'checked="checked"' : '' ?>>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-carousel-rtl"><?php esc_html_e('Right To Left', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="hidden" name="settings[carousel][rtl]" value="false">
                                            <input type="checkbox" name="settings[carousel][rtl]" id="wgb-settings-carousel-rtl" value="true" <?php echo (!empty($settings['carousel']['rtl']) && $settings['carousel']['rtl'] == 'true') ? 'checked="checked"' : '' ?>>
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Slide Speed in Milliseconds', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" placeholder="<?php esc_html_e('Slide Speed', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[carousel][speed]" value="<?php echo (isset($settings['carousel']['speed'])) ? esc_attr($settings['carousel']['speed']) : '5000'; ?>">
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Number Items in Mobile', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[carousel][mobile]" value="<?php echo (isset($settings['carousel']['mobile'])) ? esc_attr($settings['carousel']['mobile']) : '1' ?>">
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Number Items in Tablet', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[carousel][tablet]" value="<?php echo (isset($settings['carousel']['tablet'])) ? esc_attr($settings['carousel']['tablet']) : '3' ?>">
                                        </div>
                                        <div class="wgb-form-group">
                                            <label><?php esc_html_e('Number Items in Desktop', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" placeholder="<?php esc_html_e('Number Items', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[carousel][desktop]" value="<?php echo (isset($settings['carousel']['desktop'])) ? esc_attr($settings['carousel']['desktop']) : '5' ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="wgb-form-group position-dependency" data-type="bottom_cart">
                                    <label for="wgb-settings-show-variations"><?php esc_html_e('Show Variations', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[child]" value="false">
                                    <input type="checkbox" name="settings[child]" id="wgb-settings-show-variations" value="true" <?php echo (!empty($settings['child']) && $settings['child'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Leave it if you want to show variations gifts in popup', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group position-dependency" data-type="bottom_cart">
                                    <label for="wgb-settings-enabled-qty-gift"><?php esc_html_e('Enable Quantity For Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[enabled-qty]" value="false">
                                    <input type="checkbox" name="settings[enabled-qty]" id="wgb-settings-qty" value="true" <?php echo (!empty($settings['enabled-qty']) && $settings['enabled-qty'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('If checked ,customers can set quantity for each gift', 'ithemeland-free-gifts-for-woo'); ?> *(just for Ajax Manual Add To Cart)</p>
                                </div>
                                <div class="wgb-form-group position-dependency" data-type="bottom_cart">
                                    <label for="wgb-settings-show-stock-quantity"><?php esc_html_e('Show Available gift Quantity', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[show_stock_quantity]" value="false">
                                    <input type="checkbox" name="settings[show_stock_quantity]" id="wgb-settings-show-stock-quantity" value="true" <?php echo (!empty($settings['show_stock_quantity']) && $settings['show_stock_quantity'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e("If checked, Number of available gift will be shown for each gift.", 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-display_price-in-cart"><?php esc_html_e('Display price for gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[display_price]" id="wgb-settings-display_price-in-cart">
                                        <option value="no" <?php echo (!empty($settings['display_price']) && $settings['display_price'] == 'beside_coupon') ? 'selected' : '' ?>>
                                            <?php esc_html_e("Don't Display Price", 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="yes" <?php echo (!empty($settings['display_price']) && $settings['display_price'] == 'yes') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Strike and Display the Price', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                    <p class="wgb-description"><?php esc_html_e('Display price for gifts are added to cart', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-description"><?php esc_html_e('Show the rule description under the title', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[show_description]" value="false">
                                    <input type="checkbox" name="settings[show_description]" id="wgb-settings-show-description" value="true" <?php echo (!empty($settings['show_description']) && $settings['show_description'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-layout-popup"><?php esc_html_e('Layout popup', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[layout_popup]" id="wgb-settings-layout-popup">
                                        <option value="carousel" <?php echo (!empty($settings['layout_popup']) && $settings['layout_popup'] == 'carousel') ? 'selected' : '' ?>>
                                            <?php esc_html_e("Carousel", 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="list" <?php echo (!empty($settings['layout_popup']) && $settings['layout_popup'] == 'list') ? 'selected' : '' ?>>
                                            <?php esc_html_e('List', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-description"><?php esc_html_e('Show Gift Type Lable', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[show_gift_type_lable]" value="false">
                                    <input type="checkbox" name="settings[show_gift_type_lable]" id="wgb-settings-show-description" value="true" <?php echo (!empty($settings['show_gift_type_lable']) && $settings['show_gift_type_lable'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Gift Title Length', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="number" placeholder="<?php esc_html_e('Gift Title Length', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[gift_title_Length]" value="<?php echo (isset($settings['gift_title_Length'])) ? esc_attr($settings['gift_title_Length']) : '20'; ?>">
                                    <p class="wgb-description"><?php esc_html_e("How many characters to display in the gift title", 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-popup"><?php esc_html_e('Show Popup after page load in Cart page', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[cart_auto_load]" value="false">
                                    <input type="checkbox" name="settings[cart_auto_load]" id="wgb-settings-show-popup" value="true" <?php echo (!empty($settings['cart_auto_load']) && $settings['cart_auto_load'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Display Free Gifts If were Available', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-popup-checkout"><?php esc_html_e('Show Popup after page load in checkout page', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[checkout_auto_load]" value="false">
                                    <input type="checkbox" name="settings[checkout_auto_load]" id="wgb-settings-show-popup-checkout" value="true" <?php echo (!empty($settings['checkout_auto_load']) && $settings['checkout_auto_load'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Display Free Gifts If were Available', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-notice-checkout"><?php esc_html_e('Display Free Gifts Notice in Checkout Page', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[show_notice_checkout]" value="false">
                                    <input type="checkbox" name="settings[show_notice_checkout]" id="wgb-settings-show-notice-checkout" value="true" <?php echo (!empty($settings['show_notice_checkout']) && $settings['show_notice_checkout'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Display Free Gifts If were Available', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div id="wgb-settings-notification-show-notice-checkout-dependencies" style="display: <?php echo (!empty($settings['show_notice_checkout']) && $settings['show_notice_checkout'] == 'true') ? 'block' : 'none'; ?>;">
                                    <div class="wgb-form-group">
                                        <label><?php esc_html_e('Free Gift Notice in Checkout', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
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