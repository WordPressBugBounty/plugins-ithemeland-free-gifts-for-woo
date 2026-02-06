<?php

use wgb\classes\repositories\Product;

?>
<select name="rule[<?php echo esc_attr($rule_id); ?>][get_products_group][<?php echo esc_attr($group_id); ?>][value][]" class="wgb-rule-products-group-item-value wgb-select2-<?php echo esc_attr($class_name); ?> wgb-select2-option-values" data-option-name="<?php echo esc_attr($group_item['type']); ?>" data-type="select2" multiple required>
    <?php
    if (!empty($group_item['value']) && is_array($group_item['value'])):
        foreach ($group_item['value'] as $value) :
            if (in_array($group_item['type'], ['products', 'variations'])) {
                $option_title = Product::get_product_label_for_rule_fields(intval($value));
            } else {
                $option_title = (!empty($option_values[$group_item['type']][$value])) ? $option_values[$group_item['type']][$value] : $value;
            }
    ?>
            <option value="<?php echo esc_attr($value); ?>" selected><?php echo esc_html($option_title); ?></option>
    <?php
        endforeach;
    endif;
    ?>
</select>