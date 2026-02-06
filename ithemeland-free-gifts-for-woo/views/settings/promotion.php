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
                            <input type="hidden" name="action" value="wgb_save_settings_promotion">
                            <div id="wgb-settings" class="wgb-col-12">

                                <div class="wgb-form-group">
                                    <h3 style="font-size: 15px; font-weight: 600; margin-left: 15px; margin-top: 15px;"><?php esc_html_e('PROMOTION', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span></h3>
                                    <hr style="margin:1px 0 5px 0 !important; width: 20%;">
                                    <p class="wgb-description"><?php esc_html_e('This section allows you to configure simple notices about your gift promotions on the shop / product / cart pages', 'ithemeland-free-gifts-for-woo'); ?></p><br>
                                    <p class="wgb-description"><?php esc_html_e('if enabled an option to add promotion message will displays on each rule', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <!-- Promotion visibility is now controlled per rule via toggle switches -->

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-promotion-pages"><?php esc_html_e('Choose pages to show the notice', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <div class="wgb-col-6">
                                        <select name="settings[promotion_pages][]" id="wgb-settings-promotion-pages" class="wgb-select2" multiple="multiple" data-placeholder="<?php esc_html_e('Select pages...', 'ithemeland-free-gifts-for-woo'); ?>">
                                            <option value="cart" <?php echo (!empty($settings['promotion_pages']) && in_array('cart', $settings['promotion_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Cart Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="shop" <?php echo (!empty($settings['promotion_pages']) && in_array('shop', $settings['promotion_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Shop Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="product" <?php echo (!empty($settings['promotion_pages']) && in_array('product', $settings['promotion_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Product Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                            <option value="checkout" <?php echo (!empty($settings['promotion_pages']) && in_array('checkout', $settings['promotion_pages'])) ? 'selected' : ''; ?>><?php esc_html_e('Checkout Page', 'ithemeland-free-gifts-for-woo'); ?></option>
                                        </select>
                                        <p style="display: block;" class="wgb-description"><?php esc_html_e('Choose pages to show the banner. Only available for subtotal or item quantity based discount rules.', 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>
                                </div>
                                <div class="wgb-form-group ">
                                    <label><?php esc_html_e('Show as:', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <div>
                                        <label class="wgb-col-1">
                                            <input type="radio" id="wgb-settings-promotion-notice-radio" name="settings[promotion][promotion_layout]" value="notice" <?php echo (empty($settings['promotion']['promotion_layout']) || $settings['promotion']['promotion_layout'] == 'notice') ? 'checked="checked"' : ''; ?>>
                                            <?php esc_html_e('Notice', 'ithemeland-free-gifts-for-woo'); ?>
                                        </label>
                                        <label class="wgb-col-1">
                                            <input type="radio" id="wgb-settings-promotion-popup-radio" name="settings[promotion][promotion_layout]" value="popup" <?php echo (!empty($settings['promotion']['promotion_layout']) && $settings['promotion']['promotion_layout'] == 'popup') ? 'checked="checked"' : ''; ?>>
                                            <?php esc_html_e('As Popup', 'ithemeland-free-gifts-for-woo'); ?>
                                        </label>
                                        <label class="wgb-col-1">
                                            <input type="radio" id="wgb-settings-promotion-progressbar-radio" name="settings[promotion][promotion_layout]" value="progressbar" <?php echo (!empty($settings['promotion']['promotion_layout']) && $settings['promotion']['promotion_layout'] == 'progressbar') ? 'checked="checked"' : ''; ?>>
                                            <?php esc_html_e('Progress Bar', 'ithemeland-free-gifts-for-woo'); ?>
                                        </label>
                                    </div>
                                    <p class="wgb-description"><?php esc_html_e('Choose how the message should be shown: as a standard notice or as a popup.', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>

                                <!-- Progress Bar Color Settings -->
                                <div class="wgb-promotion-progressbar-dependent" style="display: none;">
                                    <div class="wgb-form-group wgb-progressbar-bg-color-group">
                                        <label for="wgb-settings-promotion-progressbar-bg-color"><?php esc_html_e('Progress Bar Background Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <input type="color" name="settings[promotion][progressbar_bg_color]" id="wgb-settings-promotion-progressbar-bg-color" value="<?php echo !empty($settings['promotion']['progressbar_bg_color']) ? esc_attr($settings['promotion']['progressbar_bg_color']) : '#4CAF50'; ?>">
                                        <p class="wgb-description"><?php esc_html_e('Choose the background color for progress bar container', 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>
                                    <div class="wgb-form-group wgb-progressbar-fill-color-group">
                                        <label for="wgb-settings-promotion-progressbar-fill-color"><?php esc_html_e('Progress Bar Fill Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <input type="color" name="settings[promotion][progressbar_fill_color]" id="wgb-settings-promotion-progressbar-fill-color" value="<?php echo !empty($settings['promotion']['progressbar_fill_color']) ? esc_attr($settings['promotion']['progressbar_fill_color']) : '#ffffff'; ?>">
                                        <p class="wgb-description"><?php esc_html_e('Choose the fill color for progress bar', 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>
                                    <div class="wgb-form-group wgb-progressbar-text-color-group">
                                        <label for="wgb-settings-promotion-progressbar-text-color"><?php esc_html_e('Progress Bar Text Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <input type="color" name="settings[promotion][progressbar_text_color]" id="wgb-settings-promotion-progressbar-text-color" value="<?php echo !empty($settings['promotion']['progressbar_text_color']) ? esc_attr($settings['promotion']['progressbar_text_color']) : '#ffffff'; ?>">
                                        <p class="wgb-description"><?php esc_html_e('Choose the text color for progress bar', 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>
                                </div>

                                <div class="wgb-promotion-notice-dependent">
                                    <div class="wgb-form-group ">
                                        <label for="wgb-settings-promotion-get-products-template-visibility"><?php esc_html_e('Show Get Products', 'ithemeland-free-gifts-for-woo'); ?></label>
                                        <input type="checkbox" name="settings[promotion][get_products_template_visibility]" id="wgb-settings-promotion-get-products-template-visibility" value="true" <?php echo (!empty($settings['promotion']['get_products_template_visibility']) && $settings['promotion']['get_products_template_visibility'] == 'true') ? 'checked="checked"' : '' ?>>
                                        <p class="wgb-description"><?php esc_html_e('Yes', 'ithemeland-free-gifts-for-woo'); ?></p>
                                    </div>

                                    <div class="wgb-promotion-get-products-templates-dependent">
                                        <div class="wgb-form-group ">
                                            <label for="wgb-settings-promotion-temp-get-product"><?php esc_html_e('Select Template Get Products', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <div class="wgb-col-2">
                                                <select name="settings[promotion-temp-get-product]" id="wgb-settings-promotion-temp-get-product" style="width: 100%;">
                                                    <option value="" <?php echo (!empty($settings['promotion-temp-get-product']) && $settings['promotion-temp-get-product'] == '') ? 'selected' : ''; ?>><?php esc_html_e('Select position...', 'ithemeland-free-gifts-for-woo'); ?></option>
                                                    <option value="glass-morphism" <?php echo (!empty($settings['promotion-temp-get-product']) && $settings['promotion-temp-get-product'] == 'glass-morphism') ? 'selected' : ''; ?>><?php esc_html_e('Glass Morphism', 'ithemeland-free-gifts-for-woo'); ?></option>
                                                    <option value="flex-items" <?php echo (!empty($settings['promotion-temp-get-product']) && $settings['promotion-temp-get-product'] == 'flex-items') ? 'selected' : ''; ?>><?php esc_html_e('Flex Items', 'ithemeland-free-gifts-for-woo'); ?></option>
                                                    <option value="tooltip-style" <?php echo (!empty($settings['promotion-temp-get-product']) && $settings['promotion-temp-get-product'] == 'tooltip-style') ? 'selected' : ''; ?>><?php esc_html_e('Tooltip Style', 'ithemeland-free-gifts-for-woo'); ?></option>
                                                    <option value="gift-box" <?php echo (!empty($settings['promotion-temp-get-product']) && $settings['promotion-temp-get-product'] == 'gift-box') ? 'selected' : ''; ?>><?php esc_html_e('Gift Box', 'ithemeland-free-gifts-for-woo'); ?></option>
                                                </select>
                                            </div>
                                            <p class="wgb-description"><?php esc_html_e('Leave it blank to didnt show the template after promotion notice!', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div>
                                        <div class="wgb-form-group wgb-per-page-group">
                                            <label for="wgb-settings-promotion-product-per-page"><?php esc_html_e('Per Page', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" min="1" name="settings[promotion][per_page]" id="wgb-settings-promotion-product-per-page" value="<?php echo !empty($settings['promotion']['per_page']) ? esc_attr($settings['promotion']['per_page']) : 5; ?>">
                                        </div>
                                        <div class="wgb-form-group wgb-laptop-column-group">
                                            <label for="wgb-settings-promotion-laptop-column"><?php esc_html_e('Laptop Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" min="1" name="settings[promotion][laptop_column]" id="wgb-settings-promotion-laptop-column" value="<?php echo !empty($settings['promotion']['laptop_column']) ? esc_attr($settings['promotion']['laptop_column']) : 6; ?>">
                                        </div>
                                        <div class="wgb-form-group wgb-tablet-column-group">
                                            <label for="wgb-settings-promotion-tablet-column"><?php esc_html_e('Tablet Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" min="1" name="settings[promotion][tablet_column]" id="wgb-settings-promotion-tablet-column" value="<?php echo !empty($settings['promotion']['tablet_column']) ? esc_attr($settings['promotion']['tablet_column']) : 6; ?>">
                                        </div>
                                        <div class="wgb-form-group wgb-phone-column-group">
                                            <label for="wgb-settings-promotion-phone-column"><?php esc_html_e('Phone Columns', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="number" min="1" name="settings[promotion][phone_column]" id="wgb-settings-promotion-phone-column" value="<?php echo !empty($settings['promotion']['phone_column']) ? esc_attr($settings['promotion']['phone_column']) : 4; ?>">
                                        </div>
                                        <div class="wgb-form-group wgb-promotion-template-bg-color-group">
                                            <label for="wgb-settings-promotion-template-bg-color"><?php esc_html_e('Background Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="color" name="settings[promotion][temp_bg_color]" id="wgb-settings-promotion-template-bg-color" value="<?php echo !empty($settings['promotion']['temp_bg_color']) ? esc_attr($settings['promotion']['temp_bg_color']) : '#fff'; ?>">
                                            <p class="wgb-description"><?php esc_html_e('Choose the background color for template', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div>
                                        <div class="wgb-form-group wgb-promotion-template-title-color-group">
                                            <label for="wgb-settings-promotion-template-title-color"><?php esc_html_e('Title Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="color" name="settings[promotion][temp_title_color]" id="wgb-settings-promotion-template-title-color" value="<?php echo !empty($settings['promotion']['temp_title_color']) ? esc_attr($settings['promotion']['temp_title_color']) : '#17141d'; ?>">
                                            <p class="wgb-description"><?php esc_html_e('Choose the title color for template', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div>
                                        <div class="wgb-form-group wgb-promotion-template-title-bg-color-group">
                                            <label for="wgb-settings-promotion-template-title-bg-color"><?php esc_html_e('Title Background Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="color" name="settings[promotion][temp_title_bg_color]" id="wgb-settings-promotion-template-title-bg-color" value="<?php echo !empty($settings['promotion']['temp_title_bg_color']) ? esc_attr($settings['promotion']['temp_title_bg_color']) : '#c8acff'; ?>">
                                            <p class="wgb-description"><?php esc_html_e('Choose the title background color for template', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div>
                                        <div class="wgb-form-group wgb-promotion-template-title-hover-color-group">
                                            <label for="wgb-settings-promotion-template-title-hover-color"><?php esc_html_e('Title Hover Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="color" name="settings[promotion][temp_title_hover_color]" id="wgb-settings-promotion-template-title-hover-color" value="<?php echo !empty($settings['promotion']['temp_title_hover_color']) ? esc_attr($settings['promotion']['temp_title_hover_color']) : '#dd2467'; ?>">
                                            <p class="wgb-description"><?php esc_html_e('Choose the title hover color for template', 'ithemeland-free-gifts-for-woo'); ?></p>
                                        </div>
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

<script>
    jQuery(document).ready(function($) {
        // Function to toggle progress bar settings visibility
        function toggleProgressBarSettings() {
            var selectedLayout = $('input[name="settings[promotion][promotion_layout]"]:checked').val();
            if (selectedLayout === 'progressbar') {
                $('.wgb-promotion-progressbar-dependent').show();
            } else {
                $('.wgb-promotion-progressbar-dependent').hide();
            }
        }

        // Initial check
        toggleProgressBarSettings();

        // Listen for changes in promotion layout
        $('input[name="settings[promotion][promotion_layout]"]').change(function() {
            toggleProgressBarSettings();
        });
    });
</script>

<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>