<?php

use wgb\classes\repositories\Product;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$rule_id = (isset($rule_id)) ? $rule_id : 0;
$rule_item['type'] = (!empty($rule_item['type'])) ? $rule_item['type'] : 'offer_bar';
?>

<div class="wgb-offer-rule-item <?php echo (!empty($rule_item['status']) && $rule_item['status'] == 'disable') ? 'wgb-offer-rule-disable' : 'wgb-offer-rule-enable'; ?>" data-id="<?php echo esc_attr($rule_id); ?>">
    <input type="hidden" name="rule[<?php echo esc_attr($rule_id); ?>][uid]" value="<?php echo esc_attr($rule_item['uid']); ?>">
    <div class="wgb-offer-rule-header">
        <div class="wgb-float-left">
            <button type="button" class="wgb-offer-rule-sortable-btn wgb-button-tr" data-id="sort"><i class="dashicons dashicons-menu"></i></button>
            <h3 class="wgb-offer-rule-title"><?php echo esc_html($rule_item['rule_name']); ?></h3>
            <div class="wgb-offer-rule-header-details">
                <h4 class="wgb-offer-rule-type-name"><?php echo (!empty($rule_types) && !empty($rule_types[$rule_item['type']])) ? esc_html($rule_types[$rule_item['type']]) : 'Unknown Type'; ?></h4>
                <h4 class="wgb-offer-rule-id">ID: <?php echo esc_html($rule_item['uid']); ?> </h4>
            </div>
        </div>
        <div class="wgb-float-right">
            <button type="button" class="wgb-offer-rule-duplicate wgb-button-tr" data-id="duplicate"><i class="dashicons dashicons-admin-page"></i></button>
            <button type="button" class="wgb-offer-rule-delete wgb-button-tr" data-id="delete"><i class="dashicons dashicons-no-alt"></i></button>
        </div>
    </div>
    <div class="wgb-offer-rule-body">
        <div class="wgb-col-6" style="display: none;">
            <div class="wgb-form-group">
                <label><?php esc_html_e('Type', 'ithemeland-free-gifts-for-woo'); ?></label>
                <select name="rule[<?php echo esc_attr($rule_id); ?>][type]" class="wgb-offer-rule-type">
                    <option value="offer_bar" <?php echo ($rule_item['type'] == 'offer_bar') ? 'selected' : ''; ?>><?php esc_html_e('Offer in single', 'ithemeland-free-gifts-for-woo'); ?></option>
                </select>
            </div>
        </div>
        <div class="type-dependencies">
            <div class="wgb-col-6" data-type="rule_name">
                <div class="wgb-form-group">
                    <label><?php esc_html_e('Offer name', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <input type="text" name="rule[<?php echo esc_attr($rule_id); ?>][rule_name]" value="<?php echo esc_attr($rule_item['rule_name']); ?>" placeholder="Offer name ..." class="wgb-offer-rule-name" required>
                </div>
            </div>

            <div class="wgb-col-3">
                <div style="width: 100%; float: left; margin-bottom: 10px;">
                    <label><?php esc_html_e('Choose where to show the notice offer', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <select name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][position]" style="width: 100%;">
                        <option value="woo_before_add_to_cart_form" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_before_add_to_cart_form') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce Before Add To Cart Form', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_after_add_to_cart_form" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_after_add_to_cart_form') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce After Add To Cart Form', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_product_meta_end" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_product_meta_end') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce Product Meta End', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_product_meta_start" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_product_meta_start') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce Product Meta Start', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_after_single_product" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_after_single_product') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce After Single Product', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_before_single_product" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_before_single_product') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce Before Single Product', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_after_single_product_summary" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_after_single_product_summary') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce After Single Product Summary', 'ithemeland-free-gifts-for-woo'); ?></option>
                        <option value="woo_before_single_product_summary" <?php echo (!empty($rule_item['offer_bar']['position']) && $rule_item['offer_bar']['position'] == 'woo_before_single_product_summary') ? 'selected' : ''; ?>><?php esc_html_e('Woocommerce Before Single Product Summary', 'ithemeland-free-gifts-for-woo'); ?></option>
                    </select>
                </div>
            </div>

            <div class="wgb-col-12">
                <div class="wgb-form-group">
                    <label><?php esc_html_e('Message', 'ithemeland-free-gifts-for-woo'); ?></label>
                    <textarea name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][message]" class="wgb-input" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> style="width: 100%; height: 100px;" placeholder="<?php esc_html_e('Buy $100 and Get this product as Free Gift', 'ithemeland-free-gifts-for-woo'); ?>"><?php echo !empty($rule_item['offer_bar']['message']) ? esc_textarea($rule_item['offer_bar']['message']) : ''; ?></textarea>
                </div>
            </div>

            <div class="wgb-col-12">
                <div class="wgb-form-group">

                    <div class="wgb-col-3">
                        <div style="width: 100%; float: left; margin-bottom: 10px;">
                            <label><?php esc_html_e('Background Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                            <input type="color" name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][bg_color]" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> class="wgb-input" value="<?php echo !empty($rule_item['offer_bar']['bg_color']) ? esc_attr($rule_item['offer_bar']['bg_color']) : '#e2e2e2'; ?>">
                        </div>
                    </div>

                    <div class="wgb-col-3">
                        <div style="width: 100%; float: left;">
                            <label><?php esc_html_e('Text Color', 'ithemeland-free-gifts-for-woo'); ?></label>
                            <input type="color" name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][text_color]" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> class="wgb-input" value="<?php echo !empty($rule_item['offer_bar']['text_color']) ? esc_attr($rule_item['offer_bar']['text_color']) : '#000000'; ?>">
                        </div>
                    </div>

                    <div class="wgb-col-3">
                        <div style="width: 100%; float: left;">
                            <label><?php esc_html_e('Text Align', 'ithemeland-free-gifts-for-woo'); ?></label>
                            <button type="button" class="wgb-offer-rule-text-align-button <?php echo (empty($rule_item['offer_bar']['text_align']) || $rule_item['offer_bar']['text_align'] == 'left') ? 'active' : ''; ?>" title="<?php esc_html_e('Left', 'ithemeland-free-gifts-for-woo'); ?>">
                                <input type="radio" style="display: none;" <?php echo (empty($rule_item['offer_bar']['text_align']) || $rule_item['offer_bar']['text_align'] == 'left') ? 'checked="checked"' : ''; ?> name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][text_align]" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> class="wgb-input" value="left">
                                <i class="dashicons dashicons-align-left"></i>
                            </button>
                            <button type="button" class="wgb-offer-rule-text-align-button <?php echo (!empty($rule_item['offer_bar']['text_align']) && $rule_item['offer_bar']['text_align'] == 'center') ? 'active' : ''; ?>" title="<?php esc_html_e('Center', 'ithemeland-free-gifts-for-woo'); ?>">
                                <input type="radio" style="display: none;" <?php echo (!empty($rule_item['offer_bar']['text_align']) && $rule_item['offer_bar']['text_align'] == 'center') ? 'checked="checked"' : ''; ?> name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][text_align]" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> class="wgb-input" value="center">
                                <i class="dashicons dashicons-align-center"></i>
                            </button>
                            <button type="button" class="wgb-offer-rule-text-align-button <?php echo (!empty($rule_item['offer_bar']['text_align']) && $rule_item['offer_bar']['text_align'] == 'right') ? 'active' : ''; ?>" title="<?php esc_html_e('Right', 'ithemeland-free-gifts-for-woo'); ?>">
                                <input type="radio" style="display: none;" <?php echo (!empty($rule_item['offer_bar']['text_align']) && $rule_item['offer_bar']['text_align'] == 'right') ? 'checked="checked"' : ''; ?> name="rule[<?php echo esc_attr($rule_id); ?>][offer_bar][text_align]" <?php echo ($rule_item['type'] != 'offer_bar') ? 'disabled' : ''; ?> class="wgb-input" value="right">
                                <i class="dashicons dashicons-align-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wgb-col-12" data-type="get">
                <div class="wgb-offer-rule-section">
                    <h3><?php esc_html_e('Display For', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-offer-rule-section-content">
                        <div class="wgb-col-12">
                            <div class="wgb-form-group">
                                <label><?php esc_html_e('Products', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_products][]" class="wgb-select2-products-variations wgb-select2-option-values" data-option-name="products" data-type="select2" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>">
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
                                <label><?php esc_html_e('Category/Tag/Taxonomy', 'ithemeland-free-gifts-for-woo'); ?></label>
                                <select name="rule[<?php echo esc_attr($rule_id); ?>][include_taxonomy][]" class="wgb-select2-taxonomies wgb-select2-option-values" data-option-name="taxonomies" multiple data-type="select2" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-free-gifts-for-woo'); ?>">
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
                    </div>
                </div>
            </div>

            <div class="wgb-col-12" data-type="conditions">
                <div class="wgb-offer-rule-section">
                    <h3><?php esc_html_e('Conditions', 'ithemeland-free-gifts-for-woo'); ?></h3>
                    <div class="wgb-offer-rule-section-content">
                        <div class="wgb-condition-items">
                            <?php
                            if (!empty($rule_item['condition'])) :
                                $condition_id = 0;
                                foreach ($rule_item['condition'] as $condition_item) :
                                    include WGBL_VIEWS_DIR . 'offer_rules/conditions/row.php';
                                    $condition_id++;
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <button type="button" class="wgb-float-right wgb-button wgb-button-white-green wgb-add-condition"><?php esc_html_e('Add Condition', 'ithemeland-free-gifts-for-woo'); ?></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>