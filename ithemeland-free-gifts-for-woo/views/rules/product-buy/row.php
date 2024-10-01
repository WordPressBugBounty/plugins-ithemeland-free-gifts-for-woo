<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wgbl-rule-item-sortable-item" data-id="<?php echo esc_attr($product_buy_id); ?>">
    <button type="button" class="wgbl-rule-item-product-buy-sortable-btn wgbl-button-tr wgbl-float-left"><i class="dashicons dashicons-menu"></i></button>
    <div class="wgbl-w25p">
        <div class="wgbl-form-group">
            <select name="rule[<?php echo esc_attr($rule_id); ?>][product_buy][<?php echo esc_attr($product_buy_id); ?>][type]" class="wgbl-rule-product-buy-product-item wgbl-select2-grouped" data-type="select2">
                <optgroup label="Product">
                    <option value="product" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product') ? 'selected' : ''; ?>><?php esc_html_e('Product', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_variation" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_variation') ? 'selected' : ''; ?>><?php esc_html_e('Product Variation', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_category" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_category') ? 'selected' : ''; ?>><?php esc_html_e('Product Category', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_attribute" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_attribute') ? 'selected' : ''; ?>><?php esc_html_e('Product Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_tag" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_tag') ? 'selected' : ''; ?>><?php esc_html_e('Product Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
                <optgroup label="Product Property">
                    <option value="product_regular_price" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_regular_price') ? 'selected' : ''; ?>><?php esc_html_e('Product regular price', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_is_on_sale" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_is_on_sale') ? 'selected' : ''; ?>><?php esc_html_e('Product is on sale', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_stock_quantity" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_stock_quantity') ? 'selected' : ''; ?>><?php esc_html_e('Product stock quantity', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_shipping_classes" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_shipping_classes') ? 'selected' : ''; ?>><?php esc_html_e('Product shipping class', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <option value="product_meta_field" <?php echo (!empty($product_buy_item['type']) && $product_buy_item['type'] == 'product_meta_field') ? 'selected' : ''; ?>><?php esc_html_e('Product meta field', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="wgbl-product-buy-extra-fields">
        <?php include WGBL_VIEWS_DIR . 'rules/product-buy/extra-field.php'; ?>
    </div>
    <button type="button" class="wgbl-button-tr wgbl-float-right wgbl-product-item-delete"><i class="dashicons dashicons-no-alt"></i></button>
</div>