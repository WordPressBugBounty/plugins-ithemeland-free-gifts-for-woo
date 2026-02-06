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
                                    <label for="wgb-settings-dashboard_date-in-cart"><?php esc_html_e('Report Dashboard date', 'ithemeland-free-gifts-for-woo'); ?></label>
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
                                    <input type="hidden" name="settings[enable_ajax_add_to_cart]" value="false">
                                    <label for="wgb-settings-enable-ajax-add-to-cart"><?php esc_html_e('Ajax Manual Gift Products Add To Cart', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="checkbox" name="settings[enable_ajax_add_to_cart]" id="wgb-settings-enable-ajax-add-to-cart" value="true" <?php echo (!empty($settings['enable_ajax_add_to_cart']) && $settings['enable_ajax_add_to_cart'] == 'true') ? 'checked="checked"' : '' ?>>
                                </div>

                                <div class="wgb-form-group">
                                    <label for="wgb-settings-hide-gift"><?php esc_html_e('Hide Free Gift Products on Shop', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[hide-gift]" value="false">
                                    <input type="checkbox" name="settings[hide-gift]" id="wgb-settings-hide-product" value="true" <?php echo (!empty($settings['hide-gift']) && $settings['hide-gift'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('The products which are configured to be given as Free Gifts will be hidden in Shop and Category Pages', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <label for="wgb-settings-hide-addtocart-gift"><?php esc_html_e('Hide Add To Cart Free Gift Products on Shop', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="hidden" name="settings[hide-addtocart]" value="false">
                                    <input type="checkbox" name="settings[hide-addtocart]" id="wgb-settings-addtocart" value="true" <?php echo (!empty($settings['hide-addtocart']) && $settings['hide-addtocart'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('Add To Cart Button which are configured to be given as Free Gifts will be hidden ( Note: dose not working with variable gift type )', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <input type="hidden" name="settings[gift-require-checkout]" value="false">
                                    <label for="wgb-settings-gift-require-checkout"><?php esc_html_e('Forced gift', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="checkbox" name="settings[gift-require-checkout]" id="wgb-settings-gift-require-checkout" value="true" <?php echo (!empty($settings['gift-require-checkout']) && $settings['gift-require-checkout'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('if the gifts is available , the customer must add gift to order', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <div class="wgb-form-group">
                                    <input type="hidden" name="settings[automatic_remove_disbale]" value="false">
                                    <label for="wgb-settings-automatic-remove-disbale"><?php esc_html_e('Allow remove the automatically added Gift', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626;">In Pro Version</span></label>
                                    <input type="checkbox" name="settings[automatic_remove_disbale]" id="wgb-settings-automatic-remove-disbale" value="true" <?php echo (!empty($settings['automatic_remove_disbale']) && $settings['automatic_remove_disbale'] == 'true') ? 'checked="checked"' : '' ?>>
                                    <p class="wgb-description"><?php esc_html_e('When enabled, a user can remove the automatically added gift products.', 'ithemeland-free-gifts-for-woo'); ?></p>
                                </div>
                                <!-- <div class="wgb-form-group">
                                                <label for="wgb-settings-show-description-gift"><?php esc_html_e('Show Price rule Description in customer emails', 'ithemeland-free-gifts-for-woo'); ?></label>
                                                <input type="hidden" name="settings[show_description]" value="false">
                                                <input type="checkbox" name="settings[show_description]" id="wgb-settings-addtocart" value="true" <?php echo (!empty($settings['show_description']) && $settings['show_description'] == 'true') ? 'checked="checked"' : '' ?>>
                                                <p class="wgb-description"><?php esc_html_e('Enable to add the info about the rule applied in the email sent to customers', 'ithemeland-free-gifts-for-woo'); ?></p>
                                            </div> -->
                                <!-- <div >
                                            WPML settings				
                                        </div>
                                        <div class="wgb-form-group">
                                            <label for="wgb-settings-hide-wpml_translate-gift"><?php esc_html_e('Extend the rules to translated contents', 'ithemeland-free-gifts-for-woo'); ?></label>
                                            <input type="checkbox" name="settings[wpml_translate]" id="wgb-settings-wpml_translate" value="true" <?php echo (!empty($settings['wpml_translate']) && $settings['wpml_translate'] == 'true') ? 'checked="checked"' : '' ?>>
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