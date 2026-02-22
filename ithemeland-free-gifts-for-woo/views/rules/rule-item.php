<?php

use wgb\classes\repositories\Product;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php
$rule_id = (isset($rule_id)) ? $rule_id : 0;
$rule_item['method'] = (!empty($rule_item['method'])) ? $rule_item['method'] : 'simple';
?>

<div class="wgb-rule-item <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'disable') ? 'wgb-rule-disable' : 'wgb-rule-enable'; ?>" data-id="<?php echo esc_attr($rule_id); ?>">
    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][uid]" value="<?php echo esc_attr($rule_item['uid']); ?>">
    <div class="wgb-rule-header">
        <div class="wgb-float-left">
            <button type="button" class="wgb-rule-sortable-btn wgb-button-tr" data-id="sort"><i class="dashicons dashicons-menu"></i></button>
            <h3 class="wgb-rule-title"><?php echo esc_html($rule_item['rule_name']); ?></h3>
            <div class="wgb-rule-header-details">
                <h4 class="wgb-rule-method-name"><?php echo (!empty($rule_methods[$rule_item['method']])) ? esc_html($rule_methods[$rule_item['method']]) : esc_html__('Unknown method', 'ithemeland-free-gifts-for-woo'); ?></h4>
                <h4 class="wgb-rule-method-id">ID: <?php echo esc_html($rule_item['uid']); ?> </h4>
            </div>
        </div>
        <div class="wgb-float-right">
            <?php if (!empty($site_languages) && is_array($site_languages)) : ?>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][language]" class="wgb-rule-item-language">
                    <option value="all" <?php echo (!empty($rule_item['language']) && $rule_item['language'] == 'all') ? 'selected' : ''; ?>><?php esc_html_e('All Languages', 'ithemeland-free-gifts-for-woo'); ?></option>
                    <?php foreach ($site_languages as $language_key => $language_label) : ?>
                        <option value="<?php echo esc_attr($language_key); ?>" <?php echo (!empty($rule_item['language']) && $rule_item['language'] == $language_key) ? 'selected' : ''; ?>><?php echo esc_html($language_label); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][language]" value="all">
            <?php endif; ?>
            <select name="rule[<?php echo esc_attr($rule_id); ?>][status]" class="wgb-mr5 wgb-rule-item-status">
                <optgroup label="Non-Exclusive">
                    <option value="enable" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'enable') ? 'selected' : ''; ?>><?php esc_html_e('Enable - Apply with other applicable rules', 'ithemeland-free-gifts-for-woo'); ?></option>
                </optgroup>
                <optgroup label="Exclusive - Per Cart Item">
                    <option value="other_applied" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'other_applied') ? 'selected' : ''; ?>><?php esc_html_e('Enable - if other rules are not Applied', 'ithemeland-free-gifts-for-woo'); ?></option>
                </optgroup>
                <optgroup label="Disabled">
                    <option value="disable" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'disable') ? 'selected' : ''; ?>><?php esc_html_e('Disabled', 'ithemeland-free-gifts-for-woo'); ?></option>
                </optgroup>
            </select>
            <button type="button" class="wgb-rule-duplicate wgb-button-tr" data-id="duplicate"><i class="dashicons dashicons-admin-page"></i></button>
            <button type="button" class="wgb-rule-delete wgb-button-tr" data-id="delete"><i class="dashicons dashicons-no-alt"></i></button>
        </div>
    </div>
    <div class="wgb-rule-body">
        <div class="wgb-col-3">
            <div class="wgb-form-group">
                <label><?php esc_html_e('Method', 'ithemeland-free-gifts-for-woo'); ?></label>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][method]" class="wgb-rule-method">
                    <?php
                    if (!empty($rule_methods_grouped)) :
                        foreach ($rule_methods_grouped as $group) :
                            if (!empty($group['methods'])) :
                    ?>
                                <optgroup label="<?php echo esc_attr($group['label']); ?>">
                                    <?php foreach ($group['methods'] as $method_key => $method_label) : ?>
                                        <option value="<?php echo esc_attr($method_key); ?>" <?php echo ($rule_item['method'] == $method_key) ? 'selected' : ''; ?>><?php echo esc_html($method_label); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                    <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>

        <div class="wgb-col-3 wgb-rule-mode-container" style="display: <?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'cheapest_item_in_cart'])) ? 'block' : 'none'; ?>;">
            <div class="wgb-form-group">
                <label><?php esc_html_e('Rule Mode', 'ithemeland-free-gifts-for-woo'); ?></label>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][rule_mode]" class="wgb-rule-mode">
                    <option value="free_gift" <?php echo (isset($rule_item['rule_mode']) && $rule_item['rule_mode'] == 'free_gift') ? 'selected' : ''; ?>><?php esc_html_e('Free Gift', 'ithemeland-free-gifts-for-woo'); ?></option>
                    <option value="discount" <?php echo (!in_array($rule_item['method'], ['simple', 'subtotal', 'cheapest_item_in_cart'])) ? 'disabled' : ''; ?> <?php echo (isset($rule_item['rule_mode']) && $rule_item['rule_mode'] == 'discount') ? 'selected' : ''; ?>><?php esc_html_e('Discount', 'ithemeland-free-gifts-for-woo'); ?> - <?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?> </option>
                </select>
            </div>
        </div>

        <div class="method-dependencies">
            <div class="wgb-col-3 wgb-quantity-item" data-type="quantities-apply-on-cart-item" style="display: <?php echo ($rule_item['method'] == 'cheapest_item_in_cart') ? 'block' : 'none'; ?>">
                <div class="wgb-form-group">
                    <label><?php esc_html_e('Price Type', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <select name="rule[<?php echo esc_attr($rule_id); ?>][quantity][price_type]" <?php echo ($rule_item['method'] != 'cheapest_item_in_cart') ? 'disabled' : ''; ?>>
                        <option value="cart_price" <?php echo (!empty($rule_item['quantity']['price_type']) && $rule_item['quantity']['price_type'] == 'cart_price') ? 'selected' : ''; ?>><?php esc_html_e('Price of the product in the cart', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="subtotal_price" <?php echo (!empty($rule_item['quantity']['price_type']) && $rule_item['quantity']['price_type'] == 'subtotal_price') ? 'selected' : ''; ?>><?php esc_html_e('Sub-Total price of the product in the cart', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="regular_price" <?php echo (!empty($rule_item['quantity']['price_type']) && $rule_item['quantity']['price_type'] == 'regular_price') ? 'selected' : ''; ?>><?php esc_html_e('Regular price of the product', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="sale_price" <?php echo (!empty($rule_item['quantity']['price_type']) && $rule_item['quantity']['price_type'] == 'sale_price') ? 'selected' : ''; ?>><?php esc_html_e('Sale price of the product', 'ithemeland-free-gifts-for-woo'); ?></option>
                    </select>
                </div>
            </div>
            <div class="wgb-col-3" data-type="quantities-based-on" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'display: none;' : ''; ?>">
                <div class="wgb-form-group">
                    <label data-label="quantities-based-on" style="<?php echo ($rule_item['method'] == 'bulk_pricing') ? 'display: none;' : ''; ?>"><?php esc_html_e('Quantities Based On', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <label data-label="price-based-on" style="<?php echo ($rule_item['method'] != 'bulk_pricing') ? 'display: none;' : ''; ?>"><?php esc_html_e('Price Based On', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <select name="rule[<?php echo esc_attr($rule_id); ?>][quantities_based_on]" <?php echo (in_array($rule_item['method'], ['get_group_of_products'])) ? 'disabled' : ''; ?>>
                        <optgroup label="Individual Products">
                            <option value="each_individual_product" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_product') ? 'selected' : ''; ?>><?php esc_html_e('Each individual product', 'ithemeland-free-gifts-for-woo'); ?></option>
                            <option value="each_individual_variation" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_variation') ? 'selected' : ''; ?>><?php esc_html_e('Each individual variation', 'ithemeland-free-gifts-for-woo'); ?></option>
                            <option value="each_individual_cart_line_item" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_cart_line_item') ? 'selected' : ''; ?>><?php esc_html_e('Each individual cart line item', 'ithemeland-free-gifts-for-woo'); ?></option>
                        </optgroup>
                        <optgroup label="All Matched Products">
                            <option value="quantities_added_up_by_category" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'quantities_added_up_by_category') ? 'selected' : ''; ?>><?php esc_html_e('Quantities added up by category', 'ithemeland-free-gifts-for-woo'); ?></option>
                            <option value="all_quantities_added_up" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'all_quantities_added_up') ? 'selected' : ''; ?>><?php esc_html_e('All quantities added up', 'ithemeland-free-gifts-for-woo'); ?></option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="wgb-col-3" data-type="rule_name">
                <div class="wgb-form-group">
                    <label><?php esc_html_e('Rule name', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <input type="text" name="rule[<?php echo esc_attr($rule_id); ?>][rule_name]" value="<?php echo esc_attr($rule_item['rule_name']); ?>" placeholder="Rule name ..." class="wgb-rule-name" required>
                </div>
            </div>
            <div class="wgb-col-12" data-type="description">
                <div class="wgb-form-group">
                    <label><?php esc_html_e('Description', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <input type="text" name="rule[<?php echo esc_attr($rule_id); ?>][description]" value="<?php echo esc_attr($rule_item['description']); ?>" placeholder="Description ...">
                </div>
            </div>
            <div class="wgb-col-12" data-type="quantities">
                <div class="wgb-rule-section">
                    <h3>
                        <?php
                        if ($rule_item['method'] == 'free_shipping') {
                            esc_html_e('Select shipping', 'ithemeland-free-gifts-for-woo');
                        } else {
                            esc_html_e('Quantities & Settings', 'ithemeland-free-gifts-for-woo');
                        }
                        ?>
                    </h3>
                    <div class="wgb-rule-section-content" data-method-type="general" style="display: <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'free_shipping', 'get_group_of_products'])) ? 'none' : 'block'; ?>">
                        <div class="wgb-col-2-5 wgb-quantity-item" style="<?php echo (!in_array($rule_item['method'], ['subtotal', 'buy_x_get_x', 'buy_x_get_y'])) ? 'display: none;' : ''; ?>">
                            <div class="wgb-form-group" data-type="quantities-comparison-operator">
                                <label><?php esc_html_e('Comparison Operator', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][quantity][comparison_operator]" class="wgb-comparison-operator" <?php echo (in_array($rule_item['method'], ['subtotal', 'buy_x_get_x', 'buy_x_get_y', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                                    <option value="" <?php echo (empty($rule_item['quantity']['comparison_operator'])) ? 'selected' : ''; ?>><?php esc_html_e('Select Comparison', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    <option value="greater_than" <?php echo (isset($rule_item['quantity']['comparison_operator']) && $rule_item['quantity']['comparison_operator'] == 'greater_than') ? 'selected' : ''; ?>><?php esc_html_e('Greater than ( > )', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    <option value="greater_than_or_equal" <?php echo (isset($rule_item['quantity']['comparison_operator']) && $rule_item['quantity']['comparison_operator'] == 'greater_than_or_equal') ? 'selected' : ''; ?>><?php esc_html_e('Greater than or equal ( >= )', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    <option value="less_than" <?php echo (isset($rule_item['quantity']['comparison_operator']) && $rule_item['quantity']['comparison_operator'] == 'less_than') ? 'selected' : ''; ?>><?php esc_html_e('Less than ( < )', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    <option value="less_than_or_equal" <?php echo (isset($rule_item['quantity']['comparison_operator']) && $rule_item['quantity']['comparison_operator'] == 'less_than_or_equal') ? 'selected' : ''; ?>><?php esc_html_e('Less than or equal ( <= )', 'ithemeland-free-gifts-for-woo'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="wgb-col-2-5 wgb-quantity-item" data-type="quantities-subtotal-amount" style="<?php echo (!in_array($rule_item['method'], ['subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Subtotal Amount', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <input type="number" min="0" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][subtotal_amount]" required value="<?php echo (!empty($rule_item['quantity']['subtotal_amount'])) ? esc_attr($rule_item['quantity']['subtotal_amount']) : ''; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'simple', 'buy_x_get_x', 'buy_x_get_y', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgb-col-2-5 wgb-quantity-item" data-type="quantities-buy" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat', 'cheapest_item_in_cart'])) ? 'display: none;' : ''; ?>">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Buy', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][buy]" type="number" min="1" placeholder="Quantity" value="<?php echo (!empty($rule_item['quantity']['buy'])) ? esc_attr($rule_item['quantity']['buy']) : ''; ?>" required <?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'subtotal_repeat', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgb-col-2-5 wgb-quantity-item" data-type="quantities-get" style="display: block;">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Get', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][get]" type="number" min="1" placeholder="Quantity" value="<?php echo (!empty($rule_item['quantity']['get'])) ? esc_attr($rule_item['quantity']['get']) : ''; ?>" required <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'free_shipping', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgb-col-3">
                            <div class="wgb-quantity-item" data-type="quantities-same-gift" style="padding-left: 15px; display: <?php echo ($rule_item['method'] != 'cheapest_item_in_cart' && (!isset($rule_item['rule_mode']) || $rule_item['rule_mode'] != 'discount')) ? 'block' : 'none'; ?>">
                                <div class="wgb-form-group wgb-checkbox-group" style="margin-bottom: 5px;">
                                    <label style="margin-top: 5px;">
                                        <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                        <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woo'); ?>
                                    </label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgb-quantity-item" data-type="quantities-auto-add-gift-to-cart" style="padding-left: 15px; display: <?php echo (!isset($rule_item['rule_mode']) || $rule_item['rule_mode'] != 'discount') ? 'block' : 'none'; ?>;">
                                <div class="wgb-form-group wgb-checkbox-group" style="margin-bottom: 5px;">
                                    <label style="margin-top: <?php echo ($rule_item['method'] == 'cheapest_item_in_cart') ? '26px' : '5px'; ?>;">
                                        <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                        <?php esc_html_e('Auto Add Gift To Cart', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span>
                                    </label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'free_shipping', 'get_group_of_products'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wgb-rule-section-content" data-method-type="get_group_of_products" style="display: <?php echo ($rule_item['method'] == 'get_group_of_products') ? 'block' : 'none'; ?>">
                        <div class="wgb-col-3 wgb-quantity-item">
                            <div class="wgb-form-group" data-type="quantities-operator" style="margin-top: 17px;">
                                <label style="width: 60px;float: left;"><?php esc_html_e('Operator', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][quantity][operator]" class="wgb-product-group-operator" <?php echo ($rule_item['method'] != 'get_group_of_products') ? 'disabled' : ''; ?> style="width: 58%;float: left;">
                                    <option value="or" <?php echo (isset($rule_item['quantity']['operator']) && $rule_item['quantity']['operator'] == 'or') ? 'selected' : ''; ?>><?php esc_html_e('OR', 'ithemeland-free-gifts-for-woo'); ?></option>
                                    <option value="and" <?php echo (isset($rule_item['quantity']['operator']) && $rule_item['quantity']['operator'] == 'and') ? 'selected' : ''; ?>><?php esc_html_e('AND', 'ithemeland-free-gifts-for-woo'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="wgb-col-2-5 wgb-quantity-item" data-type="quantities-same-gift">
                            <div class="wgb-form-group wgb-checkbox-group">
                                <label style="margin-top: 19px;">
                                    <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                    <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woo'); ?>
                                </label>
                                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'get_group_of_products') ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="wgb-rule-section-content" data-method-type="bulk_quantity" style="display: <?php echo ($rule_item['method'] == 'bulk_quantity') ? 'block' : 'none'; ?>">
                        <div class="wgb-col-12 wgb-rule-quantities-bulk-quantity-repeatable-items">
                            <?php
                            if (!empty($rule_item['quantity']['items']) && is_array($rule_item['quantity']['items'])) {
                                for ($i = 0; $i < count($rule_item['quantity']['items']); $i++) {
                                    include WGBL_VIEWS_DIR . 'rules/quantities/bulk-quantity/row.php';
                                }
                            } else {
                                $i = 0;
                                include WGBL_VIEWS_DIR . 'rules/quantities/bulk-quantity/row.php';
                            }
                            ?>
                        </div>
                        <div class="wgb-col-6" style="padding-left: 48px;">
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-same-gift">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (!in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (!in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgb-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-bulk-quantity-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woo'); ?></button>
                        </div>
                    </div>
                    <div class="wgb-rule-section-content" data-method-type="bulk_pricing" style="display: <?php echo ($rule_item['method'] == 'bulk_pricing') ? 'block' : 'none'; ?>">
                        <div class="wgb-col-12 wgb-rule-quantities-bulk-pricing-repeatable-items">
                            <?php
                            if (!empty($rule_item['quantity']['items']) && is_array($rule_item['quantity']['items'])) {
                                for ($i = 0; $i < count($rule_item['quantity']['items']); $i++) {
                                    include WGBL_VIEWS_DIR . 'rules/quantities/bulk-pricing/row.php';
                                }
                            } else {
                                $i = 0;
                                include WGBL_VIEWS_DIR . 'rules/quantities/bulk-pricing/row.php';
                            }
                            ?>
                        </div>
                        <div class="wgb-col-6" style="padding-left: 48px;">
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-same-gift">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'bulk_pricing') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart ', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'bulk_pricing') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgb-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-bulk-pricing-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woo'); ?></button>
                        </div>
                    </div>
                    <div class="wgb-rule-section-content" data-method-type="tiered_quantity" style="display: <?php echo ($rule_item['method'] == 'tiered_quantity') ? 'block' : 'none'; ?>">
                        <div class="wgb-col-12 wgb-rule-quantities-tiered-quantity-repeatable-items">
                            <?php
                            if (!empty($rule_item['quantity']['items']) && is_array($rule_item['quantity']['items'])) {
                                for ($i = 0; $i < count($rule_item['quantity']['items']); $i++) {
                                    include WGBL_VIEWS_DIR . 'rules/quantities/tiered-quantity/row.php';
                                }
                            } else {
                                $i = 0;
                                include WGBL_VIEWS_DIR . 'rules/quantities/tiered-quantity/row.php';
                            }
                            ?>
                        </div>
                        <div class="wgb-col-6" style="padding-left: 48px;">
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-same-gift">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'tiered_quantity') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgb-col-6 wgb-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgb-form-group wgb-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgb-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart ', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'tiered_quantity') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgb-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-tiered-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woo'); ?></button>
                        </div>
                    </div>
                    <div class="wgb-rule-section-content" data-method-type="free_shipping" style="display: <?php echo ($rule_item['method'] == 'free_shipping') ? 'block;' : 'none;'; ?> min-height: 40px; ">
                        <div class="wgb-col-6" style="padding-left: 48px;">
                            <div class="wgb-quantity-item" data-type="quantities-free-shipping">
                                <div class="wgb-form-group" style="margin-bottom: 0;">
                                    <div class="wgb-col-4">
                                        <label class="" style="float: left; line-height: 40px;"><?php esc_html_e('Select shipping methods for free', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    </div>
                                    <div class="wgb-col-8">
                                        <select name="rule[<?php echo esc_attr($rule_id); ?>][quantity][free_shipping_methods][]" class="wgb-col-6 wgb-select2" style="float: left" data-type="select2" required multiple <?php echo ($rule_item['method'] != 'free_shipping') ? 'disabled' : ''; ?>>
                                            <?php
                                            if (!empty($shipping_methods_options)) :
                                                foreach ($shipping_methods_options as $zone_id => $zone) :
                                            ?>
                                                    <optgroup label="<?php echo esc_attr($zone['title']); ?>">
                                                        <?php foreach ($zone['options'] as $instance_id => $method) : ?>
                                                            <option value="<?php echo esc_attr($instance_id); ?>" <?php echo !empty($rule_item['quantity']['free_shipping_methods']) && in_array($instance_id, $rule_item['quantity']['free_shipping_methods']) ? 'selected' : ''; ?>><?php echo esc_html($method['title']); ?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wgb-col-12" data-type="product-buy" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'display: none;' : ''; ?>">
                <div class="wgb-rule-section">
                    <h3><?php esc_html_e('Products - Buy', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-rule-section-content">
                        <div class="wgb-product-buy-items">
                            <?php
                            if (!empty($rule_item['product_buy'])) :
                                $product_buy_id = 0;
                                foreach ($rule_item['product_buy'] as $product_buy_item) :
                                    include WGBL_VIEWS_DIR . 'rules/product-buy/row.php';
                                    $product_buy_id++;
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-product-buy-add-product"><?php esc_html_e('Add Product', 'ithemeland-free-gifts-for-woo'); ?></button>
                    </div>
                </div>
            </div>
            <div class="wgb-col-12" data-type="get" style="<?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat', 'cheapest_item_in_cart', 'free_shipping', 'get_group_of_products'])) ? 'display: none;' : ''; ?>">
                <div class="wgb-rule-section">
                    <h3><?php esc_html_e('Products - Get', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-rule-section-content">
                        <div class="wgb-col-12">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Include Products', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_products][]" class="wgb-select2-products-variations wgb-select2-option-values" data-option-name="products" data-type="select2" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['include_products']) && is_array($rule_item['include_products'])) :
                                        foreach ($rule_item['include_products'] as $product_id) :
                                            $product_title = Product::get_product_label_for_rule_fields(intval($product_id));
                                            if (!empty($product_title)) :
                                    ?>
                                                <option value="<?php echo esc_attr($product_id); ?>" selected><?php echo esc_html($product_title) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wgb-col-12">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Include Category/Tag/Taxonomy', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_taxonomy][]" class="wgb-select2-taxonomies wgb-select2-option-values" data-option-name="taxonomies" multiple data-type="select2" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['include_taxonomy']) && is_array($rule_item['include_taxonomy'])) :
                                        foreach ($rule_item['include_taxonomy'] as $taxonomy_id) :
                                            if (!empty($option_values['taxonomies'][$taxonomy_id])) :
                                    ?>
                                                <option value="<?php echo esc_attr($taxonomy_id); ?>" selected><?php echo esc_html($option_values['taxonomies'][$taxonomy_id]) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wgb-col-6">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Exclude Products', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][exclude_products][]" class="wgb-select2-products-variations wgb-select2-option-values" data-option-name="products" data-type="select2" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['exclude_products']) && is_array($rule_item['exclude_products'])) :
                                        foreach ($rule_item['exclude_products'] as $product_id) :
                                            $product_title = Product::get_product_label_for_rule_fields(intval($product_id));
                                            if (!empty($product_title)) :
                                    ?>
                                                <option value="<?php echo esc_attr($product_id); ?>" selected><?php echo esc_html($product_title) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wgb-col-6">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Exclude Category/Tag/Taxonomy', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][exclude_taxonomy][]" class="wgb-select2-taxonomies wgb-select2-option-values" data-option-name="taxonomies" data-type="select2" multiple data-type="select2" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['exclude_taxonomy']) && is_array($rule_item['exclude_taxonomy'])) :
                                        foreach ($rule_item['exclude_taxonomy'] as $taxonomy_id) :
                                            if (!empty($option_values['taxonomies'][$taxonomy_id])) :
                                    ?>
                                                <option value="<?php echo esc_attr($taxonomy_id); ?>" selected><?php echo esc_html($option_values['taxonomies'][$taxonomy_id]) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wgb-col-12" data-type="get_products_group" style="<?php echo ($rule_item['method'] != 'get_group_of_products') ? 'display: none;' : ''; ?>">
                <div class="wgb-rule-section">
                    <h3><?php esc_html_e('Get Products Group', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-rule-section-content">
                        <div class="wgb-rule-products-group-items">
                            <?php
                            if (!empty($rule_item['get_products_group'])) {
                                $group_id = 0;
                                foreach ($rule_item['get_products_group'] as $group_item) {
                                    include WGBL_VIEWS_DIR . 'rules/get_products_group/row.php';
                                    $group_id++;
                                }
                            }
                            ?>
                        </div>
                        <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-add-get-products-group-item"><?php esc_html_e('Add Row', 'ithemeland-free-gifts-for-woo'); ?></button>

                    </div>
                </div>
            </div>
            <div class="wgb-col-12" data-type="conditions">
                <div class="wgb-rule-section">
                    <h3><?php esc_html_e('Conditions', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-rule-section-content">
                        <div class="wgb-condition-items">
                            <?php
                            if (!empty($rule_item['condition'])) :
                                $condition_id = 0;
                                foreach ($rule_item['condition'] as $condition_item) :
                                    include WGBL_VIEWS_DIR . 'rules/conditions/row.php';
                                    $condition_id++;
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-add-condition"><?php esc_html_e('Add Condition', 'ithemeland-free-gifts-for-woo'); ?></button>
                    </div>
                </div>
            </div>
            <div class="wgb-col-12" data-type="promotion" style="<?php echo (!in_array($rule_item['method'], ['subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                <div class="wgb-rule-section">
                    <h3 style="width: auto;"><?php esc_html_e('Promotion Message', 'ithemeland-free-gifts-for-woo'); ?> - <span style="color: #e32626"><?php esc_html_e('In Pro Version', 'ithemeland-free-gifts-for-woo'); ?></span></h3>
                    <label class="wgb-toggleWrapper" style="margin-left: 20px;">
                        <input type="checkbox" class="wgb-promotion-toggle" id="wgb-promotionToggle-<?php echo esc_attr($rule_id); ?>" name="rule[<?php echo esc_attr($rule_id); ?>][promotion][enabled]" value="1" <?php echo (!empty($rule_item['promotion']['enabled'])) ? 'checked="checked"' : ''; ?>>
                        <span></span>
                    </label>
                    <div class="wgb-rule-section-content wgb-promotion-section-dependent">
                        <div class="wgb-form-group">
                            <div class="wgb-col-2-5" data-type="promotion-subtotal" style="display: block;">
                                <div style="display: inline-flex;">
                                    <label><?php esc_html_e('Display From', 'ithemeland-free-gifts-for-woo'); ?></label>
                                    <span class="wgb-tooltip">?
                                        <span class="tooltip-text"><?php esc_attr_e('Set a threshold from which you want to start showing promotion message', 'ithemeland-free-gifts-for-woo'); ?><br><br><?php esc_attr_e('Example: Lets say you offer a gift  for 200  and above. you may want to set 100 here. So that the customer can see the promo text when his cart subtotal reaches 100.', 'ithemeland-free-gifts-for-woo'); ?></span>
                                    </span>
                                </div>
                                <input type="number" name="rule[<?php echo esc_attr($rule_id); ?>][promotion][from]" class="wgb-input" value="<?php echo !empty($rule_item['promotion']['from']) ? esc_attr($rule_item['promotion']['from']) : ''; ?>" placeholder="<?php esc_html_e('Enter price...', 'ithemeland-free-gifts-for-woo'); ?>">
                                <p style="line-height: 30px;">
                                    <?php esc_html_e('leave blank to ignore', 'ithemeland-free-gifts-for-woo'); ?>
                                </p>
                            </div>
                            <div class="wgb-col-6" style="display: block;" data-type="promotion-subtotal">
                                <label><?php esc_html_e('Message', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <textarea name="rule[<?php echo esc_attr($rule_id); ?>][promotion][subtotal_message]" style="width: 100%; height: 60px; padding: 8px;" placeholder="<?php esc_html_e('Spend just {difference_amount} more to receive your free gift!', 'ithemeland-free-gifts-for-woo'); ?>"><?php echo !empty($rule_item['promotion']['subtotal_message']) ? esc_attr($rule_item['promotion']['subtotal_message']) : ''; ?></textarea>
                                <p>
                                    <?php esc_html_e('{difference_amount} -> Difference amount to get gift', 'ithemeland-free-gifts-for-woo'); ?>
                                </p>
                                <p>
                                    <?php esc_html_e('Eg: Spend {difference_amount} more and get a your gift', 'ithemeland-free-gifts-for-woo'); ?>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>