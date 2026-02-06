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
                            <input type="hidden" name="action" value="wgb_save_settings_localization">
                            <div id="wgb-settings" class="wgb-col-12">
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Free', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Free', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[free]" value="<?php echo (isset($localization['itg_localization_free'])) ? esc_attr($localization['itg_localization_free']) : 'Free' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[our_gift]" value="<?php echo (isset($localization['itg_localization_our_gift'])) ? esc_attr($localization['itg_localization_our_gift']) : 'Our Gift' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Related Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Related Gift', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[related_gift]" value="<?php echo (isset($localization['itg_localization_related_gift'])) ? esc_attr($localization['itg_localization_related_gift']) : 'Related Gift' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Add Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[add_gift]" value="<?php echo (isset($localization['itg_localization_add_gift'])) ? esc_attr($localization['itg_localization_add_gift']) : 'Add Gift' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Select Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Select Gift', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[select_gift]" value="<?php echo (isset($localization['itg_localization_select_gift'])) ? esc_attr($localization['itg_localization_select_gift']) : 'Select Gift' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('No Thanks', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('No Thanks', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[no_thanks]" value="<?php echo (isset($localization['itg_localization_no_thanks'])) ? esc_attr($localization['itg_localization_no_thanks']) : 'No Thanks' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Forced Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('select a gift for proceed', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[gift_require_checkout]" value="<?php echo (isset($localization['itg_localization_gift_require_checkout'])) ? esc_attr($localization['itg_localization_gift_require_checkout']) : 'select a gift for proceed' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Cart Gift Type Label', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Cart Gift Type Label', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[gift_cart_type_label]" value="<?php echo (isset($localization['itg_localization_gift_cart_type_label'])) ? esc_attr($localization['itg_localization_gift_cart_type_label']) : 'Type' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Cart Free Gift Label', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Cart Gift Label', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[gift_cart_label]" value="<?php echo (isset($localization['itg_localization_gift_cart_label'])) ? esc_attr($localization['itg_localization_gift_cart_label']) : 'Free Product' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Free Gift Removed', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[free_gift_removed]" value="<?php echo (isset($localization['itg_localization_free_gift_removed'])) ? esc_attr($localization['itg_localization_free_gift_removed']) : 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e('Success Message when Free Gift was Added to cart', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('Gift product added successfully', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[free_gift_added]" value="<?php echo (isset($localization['itg_localization_free_gift_added'])) ? esc_attr($localization['itg_localization_free_gift_added']) : 'Gift product added successfully' ?>">
                                </div>
                                <div class="wgb-form-group">
                                    <label><?php esc_html_e("Message when the criteria don't match to Gift the Product", 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="text" placeholder="<?php esc_html_e('As of now no Gift Product(s) available based on your Cart Content', 'ithemeland-free-gifts-for-woo'); ?> ..." name="localization[free_gift_empty_message]" value="<?php echo (isset($localization['itg_localization_free_gift_empty_message'])) ? esc_attr($localization['itg_localization_free_gift_empty_message']) : 'As of now no Gift Product(s) available based on your Cart Content' ?>">
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