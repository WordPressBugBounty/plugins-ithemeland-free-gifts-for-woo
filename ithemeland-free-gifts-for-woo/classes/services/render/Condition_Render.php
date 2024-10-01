<?php

namespace wgbl\classes\services\render;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Condition_Render
{
    private static $instance;

    private $render_methods;
    private $rule_id;
    private $condition_item;
    private $condition_id;
    private $option_values;
    private $value;
    private $field_status;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->render_methods = $this->get_render_methods();
    }

    public function set_data($data)
    {
        $this->condition_item = $data['condition_item'];
        $this->rule_id = $data['rule_id'];
        $this->condition_id = $data['condition_id'];
        $this->option_values = $data['option_values'];
        $this->field_status = $data['field_status'];

        if (isset($this->condition_item['value'])) {
            $this->value = (is_array($this->condition_item['value'])) ? array_map('esc_html', $this->condition_item['value']) : esc_html($this->condition_item['value']);
        } else {
            $this->value = '';
        }
    }

    public function extra_fields_render()
    {
        $method = $this->get_render_method($this->condition_item['type']);
        return (method_exists($this, $method)) ? $this->{$method}() : '';
    }

    public function get_all_extra_fields()
    {
        $output = [];
        if (!empty($this->render_methods)) {
            foreach ($this->render_methods as $key => $method) {
                if (method_exists($this, $method)) {
                    $output[$key] = $this->{$method}();
                }
            }
        }

        return $output;
    }

    public function get_render_method($type)
    {
        return (!empty($this->render_methods[$type])) ? $this->render_methods[$type] : false;
    }

    public function get_render_methods()
    {
        return [
            'date' => 'date_render',
            'time' => 'time_render',
            'date_time' => 'date_time_render',
            'days_of_week' => 'days_of_week_render',
            'cart_total' => 'cart_total_render',
            'cart_subtotal' => 'cart_subtotal_render',
            'cart_total_weight' => 'cart_subtotal_render',
            'cart_total_quantity' => 'cart_subtotal_render',
            'cart_item_count' => 'cart_subtotal_render',
            'coupons_applied' => 'coupons_applied_render',
            'cart_items_products' => 'cart_items_products_render',
            'cart_items_variations' => 'cart_items_variations_render',
            'cart_items_categories' => 'cart_items_categories_render',
            'cart_items_attributes' => 'cart_items_attributes_render',
            'cart_items_tags' => 'cart_items_tags_render',
            'cart_items_shipping_classes' => 'cart_items_shipping_classes_render',
            'cart_item_quantity_products' => 'cart_item_quantity_products_render',
            'cart_item_quantity_variations' => 'cart_item_quantity_variations_render',
            'cart_item_quantity_categories' => 'cart_item_quantity_categories_render',
            'cart_item_quantity_attributes' => 'cart_item_quantity_attributes_render',
            'cart_item_quantity_tags' => 'cart_item_quantity_tags_render',
            'cart_item_quantity_shipping_classes' => 'cart_item_quantity_shipping_classes_render',
            'cart_item_subtotal_products' => 'cart_item_subtotal_products_render',
            'cart_item_subtotal_variations' => 'cart_item_subtotal_variations_render',
            'cart_item_subtotal_categories' => 'cart_item_subtotal_categories_render',
            'cart_item_subtotal_attributes' => 'cart_item_subtotal_attributes_render',
            'cart_item_subtotal_tags' => 'cart_item_subtotal_tags_render',
            'cart_item_subtotal_shipping_classes' => 'cart_item_subtotal_shipping_classes_render',
            'checkout_payment_methods' => 'checkout_payment_methods_render',
			'shipping_country' => 'shipping_country_render',
            'customer' => 'customer_render',
            'user_role' => 'user_role_render',
            'user_capability' => 'user_capability_render',
            'user_meta' => 'user_meta_render',
            'is_logged_in' => 'is_logged_in_render',
            'spent_total' => 'spent_total_render',
            'spent_average_per_order' => 'spent_total_render',
            'order_count' => 'spent_total_render',
            'spent_last_order' => 'spent_last_order_render',
            'last_order' => 'last_order_render',
            'customer_review_count' => 'customer_review_count_render',
            'purchased_products' => 'purchased_products_render',
            'purchased_variations' => 'purchased_variations_render',
            'purchased_categories' => 'purchased_categories_render',
            'purchased_attributes' => 'purchased_attributes_render',
            'purchased_tags' => 'purchased_tags_render',
            'quantity_purchased_products' => 'quantity_purchased_products_render',
            'value_purchased_products' => 'quantity_purchased_products_render',
            'quantity_purchased_variations' => 'quantity_purchased_variations_render',
            'value_purchased_variations' => 'quantity_purchased_variations_render',
            'quantity_purchased_categories' => 'quantity_purchased_categories_render',
            'value_purchased_categories' => 'quantity_purchased_categories_render',
            'quantity_purchased_attributes' => 'quantity_purchased_attributes_render',
            'value_purchased_attributes' => 'quantity_purchased_attributes_render',
            'quantity_purchased_tags' => 'quantity_purchased_tags_render',
            'value_purchased_tags' => 'quantity_purchased_tags_render',
            'quantity_purchased_all_rules' => 'spent_total_render',
            'quantity_purchased_this_rule' => 'spent_total_render',
        ];
    }

    private function date_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="from" ' . (($this->condition_item['method_option'] == 'from') ? 'selected' : '') . '>From</option>
                <option value="to" ' . (($this->condition_item['method_option'] == 'to') ? 'selected' : '') . '>To</option>
                <option value="specific_date" ' . (($this->condition_item['method_option'] == 'specific_date') ? 'selected' : '') . '>Specific Date</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" type="text" class="wgbl-datepicker" value="' . $this->value . '" placeholder="Select ..." required ' . $this->field_status . ' autocomplete="off">
                </div>
                </div>';
        return $html;
    }

    private function time_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="from" ' . (($this->condition_item['method_option'] == 'from') ? 'selected' : '') . '>From</option>
                <option value="to" ' . (($this->condition_item['method_option'] == 'to') ? 'selected' : '') . '>To</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" type="text" class="wgbl-timepicker" value="' . $this->value . '" placeholder="Select ..." required ' . $this->field_status . ' autocomplete="off">
                </div>
                </div>';
        return $html;
    }

    private function date_time_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="from" ' . (($this->condition_item['method_option'] == 'from') ? 'selected' : '') . '>From</option>
                <option value="to" ' . (($this->condition_item['method_option'] == 'to') ? 'selected' : '') . '>To</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" type="text" class="wgbl-datetimepicker" value="' . $this->value . '" placeholder="Select ..." required ' . $this->field_status . ' autocomplete="off">
                </div>
                </div>';
        return $html;
    }

    private function days_of_week_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value][]" class="wgbl-select2" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>
                <option value="0" ' . ((is_array($this->value) && in_array('0', $this->value)) ? 'selected' : '') . '>Sunday</option>
                <option value="1" ' . ((is_array($this->value) && in_array('1', $this->value)) ? 'selected' : '') . '>Monday</option>
                <option value="2" ' . ((is_array($this->value) && in_array('2', $this->value)) ? 'selected' : '') . '>Tuesday</option>
                <option value="3" ' . ((is_array($this->value) && in_array('3', $this->value)) ? 'selected' : '') . '>Wednesday</option>
                <option value="4" ' . ((is_array($this->value) && in_array('4', $this->value)) ? 'selected' : '') . '>Thursday</option>
                <option value="5" ' . ((is_array($this->value) && in_array('5', $this->value)) ? 'selected' : '') . '>Friday</option>
                <option value="6" ' . ((is_array($this->value) && in_array('6', $this->value)) ? 'selected' : '') . '>Saturday</option>
                </select>
                </div>
                </div>';
        return $html;
    }

    private function cart_subtotal_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" type="number" min="0" placeholder="0.00" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_total_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" type="number" min="0" placeholder="0.00" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function coupons_applied_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]" class="wgbl-condition-coupons-applied-type">
                <option value="at_least_one_any" ' . (($this->condition_item['method_option'] == 'at_least_one_any') ? 'selected' : '') . '>at least one of any</option>
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                <option value="none_at_all" ' . (($this->condition_item['method_option'] == 'none_at_all') ? 'selected' : '') . '>none at all</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <div class="wgbl-condition-extra-fields-col-4">';
        $html .= $this->get_coupons_extra_fields();
        $html .= '</div>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_products_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][products][]" class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['products']) && is_array($this->condition_item['products'])) {
            foreach ($this->condition_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . esc_attr($product_id) . '" selected>' . esc_html($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_variations_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][variations][]" class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['variations']) && is_array($this->condition_item['variations'])) {
            foreach ($this->condition_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . esc_attr($variation_id) . '" selected>' . esc_html($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_categories_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][categories][]" class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['categories']) && is_array($this->condition_item['categories'])) {
            foreach ($this->condition_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . esc_attr($category_id) . '" selected>' . esc_html($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_attributes_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][attributes][]" class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['attributes']) && is_array($this->condition_item['attributes'])) {
            foreach ($this->condition_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . esc_attr($attribute_id) . '" selected>' . esc_html($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_tags_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][tags][]" class="wgbl-select2-tags wgbl-select2-option-values" data-option-name="tags" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['tags']) && is_array($this->condition_item['tags'])) {
            foreach ($this->condition_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . esc_attr($tag_id) . '" selected>' . esc_html($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_items_shipping_classes_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][shipping_classes][]" class="wgbl-select2-shipping_classes wgbl-select2-option-values" data-option-name="shipping_classes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['shipping_classes']) && is_array($this->condition_item['shipping_classes'])) {
            foreach ($this->condition_item['shipping_classes'] as $shipping_class_id) {
                if (!empty($this->option_values['shipping_classes'][$shipping_class_id])) {
                    $html .= '<option value="' . esc_attr($shipping_class_id) . '" selected>' . esc_html($this->option_values['shipping_classes'][$shipping_class_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_quantity_products_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][products][]" class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['products']) && is_array($this->condition_item['products'])) {
            foreach ($this->condition_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . esc_attr($product_id) . '" selected>' . esc_html($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_quantity_variations_render()
    {
        $html = '<div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][variations][]" class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['variations']) && is_array($this->condition_item['variations'])) {
            foreach ($this->condition_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . esc_attr($variation_id) . '" selected>' . esc_html($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function cart_item_quantity_categories_render()
    {
        $html = '<div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][categories][]" class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['categories']) && is_array($this->condition_item['categories'])) {
            foreach ($this->condition_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . esc_attr($category_id) . '" selected>' . esc_html($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function cart_item_quantity_attributes_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][attributes][]" class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['attributes']) && is_array($this->condition_item['attributes'])) {
            foreach ($this->condition_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . esc_attr($attribute_id) . '" selected>' . esc_html($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_quantity_tags_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][tags][]" class="wgbl-select2-tags wgbl-select2-option-values" data-option-name="tags" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['tags']) && is_array($this->condition_item['tags'])) {
            foreach ($this->condition_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . esc_attr($tag_id) . '" selected>' . esc_html($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_quantity_shipping_classes_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][shipping_classes][]" class="wgbl-select2-shipping_classes wgbl-select2-option-values" data-option-name="shipping_classes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['shipping_classes']) && is_array($this->condition_item['shipping_classes'])) {
            foreach ($this->condition_item['shipping_classes'] as $shipping_class_id) {
                if (!empty($this->option_values['shipping_classes'][$shipping_class_id])) {
                    $html .= '<option value="' . esc_attr($shipping_class_id) . '" selected>' . esc_html($this->option_values['shipping_classes'][$shipping_class_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_products_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][products][]" class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['products']) && is_array($this->condition_item['products'])) {
            foreach ($this->condition_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . esc_attr($product_id) . '" selected>' . esc_html($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_variations_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][variations][]" class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['variations']) && is_array($this->condition_item['variations'])) {
            foreach ($this->condition_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . esc_attr($variation_id) . '" selected>' . esc_html($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_categories_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][categories][]" class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['categories']) && is_array($this->condition_item['categories'])) {
            foreach ($this->condition_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . esc_attr($category_id) . '" selected>' . esc_html($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_attributes_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][attributes][]" class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['attributes']) && is_array($this->condition_item['attributes'])) {
            foreach ($this->condition_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . esc_attr($attribute_id) . '" selected>' . esc_html($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_tags_render()
    {
        $html = '<div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][tags][]" class="wgbl-select2-tags wgbl-select2-option-values" data-option-name="tags" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['tags']) && is_array($this->condition_item['tags'])) {
            foreach ($this->condition_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . esc_attr($tag_id) . '" selected>' . esc_html($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w32p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function cart_item_subtotal_shipping_classes_render()
    {
        $html = '<div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][shipping_classes][]" class="wgbl-select2-shipping_classes wgbl-select2-option-values" data-option-name="shipping_classes" data-type="select2" data-placeholder="Select values ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['shipping_classes']) && is_array($this->condition_item['shipping_classes'])) {
            foreach ($this->condition_item['shipping_classes'] as $shipping_class_id) {
                if (!empty($this->option_values['shipping_classes'][$shipping_class_id])) {
                    $html .= '<option value="' . esc_attr($shipping_class_id) . '" selected>' . esc_html($this->option_values['shipping_classes'][$shipping_class_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w32p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function checkout_payment_methods_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][payment_methods][]" class="wgbl-select2-payment-methods wgbl-select2-option-values" data-option-name="payment_methods" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['payment_methods']) && is_array($this->condition_item['payment_methods'])) {
            foreach ($this->condition_item['payment_methods'] as $payment_id) {
                if (!empty($this->option_values['payment_methods'][$payment_id])) {
                    $html .= '<option value="' . esc_attr($payment_id) . '" selected>' . esc_html($this->option_values['payment_methods'][$payment_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }    
	
	private function shipping_country_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][shipping_country][]" class="wgbl-select2-shipping-country wgbl-select2-option-values" data-option-name="shipping_country" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['shipping_country']) && is_array($this->condition_item['shipping_country'])) {
            foreach ($this->condition_item['shipping_country'] as $code) {
                if (!empty($this->option_values['shipping_country'][$code])) {
                    $html .= '<option value="' . esc_attr($code) . '" selected>' . esc_html($this->option_values['shipping_country'][$code]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function customer_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][customers][]" class="wgbl-select2-customers wgbl-select2-option-values" data-option-name="customers" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['customers']) && is_array($this->condition_item['customers'])) {
            foreach ($this->condition_item['customers'] as $customer_id) {
                if (!empty($this->option_values['customers'][$customer_id])) {
                    $html .= '<option value="' . esc_attr($customer_id) . '" selected>' . esc_html($this->option_values['customers'][$customer_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function user_role_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][user_roles][]" class="wgbl-select2-user-roles" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['user_roles']) && is_array($this->condition_item['user_roles'])) {
            foreach ($this->condition_item['user_roles'] as $user_role_id) {
                if (!empty($this->option_values['user_roles'][$user_role_id])) {
                    $html .= '<option value="' . esc_attr($user_role_id) . '" selected>' . esc_html($this->option_values['user_roles'][$user_role_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function user_capability_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="in_list" ' . (($this->condition_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
        <option value="not_in_list" ' . (($this->condition_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w70p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][user_capabilities][]" class="wgbl-select2-user-capabilities" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['user_capabilities']) && is_array($this->condition_item['user_capabilities'])) {
            foreach ($this->condition_item['user_capabilities'] as $user_capability_id) {
                if (!empty($this->option_values['user_capabilities'][$user_capability_id])) {
                    $html .= '<option value="' . esc_attr($user_capability_id) . '" selected>' . esc_html($this->option_values['user_capabilities'][$user_capability_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>';
        return $html;
    }

    private function user_meta_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][meta_field_key]" value="' . ((isset($this->condition_item['meta_field_key'])) ? esc_attr($this->condition_item['meta_field_key']) : '') . '" type="text" placeholder="meta field key" required ' . $this->field_status . '>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]" class="wgbl-condition-user-meta-field-type">
                <option value="is_empty" ' . (($this->condition_item['method_option'] == 'is_empty') ? 'selected' : '') . '>is empty</option>
                <option value="is_not_empty" ' . (($this->condition_item['method_option'] == 'is_not_empty') ? 'selected' : '') . '>is not empty</option>
                <option value="contains" ' . (($this->condition_item['method_option'] == 'contains') ? 'selected' : '') . '>contains</option>
                <option value="does_not_contain" ' . (($this->condition_item['method_option'] == 'does_not_contain') ? 'selected' : '') . '>does not contain</option>
                <option value="begins_with" ' . (($this->condition_item['method_option'] == 'begins_with') ? 'selected' : '') . '>begins with</option>
                <option value="ends_with" ' . (($this->condition_item['method_option'] == 'ends_with') ? 'selected' : '') . '>ends with</option>
                <option value="equals" ' . (($this->condition_item['method_option'] == 'equals') ? 'selected' : '') . '>equals</option>
                <option value="does_not_equal" ' . (($this->condition_item['method_option'] == 'does_not_equal') ? 'selected' : '') . '>does not equal</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                <option value="less_or_equal_to" ' . (($this->condition_item['method_option'] == 'less_or_equal_to') ? 'selected' : '') . '>less or equal to</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="more_or_equal" ' . (($this->condition_item['method_option'] == 'more_or_equal') ? 'selected' : '') . '>more or equal to</option>
                <option value="is_checked" ' . (($this->condition_item['method_option'] == 'is_checked') ? 'selected' : '') . '>is checked</option>
                <option value="is_not_checked" ' . (($this->condition_item['method_option'] == 'is_not_checked') ? 'selected' : '') . '>is not checked</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <div class="wgbl-condition-extra-fields-col-4">';
        $html .= $this->get_user_meta_field_extra_fields();
        $html .= '</div>
                </div>
                </div>';
        return $html;
    }

    private function is_logged_in_render()
    {
        $html = '<div class="wgbl-w100p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" required ' . $this->field_status . '>
                <option value="yes" ' . (($this->value == 'yes') ? 'selected' : '') . '>Yes</option>
                <option value="no" ' . (($this->value == 'no') ? 'selected' : '') . '>No</option>
                </select>
                </div>
                </div>';
        return $html;
    }

    private function spent_total_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0.00" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function spent_last_order_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w70p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0.00" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function last_order_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="later" ' . (($this->condition_item['method_option'] == 'later') ? 'selected' : '') . '>within past</option>
                <option value="earlier" ' . (($this->condition_item['method_option'] == 'earlier') ? 'selected' : '') . '>earlier than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function customer_review_count_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function purchased_products_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][products][]" class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['products']) && is_array($this->condition_item['products'])) {
            foreach ($this->condition_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . esc_attr($product_id) . '" selected>' . esc_html($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function purchased_variations_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][variations][]" class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['variations']) && is_array($this->condition_item['variations'])) {
            foreach ($this->condition_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . esc_attr($variation_id) . '" selected>' . esc_html($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function purchased_categories_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
        <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
        <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
        <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][categories][]" class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['categories']) && is_array($this->condition_item['categories'])) {
            foreach ($this->condition_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . esc_attr($category_id) . '" selected>' . esc_html($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>';
        return $html;
    }

    private function purchased_attributes_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][attributes][]" class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['attributes']) && is_array($this->condition_item['attributes'])) {
            foreach ($this->condition_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . esc_attr($attribute_id) . '" selected>' . esc_html($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function purchased_tags_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][time]" required ' . $this->field_status . '>';
        $html .= $this->get_all_times(!empty($this->condition_item['time']) ? $this->condition_item['time'] : '');
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least_one" ' . (($this->condition_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
        <option value="all" ' . (($this->condition_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
        <option value="only" ' . (($this->condition_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
        <option value="none" ' . (($this->condition_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][tags][]" class="wgbl-select2-tags wgbl-select2-option-values" data-type="select2" data-option-name="tags" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['tags']) && is_array($this->condition_item['tags'])) {
            foreach ($this->condition_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . esc_attr($tag_id) . '" selected>' . esc_html($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>';
        return $html;
    }

    private function quantity_purchased_products_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][products][]" class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['products']) && is_array($this->condition_item['products'])) {
            foreach ($this->condition_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . esc_attr($product_id) . '" selected>' . esc_html($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function quantity_purchased_variations_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][variations][]" class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['variations']) && is_array($this->condition_item['variations'])) {
            foreach ($this->condition_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . esc_attr($variation_id) . '" selected>' . esc_html($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function quantity_purchased_categories_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][categories][]" class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['categories']) && is_array($this->condition_item['categories'])) {
            foreach ($this->condition_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . esc_attr($category_id) . '" selected>' . esc_html($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
                <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function quantity_purchased_attributes_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][attributes][]" class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['attributes']) && is_array($this->condition_item['attributes'])) {
            foreach ($this->condition_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . esc_attr($attribute_id) . '" selected>' . esc_html($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function quantity_purchased_tags_render()
    {
        $html = '<div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][tags][]" class="wgbl-select2-tags wgbl-select2-option-values" data-option-name="tags" data-type="select2" data-placeholder="Select ..." multiple required ' . $this->field_status . '>';
        if (!empty($this->condition_item['tags']) && is_array($this->condition_item['tags'])) {
            foreach ($this->condition_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . esc_attr($tag_id) . '" selected>' . esc_html($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][method_option]">
        <option value="at_least" ' . (($this->condition_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
        <option value="more_than" ' . (($this->condition_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
        <option value="not_more_than" ' . (($this->condition_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
        <option value="less_than" ' . (($this->condition_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
        </select>
        </div>
        </div>
        <div class="wgbl-w30p">
        <div class="wgbl-form-group">
        <input type="number" min="0" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . $this->value . '" placeholder="0" required ' . $this->field_status . '>
        </div>
        </div>';
        return $html;
    }

    private function get_coupons_extra_fields()
    {
        if (empty($this->condition_item['method_option'])) {
            return '';
        }

        switch ($this->condition_item['method_option']) {
            case 'at_least_one':
            case 'all':
            case 'only':
            case 'none':
                $html = '<select name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][coupons][]" class="wgbl-select2-coupons wgbl-select2-option-values" data-option-name="coupons" data-type="select2" data-placeholder="Select ..." required ' . $this->field_status . ' multiple>';
                if (!empty($this->condition_item['coupons']) && is_array($this->condition_item['coupons'])) {
                    foreach ($this->condition_item['coupons'] as $coupon_id) {
                        if (!empty($this->option_values['coupons'][$coupon_id])) {
                            $html .= '<option value="' . esc_attr($coupon_id) . '" selected>' . esc_html($this->option_values['coupons'][$coupon_id]) . '</option>';
                        }
                    }
                }
                $html .= '</select>';
                break;
            default:
                $html = '';
        }

        return $html;
    }

    private function get_all_times($value = '')
    {
        $output = '<optgroup label="All time"></optgroup>
            <option value="all_time" ' . (($value == 'all_time') ? 'selected' : '') . '>all time</option>
            </optgroup>
            <optgroup label="Current">
            <option value="current_day" ' . (($value == 'current_day') ? 'selected' : '') . '>current day</option>
            <option value="current_week" ' . (($value == 'current_week') ? 'selected' : '') . '>current week</option>
            <option value="current_month" ' . (($value == 'current_month') ? 'selected' : '') . '>current month</option>
            <option value="current_year" ' . (($value == 'current_year') ? 'selected' : '') . '>current year</option>
            </optgroup>
            <optgroup label="Days">
            <option value="1_day" ' . (($value == '1_day') ? 'selected' : '') . '>1 day</option>
            <option value="2_day" ' . (($value == '2_day') ? 'selected' : '') . '>2 days</option>
            <option value="3_day" ' . (($value == '3_day') ? 'selected' : '') . '>3 days</option>
            <option value="4_day" ' . (($value == '4_day') ? 'selected' : '') . '>4 days</option>
            <option value="5_day" ' . (($value == '5_day') ? 'selected' : '') . '>5 days</option>
            <option value="6_day" ' . (($value == '6_day') ? 'selected' : '') . '>6 days</option>
            </optgroup>
            <optgroup label="Weeks">
            <option value="1_week" ' . (($value == '1_week') ? 'selected' : '') . '>1 week</option>
            <option value="2_week" ' . (($value == '2_week') ? 'selected' : '') . '>2 weeks</option>
            <option value="3_week" ' . (($value == '3_week') ? 'selected' : '') . '>3 weeks</option>
            <option value="4_week" ' . (($value == '4_week') ? 'selected' : '') . '>4 weeks</option>
            </optgroup>
            <optgroup label="Months">
            <option value="1_month" ' . (($value == '1_month') ? 'selected' : '') . '>1 month</option>
            <option value="2_month" ' . (($value == '2_month') ? 'selected' : '') . '>2 months</option>
            <option value="3_month" ' . (($value == '3_month') ? 'selected' : '') . '>3 months</option>
            <option value="4_month" ' . (($value == '4_month') ? 'selected' : '') . '>4 months</option>
            <option value="5_month" ' . (($value == '5_month') ? 'selected' : '') . '>5 months</option>
            <option value="6_month" ' . (($value == '6_month') ? 'selected' : '') . '>6 months</option>
            <option value="7_month" ' . (($value == '7_month') ? 'selected' : '') . '>7 months</option>
            <option value="8_month" ' . (($value == '8_month') ? 'selected' : '') . '>8 months</option>
            <option value="9_month" ' . (($value == '9_month') ? 'selected' : '') . '>9 months</option>
            <option value="10_month" ' . (($value == '10_month') ? 'selected' : '') . '>10 months</option>
            <option value="11_month" ' . (($value == '11_month') ? 'selected' : '') . '>11 months</option>
            <option value="12_month" ' . (($value == '12_month') ? 'selected' : '') . '>12 months</option>
            </optgroup>
            <optgroup label="Years">
            <option value="2_year" ' . (($value == '2_year') ? 'selected' : '') . '>2 years</option>
            <option value="3_year" ' . (($value == '3_year') ? 'selected' : '') . '>3 years</option>
            <option value="4_year" ' . (($value == '4_year') ? 'selected' : '') . '>4 years</option>
            <option value="5_year" ' . (($value == '5_year') ? 'selected' : '') . '>5 years</option>
            <option value="6_year" ' . (($value == '6_year') ? 'selected' : '') . '>6 years</option>
            <option value="7_year" ' . (($value == '7_year') ? 'selected' : '') . '>7 years</option>
            <option value="8_year" ' . (($value == '8_year') ? 'selected' : '') . '>8 years</option>
            <option value="9_year" ' . (($value == '9_year') ? 'selected' : '') . '>9 years</option>
            <option value="10_year" ' . (($value == '10_year') ? 'selected' : '') . '>10 years</option>
            </optgroup>';

        return $output;
    }

    private function get_user_meta_field_extra_fields()
    {
        if (empty($this->condition_item['method_option'])) {
            return '';
        }

        switch ($this->condition_item['method_option']) {
            case 'contains':
            case 'does_not_contain':
            case 'does_not_contain':
            case 'begins_with':
            case 'ends_with':
            case 'equals':
            case 'does_not_equal':
            case 'less_than':
            case 'less_or_equal_to':
            case 'more_than':
            case 'more_or_equal':
                $html = '<input type="text" name="rule[' . $this->rule_id . '][condition][' . $this->condition_id . '][value]" value="' . ((isset($this->condition_item['value'])) ? esc_attr($this->condition_item['value']) : '') . '" placeholder="Value ..." required ' . $this->field_status . '>';
                break;
            default:
                $html = '';
        }

        return $html;
    }
}
