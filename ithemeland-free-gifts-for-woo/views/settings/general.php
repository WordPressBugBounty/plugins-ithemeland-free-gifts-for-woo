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
                            <input type="hidden" name="action" value="wgb_save_settings_general">
                            <div id="wgb-settings" class="wgb-col-12">
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-dashboard_date-in-cart"><?php esc_html_e('Report Dashboard date', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <select name="settings[dashboard_date]" id="wgb-settings-dashboard_date-in-cart">
                                        <option value="one_month_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'one_month_ago') ? 'selected' : '' ?>>
                                            <?php esc_html_e("One month ago", 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="the_last_three_months" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_three_months') ? 'selected' : '' ?>>
                                            <?php esc_html_e('The last three months', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="the_last_six_months" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_six_months') ? 'selected' : '' ?>>
                                            <?php esc_html_e('The last six months', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="nine_months_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'nine_months_ago') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Nine months ago', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="once_year_ago" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'once_year_ago') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Once Year Ago', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="the_last_two_years" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_two_years') ? 'selected' : '' ?>>
                                            <?php esc_html_e('The last two years', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="the_last_three_years" <?php echo (!empty($settings['dashboard_date']) && $settings['dashboard_date'] == 'the_last_three_years') ? 'selected' : '' ?>>
                                            <?php esc_html_e('The last three years', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                    <p class="wgb-description"><?php esc_html_e('Set date for dashboard report', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-hide-gift"><?php esc_html_e('Hide Free Gift Products on Shop', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[hide-gift]" value="false">
                                    <input type="checkbox" name="settings[hide-gift]" id="wgb-settings-hide-gift" value="true" <?php echo (!empty($itfreegift_settings['hide-gift']) && $itfreegift_settings['hide-gift'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('The products which are configured to be given as Free Gifts will be hidden in Shop and Category Pages', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-hide-addtocart-gift"><?php esc_html_e('Hide Add To Cart Free Gift Products on Shop', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[hide-addtocart]" value="false">
                                    <input type="checkbox" name="settings[hide-addtocart]" id="wgb-settings-hide-addtocart-gift" value="true" <?php echo (!empty($itfreegift_settings['hide-addtocart']) && $itfreegift_settings['hide-addtocart'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Add To Cart Button which are configured to be given as Free Gifts will be hidden ( Note: dose not working with variable gift type )', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <input type="hidden" name="settings[gift-require-checkout]" value="false">
                                    <label for="wgb-settings-gift-require-checkout"><?php esc_html_e('Forced gift', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="checkbox" name="settings[gift-require-checkout]" id="wgb-settings-gift-require-checkout" value="true" <?php echo (!empty($itfreegift_settings['gift-require-checkout']) && $itfreegift_settings['gift-require-checkout'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('if the gifts is available , the customer must add gift to order', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <input type="hidden" name="settings[automatic_remove_disbale]" value="false">
                                    <label for="wgb-settings-automatic-remove-disbale"><?php esc_html_e('Allow remove the automatically added Gift', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="checkbox" name="settings[automatic_remove_disbale]" id="wgb-settings-automatic-remove-disbale" value="true" <?php echo (!empty($itfreegift_settings['automatic_remove_disbale']) && $itfreegift_settings['automatic_remove_disbale'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('When enabled, a user can remove the automatically added gift products.', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-form-group">
                                    <input type="hidden" name="settings[enable_ajax_add_to_cart]" value="false">
                                    <label for="wgb-settings-enable-ajax-add-to-cart"><?php esc_html_e('Ajax Manual Gift Products Add To Cart', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="checkbox" name="settings[enable_ajax_add_to_cart]" id="wgb-settings-enable-ajax-add-to-cart" value="true" <?php echo (!empty($settings['enable_ajax_add_to_cart']) && $settings['enable_ajax_add_to_cart'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-description"><?php esc_html_e('Show the rule description under the title', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[show_description]" value="false">
                                    <input type="checkbox" name="settings[show_description]" id="wgb-settings-show-description" value="true" <?php echo (!empty($itfreegift_settings['show_description']) && $itfreegift_settings['show_description'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-gift-type-label"><?php esc_html_e('Show Gift Type Label', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[show_gift_type_lable]" value="false">
                                    <input type="checkbox" name="settings[show_gift_type_lable]" id="wgb-settings-show-gift-type-label" value="true" <?php echo (!empty($itfreegift_settings['show_gift_type_lable']) && $itfreegift_settings['show_gift_type_lable'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-hide-inactive-gifts"><?php esc_html_e('Hide Inactive Gifts', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[hide_inactive_gifts]" value="false">
                                    <input type="checkbox" name="settings[hide_inactive_gifts]" id="wgb-settings-hide-inactive-gifts" value="true" <?php echo (!empty($itfreegift_settings['hide_inactive_gifts']) && $itfreegift_settings['hide_inactive_gifts'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Gift Title Length', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="number" placeholder="<?php esc_html_e('Gift Title Length', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[gift_title_Length]" value="<?php echo (isset($itfreegift_settings['gift_title_Length'])) ? esc_attr($itfreegift_settings['gift_title_Length']) : '20'; ?>">
                                    <p class="wgb-description"><?php esc_html_e("How many characters to display in the gift title", 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-variations"><?php esc_html_e('Display variation products separate', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="settings[child]" value="false">
                                    <input type="checkbox" name="settings[child]" id="wgb-settings-show-variations" value="true" <?php echo (!empty($itfreegift_settings['child']) && $itfreegift_settings['child'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>
                                <div class="wgb-form-group wgb-settings-show-variations-dependent" style="margin-top: -8px;">
                                    <label><?php esc_html_e('Variation selection type', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <select name="settings[child_select_type]">
                                        <option value="button_with_popup" <?php echo ((!empty($itfreegift_settings['child_select_type'])) && $itfreegift_settings['child_select_type'] == 'button_with_popup') ? 'selected' : ''; ?>><?php esc_html_e('Button with popup', 'ithemeland-free-gifts-for-woo'); ?></option>
                                        <option value="dropdown" <?php echo ((!empty($itfreegift_settings['child_select_type'])) && $itfreegift_settings['child_select_type'] == 'dropdown') ? 'selected' : ''; ?>><?php esc_html_e('Dropdown', 'ithemeland-free-gifts-for-woo'); ?></option>
                                        <option value="dropdown_and_button" <?php echo ((!empty($itfreegift_settings['child_select_type'])) && $itfreegift_settings['child_select_type'] == 'dropdown_and_button') ? 'selected' : ''; ?>><?php esc_html_e('Dropdown and button', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    </select>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-display_price-in-cart"><?php esc_html_e('Display price for gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[display_price]" id="wgb-settings-display_price-in-cart">
                                        <option value="no" <?php echo (empty($itfreegift_settings['display_price']) || $itfreegift_settings['display_price'] == 'no') ? 'selected' : '' ?>>
                                            <?php esc_html_e("Don't Display Price", 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="yes" <?php echo (!empty($itfreegift_settings['display_price']) && $itfreegift_settings['display_price'] == 'yes') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Strike and Display the Price', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                    <p class="wgb-description"><?php esc_html_e('Display price for gifts are added to cart', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-show-popup"><?php esc_html_e('Show Popup after page load', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[popup_auto_load]" value="false">
                                    <input type="checkbox" name="settings[popup_auto_load]" id="wgb-settings-show-popup" value="true" <?php echo (!empty($itfreegift_settings['popup_auto_load']) && $itfreegift_settings['popup_auto_load'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Display Free Gifts If were Available', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <div class="wgb-form-group wgb-settings-show-popup-dependent">
                                    <label for="wgb-settings-layout-popup"><?php esc_html_e('Layout popup', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <select name="settings[layout_popup]" id="wgb-settings-layout-popup">
                                        <option value="carousel" <?php echo (!empty($itfreegift_settings['layout_popup']) && $itfreegift_settings['layout_popup'] == 'carousel') ? 'selected' : '' ?>>
                                            <?php esc_html_e("Carousel", 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="list" <?php echo (!empty($itfreegift_settings['layout_popup']) && $itfreegift_settings['layout_popup'] == 'list') ? 'selected' : '' ?>>
                                            <?php esc_html_e('List', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                        <option value="grid" <?php echo (!empty($itfreegift_settings['layout_popup']) && $itfreegift_settings['layout_popup'] == 'grid') ? 'selected' : '' ?>>
                                            <?php esc_html_e('Grid', 'ithemeland-free-gifts-for-woo'); ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="wgb-form-group wgb-settings-show-popup-dependent">
                                    <label for="wgb-settings-popup-pages"><?php esc_html_e('Choose pages to show the popup', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <div class="wgb-select2-container">
                                        <select name="settings[popup_pages][]" id="wgb-settings-popup-pages" class="wgb-select2" multiple="multiple" data-placeholder="<?php esc_html_e('Select pages...', 'ithemeland-free-gifts-for-woo'); ?>">
                                            <option value="cart" <?php echo (!empty($itfreegift_settings['popup_pages']) && in_array('cart', $itfreegift_settings['popup_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Cart Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="shop" <?php echo (!empty($itfreegift_settings['popup_pages']) && in_array('shop', $itfreegift_settings['popup_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Shop Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="product" <?php echo (!empty($itfreegift_settings['popup_pages']) && in_array('product', $itfreegift_settings['popup_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Product Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="checkout" <?php echo (!empty($itfreegift_settings['popup_pages']) && in_array('checkout', $itfreegift_settings['popup_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Checkout Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="wgb-form-group">
                                    <h3 style="font-size: 15px; font-weight: 600; margin-top: 15px;"><?php esc_html_e('"Add Gift" Button', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></h3>
                                    <hr style="margin:1px 0 5px 0 !important; width: 20%;">
                                </div>
                                <div class="wgb-form-group">
                                    <label style="line-height: 61px;"><?php esc_html_e('Text Color', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Color', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Text Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_text_color]" value="<?php echo (isset($itfreegift_settings['add_gift_text_color'])) ? esc_attr($itfreegift_settings['add_gift_text_color']) : '#e4003b'; ?>">
                                        </div>
                                    </div>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Hover', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Text Hover Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_text_color_hover]" value="<?php echo (isset($itfreegift_settings['add_gift_text_color_hover'])) ? esc_attr($itfreegift_settings['add_gift_text_color_hover']) : '#fff'; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-form-group">
                                    <label style="line-height: 61px;"><?php esc_html_e('Background Color', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Color', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Background Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_background]" value="<?php echo (isset($itfreegift_settings['add_gift_background'])) ? esc_attr($itfreegift_settings['add_gift_background']) : '#fff'; ?>">
                                        </div>
                                    </div>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Hover', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Background Hover Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_background_hover]" value="<?php echo (isset($itfreegift_settings['add_gift_background_hover'])) ? esc_attr($itfreegift_settings['add_gift_background_hover']) : '#e4003b'; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="wgb-form-group">
                                    <label style="line-height: 61px;"><?php esc_html_e('Border Color', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Color', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Border Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_border_color]" value="<?php echo (isset($itfreegift_settings['add_gift_border_color'])) ? esc_attr($itfreegift_settings['add_gift_border_color']) : '#e4003b'; ?>">
                                        </div>
                                    </div>
                                    <div class="wgb-settings-display-add-gift-color-container">
                                        <span><?php esc_html_e('Hover', 'ithemeland-free-gifts-for-woo'); ?></span>
                                        <div>
                                            <input type="color" placeholder="<?php esc_html_e('Border Hover Color', 'ithemeland-free-gifts-for-woo'); ?> ..." name="settings[add_gift_border_color_hover]" value="<?php echo (isset($itfreegift_settings['add_gift_border_color_hover'])) ? esc_attr($itfreegift_settings['add_gift_border_color_hover']) : '#e4003b'; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="wgb-form-group">
                                                <label for="wgb-settings-show-description-gift"><?php esc_html_e('Show Price rule Description in customer emails', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[show_description]" value="false">
                                                <input type="checkbox" name="settings[show_description]" id="wgb-settings-addtocart" value="true" <?php echo (!empty($itfreegift_settings['show_description']) && $itfreegift_settings['show_description'] == 'true') ? 'checked="checked"' : '' ?>>
                                                <p class="wgb-description"><?php esc_html_e('Enable to add the info about the rule applied in the email sent to customers', 'ithemeland-free-gifts-for-woo'); ?></p>
                                            </div> -->
                                <!-- <div >
                                            WPML settings				
                                        </div>
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-hide-wpml_translate-gift"><?php esc_html_e('Extend the rules to translated contents', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                            <input type="checkbox" name="settings[wpml_translate]" id="wgb-settings-wpml_translate" value="true" <?php echo (!empty($itfreegift_settings['wpml_translate']) && $itfreegift_settings['wpml_translate'] == 'true') ? 'checked="checked"' : '' ?>>
                                            <p class="wgb-description"><?php esc_html_e('If enabled the rules will be applied also to translated products', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div> -->
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