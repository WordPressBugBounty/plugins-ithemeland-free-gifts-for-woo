<div class="wgb-rule-products-group-item wgb-rule-item-sortable-item" data-id="<?php echo esc_attr($group_id); ?>">
    <button type="button" class="wgb-rule-item-get-products-group-item-sortable-btn wgb-button-tr wgb-float-left"><i class="dashicons dashicons-menu"></i></button>
    <div class="wgb-w10p">
        <div class="wgb-form-group">
            <input type="number" min="1" value="<?php echo (!empty($group_item['quantity'])) ? esc_attr($group_item['quantity']) : 1; ?>" required name="rule[<?php echo esc_attr($rule_id); ?>][get_products_group][<?php echo esc_attr($group_id); ?>][quantity]" placeholder="<?php esc_html_e('Qty', 'ithemeland-free-gifts-for-woocommerce'); ?>">
        </div>
    </div>
    <div class="wgb-w25p">
        <div class="wgb-form-group">
            <select name="rule[<?php echo esc_attr($rule_id); ?>][get_products_group][<?php echo esc_attr($group_id); ?>][type]" class="wgb-rule-products-group-item-type wgb-select2-grouped">
                <optgroup label="Product">
                    <option value="products" <?php echo (!empty($group_item['type']) && $group_item['type'] == 'products') ? 'selected' : ''; ?>><?php esc_html_e('Product', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="variations" <?php echo (!empty($group_item['type']) && $group_item['type'] == 'variations') ? 'selected' : ''; ?>><?php esc_html_e('Product variation', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="categories" <?php echo (!empty($group_item['type']) && $group_item['type'] == 'categories') ? 'selected' : ''; ?>><?php esc_html_e('Product category', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="attributes" <?php echo (!empty($group_item['type']) && $group_item['type'] == 'attributes') ? 'selected' : ''; ?>><?php esc_html_e('Product attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="tags" <?php echo (!empty($group_item['type']) && $group_item['type'] == 'tags') ? 'selected' : ''; ?>><?php esc_html_e('Product tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="wgb-w56p">
        <div class="wgb-form-group" data-type="value">
            <?php
            if (empty($group_item['type'])) {
                $group_item['type'] = 'products';
            }
            $class_name = ($group_item['type'] == 'attributes') ? 'taxonomies' : $group_item['type'];
            include WGBL_VIEWS_DIR . 'rules/get_products_group/value.php';
            ?>
        </div>
    </div>
    <button type="button" class="wgb-button-tr wgb-float-right wgb-get-products-group-item-delete"><i class="dashicons dashicons-no-alt"></i></button>
</div>