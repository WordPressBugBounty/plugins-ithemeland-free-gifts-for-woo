<?php

namespace wgbl\classes\services\render;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Product_Buy_Render
{
    private static $instance;

    private $render_methods;
    private $rule_id;
    private $product_buy_item;
    private $product_buy_id;
    private $option_values;
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
        $this->product_buy_item = $data['product_buy_item'];
        $this->rule_id = $data['rule_id'];
        $this->product_buy_id = $data['product_buy_id'];
        $this->option_values = $data['option_values'];
        $this->field_status = $data['field_status'];
    }

    public function extra_fields_render()
    {
        $method = $this->get_render_method($this->product_buy_item['type']);
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
            'product' => 'product_render',
            'product_variation' => 'product_variation_render',
            'product_category' => 'product_category_render',
            'product_attribute' => 'product_attribute_render',
            'product_tag' => 'product_tag_render',
            'product_regular_price' => 'product_regular_price_render',
            'product_is_on_sale' => 'product_is_on_sale_render',
            'product_stock_quantity' => 'product_stock_quantity_render',
            'product_shipping_classes' => 'product_shipping_classes_render',
            'product_meta_field' => 'product_meta_field_render',
        ];
    }

    private function product_render()
    {
        $html = '<div class="wgbl-w30p">
            <div class="wgbl-form-group">
            <select class="wgbl-rule-product-buy-method-option" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
            <option value="in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
            <option value="not_in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
            </select>
            </div>
            </div>
            <div class="wgbl-w70p">
            <div class="wgbl-form-group">
            <select class="wgbl-select2-products wgbl-select2-option-values" data-option-name="products" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][products][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['products']) && is_array($this->product_buy_item['products'])) {
            foreach ($this->product_buy_item['products'] as $product_id) {
                if (!empty($this->option_values['products'][$product_id])) {
                    $html .= '<option value="' . sanitize_text_field($product_id) . '" selected>' . sanitize_text_field($this->option_values['products'][$product_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
            </div>
            </div>';

        return $html;
    }

    private function product_variation_render()
    {
        $html = '<div class="wgbl-w30p">
            <div class="wgbl-form-group">
            <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
            <option value="in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
            <option value="not_in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
            </select>
            </div>
            </div>
            <div class="wgbl-w70p">
            <div class="wgbl-form-group">
            <select class="wgbl-select2-variations wgbl-select2-option-values" data-option-name="variations" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][variations][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['variations']) && is_array($this->product_buy_item['variations'])) {
            foreach ($this->product_buy_item['variations'] as $variation_id) {
                if (!empty($this->option_values['variations'][$variation_id])) {
                    $html .= '<option value="' . sanitize_text_field($variation_id) . '" selected>' . sanitize_text_field($this->option_values['variations'][$variation_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
            </div>
            </div>';

        return $html;
    }

    private function product_category_render()
    {
        $html = '<div class="wgbl-w30p">
            <div class="wgbl-form-group">
            <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
            <option value="in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
            <option value="not_in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
            </select>
            </div>
            </div>
            <div class="wgbl-w70p">
            <div class="wgbl-form-group">
            <select class="wgbl-select2-categories wgbl-select2-option-values" data-option-name="categories" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][categories][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['categories']) && is_array($this->product_buy_item['categories'])) {
            foreach ($this->product_buy_item['categories'] as $category_id) {
                if (!empty($this->option_values['categories'][$category_id])) {
                    $html .= '<option value="' . sanitize_text_field($category_id) . '" selected>' . sanitize_text_field($this->option_values['categories'][$category_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
            </div>
            </div>';
        return $html;
    }

    private function product_attribute_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
                <option value="at_least_one" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select class="wgbl-select2-attributes wgbl-select2-option-values" data-option-name="attributes" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][attributes][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['attributes']) && is_array($this->product_buy_item['attributes'])) {
            foreach ($this->product_buy_item['attributes'] as $attribute_id) {
                if (!empty($this->option_values['attributes'][$attribute_id])) {
                    $html .= '<option value="' . sanitize_text_field($attribute_id) . '" selected>' . sanitize_text_field($this->option_values['attributes'][$attribute_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function product_tag_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
                <option value="at_least_one" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'at_least_one') ? 'selected' : '') . '>at least one of selected</option>
                <option value="all" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'all') ? 'selected' : '') . '>all of selected</option>
                <option value="only" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'only') ? 'selected' : '') . '>only selected</option>
                <option value="none" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'none') ? 'selected' : '') . '>none of selected</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select class="wgbl-select2-tags wgbl-select2-option-values" data-option-name="tags" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][tags][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['tags']) && is_array($this->product_buy_item['tags'])) {
            foreach ($this->product_buy_item['tags'] as $tag_id) {
                if (!empty($this->option_values['tags'][$tag_id])) {
                    $html .= '<option value="' . sanitize_text_field($tag_id) . '" selected>' . sanitize_text_field($this->option_values['tags'][$tag_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
                </div>
                </div>';
        return $html;
    }

    private function product_regular_price_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
                <option value="at_least" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][value]" type="number" min="0" placeholder="0.00" value="' . ((isset($this->product_buy_item['value'])) ? sanitize_text_field($this->product_buy_item['value']) : 0) . '" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function product_is_on_sale_render()
    {
        $html = '<div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][value]" required ' . $this->field_status . '>
                <option value="yes" ' . ((isset($this->product_buy_item['value']) && $this->product_buy_item['value'] == 'yes') ? 'selected' : '') . '>Yes</option>
                <option value="no" ' . ((isset($this->product_buy_item['value']) && $this->product_buy_item['value'] == 'no') ? 'selected' : '') . '>No</option>
                </select>
                </div>
                </div>';
        return $html;
    }

    private function product_stock_quantity_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
                <option value="at_least" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'at_least') ? 'selected' : '') . '>at least</option>
                <option value="more_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="not_more_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_more_than') ? 'selected' : '') . '>not more than</option>
                <option value="less_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][value]" type="number" min="0" placeholder="0" value="' . ((isset($this->product_buy_item['value'])) ? sanitize_text_field($this->product_buy_item['value']) : 0) . '" required ' . $this->field_status . '>
                </div>
                </div>';
        return $html;
    }

    private function product_shipping_classes_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]">
                <option value="in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'in_list') ? 'selected' : '') . '>in list</option>
                <option value="not_in_list" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'not_in_list') ? 'selected' : '') . '>not in list</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w70p">
                <div class="wgbl-form-group">
                <select class="wgbl-select2-shipping_classes wgbl-select2-option-values" data-option-name="shipping_classes" name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][shipping_classes][]" data-type="select2" multiple required ' . $this->field_status . '>';
        if (!empty($this->product_buy_item['shipping_classes']) && is_array($this->product_buy_item['shipping_classes'])) {
            foreach ($this->product_buy_item['shipping_classes'] as $shipping_class_id) {
                if (!empty($this->option_values['shipping_classes'][$shipping_class_id])) {
                    $html .= '<option value="' . sanitize_text_field($shipping_class_id) . '" selected>' . sanitize_text_field($this->option_values['shipping_classes'][$shipping_class_id]) . '</option>';
                }
            }
        }
        $html .= '</select>
            </div>
            </div>';
        return $html;
    }

    private function product_meta_field_render()
    {
        $html = '<div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <input name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][product_meta_field]" type="text" placeholder="meta field key" required ' . $this->field_status . ' value="' . ((!empty($this->product_buy_item['product_meta_field'])) ? sanitize_text_field($this->product_buy_item['product_meta_field']) : '') . '">
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <select name="rule[' . $this->rule_id . '][product_buy][' . $this->product_buy_id .  '][method_option]" class="wgbl-product-buy-product-meta-field-type">
                <option value="is_empty" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'is_empty') ? 'selected' : '') . '>is empty</option>
                <option value="is_not_empty" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'is_not_empty') ? 'selected' : '') . '>is not empty</option>
                <option value="contains" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'contains') ? 'selected' : '') . '>contains</option>
                <option value="does_not_contain" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'does_not_contain') ? 'selected' : '') . '>does not contain</option>
                <option value="begins_with" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'begins_with') ? 'selected' : '') . '>begins with</option>
                <option value="ends_with" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'ends_with') ? 'selected' : '') . '>ends with</option>
                <option value="equals" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'equals') ? 'selected' : '') . '>equals</option>
                <option value="does_not_equal" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'does_not_equal') ? 'selected' : '') . '>does not equal</option>
                <option value="less_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'less_than') ? 'selected' : '') . '>less than</option>
                <option value="less_or_equal_to" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'less_or_equal_to') ? 'selected' : '') . '>less or equal to</option>
                <option value="more_than" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'more_than') ? 'selected' : '') . '>more than</option>
                <option value="more_or_equal" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'more_or_equal') ? 'selected' : '') . '>more or equal to</option>
                <option value="is_checked" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'is_checked') ? 'selected' : '') . '>is checked</option>
                <option value="is_not_checked" ' . ((!empty($this->product_buy_item['method_option']) && $this->product_buy_item['method_option'] == 'is_not_checked') ? 'selected' : '') . '>is not checked</option>
                </select>
                </div>
                </div>
                <div class="wgbl-w30p">
                <div class="wgbl-form-group">
                <div class="wgbl-product-buy-extra-fields-col-4">';
        $html .= $this->get_product_meta_field();
        $html .= '</div>
                </div>
                </div>';
        return $html;
    }

    private function get_product_meta_field()
    {
        if (empty($this->product_buy_item['method_option'])) {
            return '';
        }

        switch ($this->product_buy_item['method_option']) {
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
                $output = '<input type="text" name="rule[' . $this->rule_id .  '][product_buy][' . $this->product_buy_id . '][value]" placeholder="Value ..." required ' . $this->field_status . ' value="' . ((isset($this->product_buy_item['value'])) ? sanitize_text_field($this->product_buy_item['value']) : '') . '">';
                break;
            default:
                $output = '';
        }

        return $output;
    }
}
