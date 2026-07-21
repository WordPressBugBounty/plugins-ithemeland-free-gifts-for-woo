<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use ITFreeGift\classes\repositories\Product;

?>
<select name="rule[<?php echo esc_attr($itfreegift_rule_id); ?>][get_products_group][<?php echo esc_attr($group_id); ?>][value][]" class="wgb-rule-products-group-item-value wgb-select2-<?php echo esc_attr($itfreegift_class_name); ?> wgb-select2-option-values" data-option-name="<?php echo esc_attr($itfreegift_group_item['type']); ?>" data-type="select2" multiple required>
    <?php
    if (!empty($itfreegift_group_item['value']) && is_array($itfreegift_group_item['value'])):
        foreach ($itfreegift_group_item['value'] as $itfreegift_value) :
            if (in_array($itfreegift_group_item['type'], ['products', 'variations'])) {
                $itfreegift_option_title = Product::get_product_label_for_rule_fields(intval($itfreegift_value));
            } else {
                $itfreegift_option_title = (!empty($option_values[$itfreegift_group_item['type']][$itfreegift_value])) ? $option_values[$itfreegift_group_item['type']][$itfreegift_value] : $itfreegift_value;
            }
    ?>
            <option value="<?php echo esc_attr($itfreegift_value); ?>" selected><?php echo esc_html($itfreegift_option_title); ?></option>
    <?php
        endforeach;
    endif;
    ?>
</select>