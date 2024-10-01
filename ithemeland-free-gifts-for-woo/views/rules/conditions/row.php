<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php

if (!empty($condition_item['type'])) :
?>
    <div class="wgbl-rule-item-sortable-item" data-id="<?php echo esc_attr($condition_id); ?>">
        <button type="button" class="wgbl-rule-item-condition-sortable-btn wgbl-button-tr wgbl-float-left"><i class="dashicons dashicons-menu"></i></button>
        <div class="wgbl-w25p">
            <div class="wgbl-form-group">
                <select name="rule[<?php echo esc_attr($rule_id); ?>][condition][<?php echo esc_attr($condition_id); ?>][type]" class="wgbl-rule-condition-item wgbl-select2-grouped" data-type="select2">
                    <optgroup label="<?php esc_html_e('Date & Times', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="date" <?php echo ($condition_item['type'] == 'date') ? 'selected' : ''; ?>><?php esc_html_e('Date', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="time" <?php echo ($condition_item['type'] == 'time') ? 'selected' : ''; ?>><?php esc_html_e('Time', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="date_time" <?php echo ($condition_item['type'] == 'date_time') ? 'selected' : ''; ?>><?php esc_html_e('Date & Time', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="days_of_week" <?php echo ($condition_item['type'] == 'days_of_week') ? 'selected' : ''; ?>><?php esc_html_e('Days of week', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Cart - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="cart_subtotal" <?php echo ($condition_item['type'] == 'cart_subtotal') ? 'selected' : ''; ?>><?php esc_html_e('Cart subtotal Price', 'ithemeland-free-gifts-for-woocommerce'); ?></option>                        
						<option value="cart_total" <?php echo ($condition_item['type'] == 'cart_total') ? 'selected' : ''; ?>><?php esc_html_e('Cart Total Price', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_total_weight" <?php echo ($condition_item['type'] == 'cart_total_weight') ? 'selected' : ''; ?>><?php esc_html_e('Cart total weight', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_total_quantity" <?php echo ($condition_item['type'] == 'cart_total_quantity') ? 'selected' : ''; ?>><?php esc_html_e('Cart Total Quantity', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_count" <?php echo ($condition_item['type'] == 'cart_item_count') ? 'selected' : ''; ?>><?php esc_html_e('Cart item count', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="coupons_applied" <?php echo ($condition_item['type'] == 'coupons_applied') ? 'selected' : ''; ?>><?php esc_html_e('Coupons applied', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Cart Items - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="cart_items_products" <?php echo ($condition_item['type'] == 'cart_items_products') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_items_variations" <?php echo ($condition_item['type'] == 'cart_items_variations') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_items_categories" <?php echo ($condition_item['type'] == 'cart_items_categories') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_items_attributes" <?php echo ($condition_item['type'] == 'cart_items_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_items_tags" <?php echo ($condition_item['type'] == 'cart_items_tags') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_items_shipping_classes" <?php echo ($condition_item['type'] == 'cart_items_shipping_classes') ? 'selected' : ''; ?>><?php esc_html_e('Cart items - Shipping Classes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Cart Items - Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="cart_item_quantity_products" <?php echo ($condition_item['type'] == 'cart_item_quantity_products') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_quantity_variations" <?php echo ($condition_item['type'] == 'cart_item_quantity_variations') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_quantity_categories" <?php echo ($condition_item['type'] == 'cart_item_quantity_categories') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_quantity_attributes" <?php echo ($condition_item['type'] == 'cart_item_quantity_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_quantity_tags" <?php echo ($condition_item['type'] == 'cart_item_quantity_tags') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_quantity_shipping_classes" <?php echo ($condition_item['type'] == 'cart_item_quantity_shipping_classes') ? 'selected' : ''; ?>><?php esc_html_e('Cart item quantity - Shipping Classes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Cart Items - Subtotal - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="cart_item_subtotal_products" <?php echo ($condition_item['type'] == 'cart_item_subtotal_products') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_subtotal_variations" <?php echo ($condition_item['type'] == 'cart_item_subtotal_variations') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_subtotal_categories" <?php echo ($condition_item['type'] == 'cart_item_subtotal_categories') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_subtotal_attributes" <?php echo ($condition_item['type'] == 'cart_item_subtotal_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_subtotal_tags" <?php echo ($condition_item['type'] == 'cart_item_subtotal_tags') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="cart_item_subtotal_shipping_classes" <?php echo ($condition_item['type'] == 'cart_item_subtotal_shipping_classes') ? 'selected' : ''; ?>><?php esc_html_e('Cart item subtotal - Shipping Classes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Checkout - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="checkout_payment_methods" <?php echo ($condition_item['type'] == 'checkout_payment_methods') ? 'selected' : ''; ?>><?php esc_html_e('Payment method', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Checkout - Address - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="shipping_country" <?php echo ($condition_item['type'] == 'shipping_country') ? 'selected' : ''; ?>><?php esc_html_e('Shipping country', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
						<!-- <option value="shipping_state" <?php echo ($condition_item['type'] == 'shipping_state') ? 'selected' : ''; ?>><?php esc_html_e('Shipping state', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
						<option value="shipping_postcode" <?php echo ($condition_item['type'] == 'shipping_postcode') ? 'selected' : ''; ?>><?php esc_html_e('Shipping postcode', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
						<option value="shipping_zone" <?php echo ($condition_item['type'] == 'shipping_zone') ? 'selected' : ''; ?>><?php esc_html_e('Shipping zone', 'ithemeland-free-gifts-for-woocommerce'); ?></option> -->
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Customer - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="customer" <?php echo ($condition_item['type'] == 'customer') ? 'selected' : ''; ?>><?php esc_html_e('Customer', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="user_role" <?php echo ($condition_item['type'] == 'user_role') ? 'selected' : ''; ?>><?php esc_html_e('User role', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="user_capability" <?php echo ($condition_item['type'] == 'user_capability') ? 'selected' : ''; ?>><?php esc_html_e('User Capability', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="user_meta" <?php echo ($condition_item['type'] == 'user_meta') ? 'selected' : ''; ?>><?php esc_html_e('User Meta', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="is_logged_in" <?php echo ($condition_item['type'] == 'is_logged_in') ? 'selected' : ''; ?>><?php esc_html_e('Is logged in', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Customer - Value - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="spent_total" <?php echo ($condition_item['type'] == 'spent_total') ? 'selected' : ''; ?>><?php esc_html_e('Spent - Total', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="spent_average_per_order" <?php echo ($condition_item['type'] == 'spent_average_per_order') ? 'selected' : ''; ?>><?php esc_html_e('Spent - Average per order', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="spent_last_order" <?php echo ($condition_item['type'] == 'spent_last_order') ? 'selected' : ''; ?>><?php esc_html_e('Spent - Last order', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="order_count" <?php echo ($condition_item['type'] == 'order_count') ? 'selected' : ''; ?>><?php esc_html_e('Order count', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="last_order" <?php echo ($condition_item['type'] == 'last_order') ? 'selected' : ''; ?>><?php esc_html_e('Last Order', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="customer_review_count" <?php echo ($condition_item['type'] == 'customer_review_count') ? 'selected' : ''; ?>><?php esc_html_e('Customer review count', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Purchase History - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="purchased_products" <?php echo ($condition_item['type'] == 'purchased_products') ? 'selected' : ''; ?>><?php esc_html_e('Purchased - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="purchased_variations" <?php echo ($condition_item['type'] == 'purchased_variations') ? 'selected' : ''; ?>><?php esc_html_e('Purchased - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="purchased_categories" <?php echo ($condition_item['type'] == 'purchased_categories') ? 'selected' : ''; ?>><?php esc_html_e('Purchased - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="purchased_attributes" <?php echo ($condition_item['type'] == 'purchased_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Purchased - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="purchased_tags" <?php echo ($condition_item['type'] == 'purchased_tags') ? 'selected' : ''; ?>><?php esc_html_e('Purchased - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Purchase History - Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="quantity_purchased_products" <?php echo ($condition_item['type'] == 'quantity_purchased_products') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="quantity_purchased_variations" <?php echo ($condition_item['type'] == 'quantity_purchased_variations') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="quantity_purchased_categories" <?php echo ($condition_item['type'] == 'quantity_purchased_categories') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="quantity_purchased_attributes" <?php echo ($condition_item['type'] == 'quantity_purchased_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="quantity_purchased_tags" <?php echo ($condition_item['type'] == 'quantity_purchased_tags') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Purchase History - Value - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="value_purchased_products" <?php echo ($condition_item['type'] == 'value_purchased_products') ? 'selected' : ''; ?>><?php esc_html_e('Value purchased - Products', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="value_purchased_variations" <?php echo ($condition_item['type'] == 'value_purchased_variations') ? 'selected' : ''; ?>><?php esc_html_e('Value purchased - Variations', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="value_purchased_categories" <?php echo ($condition_item['type'] == 'value_purchased_categories') ? 'selected' : ''; ?>><?php esc_html_e('Value purchased - Categories', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="value_purchased_attributes" <?php echo ($condition_item['type'] == 'value_purchased_attributes') ? 'selected' : ''; ?>><?php esc_html_e('Value purchased - Attributes', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="value_purchased_tags" <?php echo ($condition_item['type'] == 'value_purchased_tags') ? 'selected' : ''; ?>><?php esc_html_e('Value purchased - Tags', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                    <optgroup label="<?php esc_html_e('Purchase Gifts History - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>">
                        <option value="quantity_purchased_all_rules" <?php echo ($condition_item['type'] == 'quantity_purchased_all_rules') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - All rules', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        <option value="quantity_purchased_this_rule" <?php echo ($condition_item['type'] == 'quantity_purchased_this_rule') ? 'selected' : ''; ?>><?php esc_html_e('Quantity purchased - This rule', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="wgbl-condition-extra-fields">
            <?php include WGBL_VIEWS_DIR . 'rules/conditions/extra-field.php'; ?>
        </div>
        <button type="button" class="wgbl-button-tr wgbl-float-right wgbl-condition-delete"><i class="dashicons dashicons-no-alt"></i></button>
    </div>
<?php endif; ?>