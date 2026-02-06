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
            <div class="wgb-tab-content-item" data-content="rules">
                <div class="wgb-wrap">
                    <div class="wgb-tab-middle-content">
                        <?php
                        if (!empty($flush_message) && is_array($flush_message)) {
                            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
                        }
                        ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" id="wgb-rules-form">
                            <?php wp_nonce_field('wgb_post_nonce'); ?>
                            <input type="hidden" name="action" value="wgb_save_rules">
                            <div id="wgb-rules">
                                <?php if (!empty($option_values)) : ?>
                                    <script>
                                        wgbSetOptionValues(<?php echo json_encode($option_values) ?>);
                                    </script>
                                <?php endif; ?>
                                <?php
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
                            <div class="wgb-col-12" style="padding: 0 !important;">
                                <p class="wgb-empty-rules-box" style="<?php echo (!empty($rules['items'])) ? 'display:none' : ''; ?>"><?php esc_html_e("No rules configured.", 'ithemeland-free-gifts-for-woo'); ?></p>
                                <button type="button" class="wgb-button wgb-button-white-green wgb-float-right wgb-add-rule wgb-mt10" style="margin-right: 0 !important;"><?php esc_html_e("Add Rule", 'ithemeland-free-gifts-for-woo'); ?></button>
                                <button type="button" class="wgb-button wgb-button-blue wgb-float-left wgb-mt10" id="wgb-rules-save-changes"><?php esc_html_e("Save Changes", 'ithemeland-free-gifts-for-woo'); ?></button>
                            </div>
                            <textarea name="option_values" id="wgb-option-values" style="display: none !important;"></textarea>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>