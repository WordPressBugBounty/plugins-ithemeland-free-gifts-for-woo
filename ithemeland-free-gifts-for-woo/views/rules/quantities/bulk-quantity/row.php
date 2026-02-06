<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php if (isset($rule_id)) : ?>
    <div class="wgb-rule-quantities-bulk-quantity-repeatable-item">
        <div class="wgb-col-1-5" style="padding: 0; margin: 2px 0 0 0;">
            <div class="wgb-form-group">
                <button type="button" class="wgb-rule-item-quantities-bulk-quantity-row-sortable-btn wgb-button-tr wgb-float-left"><i class="dashicons dashicons-menu"></i></button>
            </div>
        </div>
        <div class="wgb-col-3-5 wgb-quantity-item" data-type="quantities-from">
            <div class="wgb-form-group">
                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][items][<?php echo esc_attr($i); ?>][from]" type="text" placeholder="<?php esc_html_e('From', 'ithemeland-free-gifts-for-woo'); ?>" value="<?php echo (!empty($rule_item['quantity']['items'][$i]['from'])) ? esc_attr($rule_item['quantity']['items'][$i]['from']) : ''; ?>" <?php echo (!empty($rule_item) && $rule_item['method'] != 'bulk_quantity') ? 'disabled' : ''; ?>>
            </div>
        </div>
        <div class="wgb-col-3-5 wgb-quantity-item" data-type="quantities-to">
            <div class="wgb-form-group">
                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][items][<?php echo esc_attr($i); ?>][to]" type="text" placeholder="<?php esc_html_e('To', 'ithemeland-free-gifts-for-woo'); ?>" value="<?php echo (!empty($rule_item['quantity']['items'][$i]['to'])) ? esc_attr($rule_item['quantity']['items'][$i]['to']) : ''; ?>" <?php echo (!empty($rule_item) && $rule_item['method'] != 'bulk_quantity') ? 'disabled' : ''; ?>>
            </div>
        </div>
        <div class="wgb-col-3-5 wgb-quantity-item" data-type="quantities-get">
            <div class="wgb-form-group">
                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][items][<?php echo esc_attr($i); ?>][get]" type="number" min="1" placeholder="Get" value="<?php echo (isset($rule_item['quantity']['items'][$i]['get'])) ? esc_attr($rule_item['quantity']['items'][$i]['get']) : ''; ?>" required <?php echo (!empty($rule_item) && $rule_item['method'] != 'bulk_quantity') ? 'disabled' : ''; ?>>
            </div>
        </div>
        <div class="wgb-col-1-5">
            <div class="wgb-form-group">
                <button type="button" class="wgb-rules-quantities-bulk-quantity-delete-row-item wgb-button-tr wgb-float-right"><i class="dashicons dashicons-no-alt"></i></button>
            </div>
        </div>
    </div>
<?php endif; ?>