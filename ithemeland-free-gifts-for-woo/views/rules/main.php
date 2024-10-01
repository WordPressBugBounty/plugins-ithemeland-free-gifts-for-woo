<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wgbl-wrap">
    <div class="wgbl-tab-middle-content">
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'rules') : ?>
            <?php include WGBL_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" id="wgbl-rules-form">
            <?php wp_nonce_field('wgbl_post_nonce'); ?>
            <input type="hidden" name="action" value="wgbl_save_rules">
            <div id="wgbl-rules">
                <?php if (!empty($option_values)) : ?>
                    <script>
                        wgblSetOptionValues(<?php echo wp_json_encode($option_values) ?>);
                    </script>
                <?php
                endif;
                if (!empty($rules['items'])) :
                    $rule_id = 0;
                    foreach ($rules['items'] as $rule_item) :
                        if (!empty($rule_item['uid'])) {
                            include WGBL_VIEWS_DIR . 'rules/rule-item.php';
                        }
                        $rule_id++;
                    endforeach;
                endif;
                ?>
            </div>
            <div class="wgbl-col-12" style="padding: 0 !important;">
                <p class="wgbl-empty-rules-box" style="<?php echo (!empty($rules['items'])) ? 'display:none' : ''; ?>"><?php esc_html_e("No rules configured.", 'ithemeland-free-gifts-for-woocommerce'); ?></p>
                <button type="button" class="wgbl-button wgbl-button-white-green wgbl-float-right wgbl-add-rule wgbl-mt10" style="margin-right: 0 !important;"><?php esc_html_e("Add Rule", 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                <button type="button" class="wgbl-button wgbl-button-blue wgbl-float-left wgbl-mt10" id="wgbl-rules-save-changes"><?php esc_html_e("Save Changes", 'ithemeland-free-gifts-for-woocommerce'); ?></button>
            </div>
            <textarea name="option_values" id="wgbl-option-values" style="display: none !important;"></textarea>
        </form>
    </div>
</div>