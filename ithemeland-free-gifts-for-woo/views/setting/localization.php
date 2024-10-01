<div class="wgbl-wrap">
    <div class="wgbl-tab-middle-content">
        <?php
        include_once WGBL_VIEWS_DIR . 'setting/tabs.php';
        if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'settings') {
            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
        }
        ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input type="hidden" name="action" value="wgbl_save_settings_localization">
            <?php wp_nonce_field('wgbl_post_nonce'); ?>
            <div id="wgbl-settings" class="wgbl-col-12">
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Free', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Free', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[txt_free]" value="<?php echo (isset($localization['itg_localization_txt_free'])) ? esc_attr($localization['itg_localization_txt_free']) : 'Free' ?>">
                </div>
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[our_gift]" value="<?php echo (isset($localization['itg_localization_our_gift'])) ? esc_attr($localization['itg_localization_our_gift']) : 'Our Gift' ?>">
                </div>
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Add Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Our Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[add_gift]" value="<?php echo (isset($localization['itg_localization_add_gift'])) ? esc_attr($localization['itg_localization_add_gift']) : 'Add Gift' ?>">
                </div>
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Select Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Select Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[select_gift]" value="<?php echo (isset($localization['itg_localization_select_gift'])) ? esc_attr($localization['itg_localization_select_gift']) : 'Select Gift' ?>">
                </div>
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Select Your Gift ( Dropdown Layout )', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Select Your Gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[select_your_gift]" value="<?php echo (isset($localization['itg_localization_select_your_gift'])) ? esc_attr($localization['itg_localization_select_your_gift']) : 'Select Your Gift' ?>">
                </div>
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Free Gift Removed', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></label>
                    <input type="text" placeholder="<?php esc_html_e('Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift', 'ithemeland-free-gifts-for-woocommerce-lite'); ?> ..." name="localization[free_gift_removed]" value="<?php echo (isset($localization['itg_localization_free_gift_removed'])) ? esc_attr($localization['itg_localization_free_gift_removed']) : 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift' ?>">
                </div>
            </div>
            <div class="wgbl-col-12">
                <button type="submit" class="wgbl-button wgbl-button-blue wgbl-float-left wgbl-mt10"><?php esc_html_e("Save Changes", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button>
            </div>
        </form>
    </div>
</div>