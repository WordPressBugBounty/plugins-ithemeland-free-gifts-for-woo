<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php
$rule_id = (isset($rule_id)) ? $rule_id : 0;
$rule_item['method'] = (!empty($rule_item['method'])) ? $rule_item['method'] : 'simple';
?>

<div class="wgbl-rule-item <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'disable') ? 'wgbl-rule-disable' : 'wgbl-rule-enable'; ?>" data-id="<?php echo esc_attr($rule_id); ?>">
    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][uid]" value="<?php echo esc_attr($rule_item['uid']); ?>">
    <div class="wgbl-rule-header">
        <div class="wgbl-float-left">
            <button type="button" class="wgbl-rule-sortable-btn wgbl-button-tr" data-id="sort"><i class="dashicons dashicons-menu"></i></button>
            <h3 class="wgbl-rule-title"><?php echo esc_html($rule_item['rule_name']); ?></h3>
            <h4 class="wgbl-rule-method-name"><?php echo (!empty($rule_methods[$rule_item['method']])) ? esc_html($rule_methods[$rule_item['method']]) : esc_html__('Unknown method', 'ithemeland-free-gifts-for-woocommerce'); ?></h4>
            <h4 class="wgbl-rule-method-id">ID: <?php echo esc_html($rule_item['uid']); ?> </h4>
        </div>
        <div class="wgbl-float-right">
            <?php if (!empty($site_languages) && is_array($site_languages)) : ?>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][language]" class="wgbl-rule-item-language">
                    <option value="all" <?php echo (!empty($rule_item['language']) && $rule_item['language'] == 'all') ? 'selected' : ''; ?>><?php esc_html_e('All Languages', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                    <?php foreach ($site_languages as $language_key => $language_label) : ?>
                        <option value="<?php echo esc_attr($language_key); ?>" <?php echo (!empty($rule_item['language']) && $rule_item['language'] == $language_key) ? 'selected' : ''; ?>><?php echo esc_html($language_label); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][language]" value="all">
            <?php endif; ?>
            <select name="rule[<?php echo esc_attr($rule_id); ?>][status]" class="wgbl-mr5 wgbl-rule-item-status">
                <optgroup label="Non-Exclusive">
                    <option value="enable" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'enable') ? 'selected' : ''; ?>><?php esc_html_e('Enable - Apply with other applicable rules', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
                <optgroup label="Exclusive - Per Cart Item">
                    <option value="other_applied" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'other_applied') ? 'selected' : ''; ?>><?php esc_html_e('Enable - if other rules are not Applied', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
                <optgroup label="Disabled">
                    <option value="disable" <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'disable') ? 'selected' : ''; ?>><?php esc_html_e('Disabled', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                </optgroup>
            </select>
            <button type="button" class="wgbl-rule-duplicate wgbl-button-tr" data-id="duplicate"><i class="dashicons dashicons-admin-page"></i></button>
            <button type="button" class="wgbl-rule-delete wgbl-button-tr" data-id="delete"><i class="dashicons dashicons-no-alt"></i></button>
        </div>
    </div>
    <div class="wgbl-rule-body">
        <div class="wgbl-col-6">
            <div class="wgbl-form-group">
                <label><?php esc_html_e('Method', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][method]" class="wgbl-rule-method">
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
        <div class="method-dependencies">
            <div class="wgbl-col-3" data-type="quantities-based-on" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                <div class="wgbl-form-group">
                    <label data-label="quantities-based-on" style="<?php echo ($rule_item['method'] == 'bulk_pricing') ? 'display: none;' : ''; ?>"><?php esc_html_e('Quantities Based On', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                    <label data-label="price-based-on" style="<?php echo ($rule_item['method'] != 'bulk_pricing') ? 'display: none;' : ''; ?>"><?php esc_html_e('Price Based On', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                    <select name="rule[<?php echo esc_attr($rule_id); ?>][quantities_based_on]">
                        <optgroup label="Individual Products">
                            <option value="each_individual_product" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_product') ? 'selected' : ''; ?>><?php esc_html_e('Each individual product', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                            <option value="each_individual_variation" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_variation') ? 'selected' : ''; ?>><?php esc_html_e('Each individual variation - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                            <option value="each_individual_cart_line_item" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'each_individual_cart_line_item') ? 'selected' : ''; ?>><?php esc_html_e('Each individual cart line item - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        </optgroup>
                        <optgroup label="All Matched Products">
                            <option value="quantities_added_up_by_category" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'quantities_added_up_by_category') ? 'selected' : ''; ?>><?php esc_html_e('Quantities added up by category - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                            <option value="all_quantities_added_up" <?php echo (!empty($rule_item['quantities_based_on']) && $rule_item['quantities_based_on'] == 'all_quantities_added_up') ? 'selected' : ''; ?>><?php esc_html_e('All quantities added up - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="wgbl-col-3" data-type="rule_name">
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Rule name', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                    <input type="text" name="rule[<?php echo esc_attr($rule_id); ?>][rule_name]" value="<?php echo esc_attr($rule_item['rule_name']); ?>" placeholder="Rule name ..." class="wgbl-rule-name" required>
                </div>
            </div>
            <div class="wgbl-col-12" data-type="description">
                <div class="wgbl-form-group">
                    <label><?php esc_html_e('Description', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                    <input type="text" name="rule[<?php echo esc_attr($rule_id); ?>][description]" value="<?php echo esc_attr($rule_item['description']); ?>" placeholder="Description ...">
                </div>
            </div>
            <div class="wgbl-col-12" data-type="quantities">
                <div class="wgbl-rule-section">
                    <h3><?php esc_html_e('Quantities & Settings', 'ithemeland-free-gifts-for-woocommerce'); ?></h3>
                    <div class="wgbl-rule-section-content" data-method-type="general" style="display: <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity'])) ? 'none' : 'block'; ?>">
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-subtotal-amount" style="<?php echo (!in_array($rule_item['method'], ['subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Subtotal Amount', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <input type="number" min="0" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][subtotal_amount]" value="<?php echo (!empty($rule_item['quantity']['subtotal_amount'])) ? esc_attr($rule_item['quantity']['subtotal_amount']) : ''; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'simple', 'buy_x_get_x', 'buy_x_get_y', 'cheapest_item_in_cart'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-buy" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Buy', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][buy]" type="number" min="1" placeholder="Quantity" value="<?php echo (!empty($rule_item['quantity']['buy'])) ? esc_attr($rule_item['quantity']['buy']) : ''; ?>" required <?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'subtotal_repeat'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-get" style="display: <?php echo ($rule_item['method'] != 'cheapest_item_in_cart') ? 'block' : 'none'; ?>">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Get', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <input name="rule[<?php echo esc_attr($rule_id); ?>][quantity][get]" type="number" min="1" placeholder="Quantity" value="<?php echo (!empty($rule_item['quantity']['get'])) ? esc_attr($rule_item['quantity']['get']) : ''; ?>" required <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'cheapest_item_in_cart'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-same-gift" style="display: <?php echo ($rule_item['method'] != 'cheapest_item_in_cart') ? 'block' : 'none'; ?>">
                            <div class="wgbl-form-group wgbl-checkbox-group">
                                <label>
                                    <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                    <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woocommerce'); ?>
                                </label>
                                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'cheapest_item_in_cart'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-auto-add-gift-to-cart" style="display: <?php echo ($rule_item['method'] != 'cheapest_item_in_cart') ? 'block' : 'none'; ?>">
                            <div class="wgbl-form-group wgbl-checkbox-group">
                                <label>
                                    <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                    <?php esc_html_e('Auto Add Gift To Cart - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?>
                                </label>
                                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing', 'tiered_quantity', 'cheapest_item_in_cart'])) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        <div class="wgbl-col-2-5 wgbl-quantity-item" data-type="quantities-apply-on-cart-item" style="display: <?php echo ($rule_item['method'] == 'cheapest_item_in_cart') ? 'block' : 'none'; ?>">
                            <!--<div class="wgbl-form-group wgbl-checkbox-group">
                                <label>
                                    <input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="apply_on_cart_item" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['apply_on_cart_item']) && $rule_item['quantity']['apply_on_cart_item'] == 'yes') ? 'checked="checked"' : ''; ?>>
                                    <?php esc_html_e("Apply on the same cart line item", 'ithemeland-free-gifts-for-woocommerce'); ?>
                                </label>
                                <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][apply_on_cart_item]" value="<?php echo (!empty($rule_item['quantity']['apply_on_cart_item']) && $rule_item['quantity']['apply_on_cart_item'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'cheapest_item_in_cart') ? 'disabled' : ''; ?>>
                            </div>-->
                        </div>
                    </div>
                    <div class="wgbl-rule-section-content" data-method-type="bulk_quantity" style="display: <?php echo ($rule_item['method'] == 'bulk_quantity') ? 'block' : 'none'; ?>">
                        <div class="wgbl-col-12 wgbl-rule-quantities-bulk-quantity-repeatable-items">
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
                        <div class="wgbl-col-6" style="padding-left: 48px;">
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-same-gift">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (!in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo (!in_array($rule_item['method'], ['bulk_quantity', 'bulk_pricing'])) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgbl-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgbl-float-right wgbl-button wgbl-button-white-green wgbl-bulk-quantity-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                        </div>
                    </div>
                    <div class="wgbl-rule-section-content" data-method-type="bulk_pricing" style="display: <?php echo ($rule_item['method'] == 'bulk_pricing') ? 'block' : 'none'; ?>">
                        <div class="wgbl-col-12 wgbl-rule-quantities-bulk-pricing-repeatable-items">
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
                        <div class="wgbl-col-6" style="padding-left: 48px;">
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-same-gift">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'bulk_pricing') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'bulk_pricing') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgbl-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgbl-float-right wgbl-button wgbl-button-white-green wgbl-bulk-pricing-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                        </div>
                    </div>
                    <div class="wgbl-rule-section-content" data-method-type="tiered_quantity" style="display: <?php echo ($rule_item['method'] == 'tiered_quantity') ? 'block' : 'none'; ?>">
                        <div class="wgbl-col-12 wgbl-rule-quantities-tiered-quantity-repeatable-items">
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
                        <div class="wgbl-col-6" style="padding-left: 48px;">
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-same-gift">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="same_gift" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Same Gift', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][same_gift]" value="<?php echo (!empty($rule_item['quantity']['same_gift']) && $rule_item['quantity']['same_gift'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'tiered_quantity') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                            <div class="wgbl-col-6 wgbl-quantity-item" data-type="quantities-auto-add-gift-to-cart">
                                <div class="wgbl-form-group wgbl-checkbox-group">
                                    <label><input type="checkbox" data-id="<?php echo esc_attr($rule_id); ?>" data-name="auto_add_gift_to_cart" class="wgbl-rule-quantities-checkbox" <?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'checked="checked"' : ''; ?>> <?php esc_html_e('Auto Add Gift To Cart - Pro version', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][quantity][auto_add_gift_to_cart]" value="<?php echo (!empty($rule_item['quantity']['auto_add_gift_to_cart']) && $rule_item['quantity']['auto_add_gift_to_cart'] == 'yes') ? 'yes' : 'no'; ?>" <?php echo ($rule_item['method'] != 'tiered_quantity') ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="wgbl-col-6" style="padding-right: 49px;">
                            <button type="button" class="wgbl-float-right wgbl-button wgbl-button-white-green wgbl-tiered-quantities-add-item"><?php esc_html_e('Add item', 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-12" data-type="product-buy" style="<?php echo (in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat'])) ? 'display: none;' : ''; ?>">
                <div class="wgbl-rule-section">
                    <h3><?php esc_html_e('Products - Buy', 'ithemeland-free-gifts-for-woocommerce'); ?></h3>
                    <div class="wgbl-rule-section-content">
                        <div class="wgbl-product-buy-items">
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
                        <button type="button" class="wgbl-float-right wgbl-button wgbl-button-white-green wgbl-product-buy-add-product"><?php esc_html_e('Add Product', 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                    </div>
                </div>
            </div>
            <div class="wgbl-col-12" data-type="get" style="<?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat', 'cheapest_item_in_cart'])) ? 'display: none;' : ''; ?>">
                <div class="wgbl-rule-section">
                    <h3><?php esc_html_e('Products - Get', 'ithemeland-free-gifts-for-woocommerce'); ?></h3>
                    <div class="wgbl-rule-section-content">
                        <div class="wgbl-col-12">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Include Products', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_products][]" class="wgbl-select2-products-variations wgbl-select2-option-values" data-option-name="products" data-type="select2" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woocommerce'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['include_products']) && is_array($rule_item['include_products'])) :
                                        foreach ($rule_item['include_products'] as $product_id) :
                                            if (!empty($option_values['products'][$product_id])) :
                                    ?>
                                                <option value="<?php echo esc_attr($product_id); ?>" selected><?php echo esc_html($option_values['products'][$product_id]) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wgbl-col-12">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Include Category/Tag/Taxonomy', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_taxonomy][]" class="wgbl-select2-taxonomies wgbl-select2-option-values" data-option-name="taxonomies" multiple data-type="select2" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woocommerce'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
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
                        <div class="wgbl-col-6">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Exclude Products', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][exclude_products][]" class="wgbl-select2-products-variations wgbl-select2-option-values" data-option-name="products" data-type="select2" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woocommerce'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
                                    <?php
                                    if (!empty($rule_item['exclude_products']) && is_array($rule_item['exclude_products'])) :
                                        foreach ($rule_item['exclude_products'] as $product_id) :
                                            if (!empty($option_values['products'][$product_id])) :
                                    ?>
                                                <option value="<?php echo esc_attr($product_id); ?>" selected><?php echo esc_html($option_values['products'][$product_id]) ?></option>
                                    <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wgbl-col-6">
                            <div class="wgbl-form-group">
                                <label><?php esc_html_e('Exclude Category/Tag/Taxonomy', 'ithemeland-free-gifts-for-woocommerce'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][exclude_taxonomy][]" class="wgbl-select2-taxonomies wgbl-select2-option-values" data-option-name="taxonomies" data-type="select2" multiple data-type="select2" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woocommerce'); ?>" <?php echo (in_array($rule_item['method'], ['buy_x_get_x', 'buy_x_get_x_repeat'])) ? 'disabled' : ''; ?>>
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
            <div class="wgbl-col-12" data-type="conditions">
                <div class="wgbl-rule-section">
                    <h3><?php esc_html_e('Conditions', 'ithemeland-free-gifts-for-woocommerce'); ?></h3>
                    <div class="wgbl-rule-section-content">
                        <div class="wgbl-condition-items">
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
                        <button type="button" class="wgbl-float-right wgbl-button wgbl-button-white-green wgbl-add-condition"><?php esc_html_e('Add Condition', 'ithemeland-free-gifts-for-woocommerce'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>