<?php
if (!defined('ABSPATH')) {
    exit;
}

class class_wc_advanced_gift_admin
{
    public function __construct()
    {
        add_filter('woocommerce_order_item_display_meta_key', [
            $this,
            'pw_woocommerce_order_item_display_meta_key'
        ], 10, 1);

        add_filter('woocommerce_order_item_display_meta_value', [
            $this,
            'pw_woocommerce_order_item_display_meta_value'
        ], 10, 1);

        add_action('admin_init', [$this, 'register_metaboxes']);

        add_action('wp_ajax_it_wc_gift_to_order', [$this, 'pw_ajax_add_free_gifts_to_order']);
    }

    public function pw_woocommerce_order_item_display_meta_value($display_value)
    {
        if ($display_value == 'yes') {
            $display_value = __('Yes', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'buy_x_get_y') {
            $display_value = __('Buy X Get Y', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'buy_x_get_y_repeat') {
            $display_value = __('Buy X Get Y Repeat', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'buy_x_get_x_repeat') {
            $display_value = __('Buy X Get X Repeat', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'buy_x_get_x') {
            $display_value = __('Buy X Get X', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'simple') {
            $display_value = __('Simple', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'auto') {
            $display_value = __('Autimatic', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'subtotal_repeat') {
            $display_value = __('Subtotal Repeat', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'subtotal') {
            $display_value = __('Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'manual') {
            $display_value = __('Selected Manual', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_value == 'add_admin') {
            $display_value = __('Added By Admin', 'ithemeland-free-gifts-for-woocommerce-lite');
        }

        return $display_value;
    }


    public function pw_woocommerce_order_item_display_meta_key($display_key)
    {
        if ($display_key == '_free_gift') {
            $display_key = __('Free Gift', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_key == '_rule_id_free_gift') {
            $display_key = __('Rule Gift ID', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_key == '_free_gift_type') {
            $display_key = __('Type', 'ithemeland-free-gifts-for-woocommerce-lite');
        } elseif ($display_key == '_free_gift_method') {
            $display_key = __('Method', 'ithemeland-free-gifts-for-woocommerce-lite');
        }

        return $display_key;
    }

    public function register_metaboxes()
    {
        add_meta_box('woocommerce-customer-add-gift', __('Add Manual Gift To this Order', 'ithemeland-free-gifts-for-woocommerce-lite'), array(
            $this,
            'render_gift_order'
        ), 'shop_order', 'normal', 'default');
    }

    public function render_gift_order($order = 0)
    {

        // If no order object is available, bail here
        if (!is_object($order)) {
            return false;
        }
        // Get the customer ID
        $order       = wc_get_order($order);
        $order_id    = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
        $customer_id = $order->get_user_id();

        wp_register_script('it-order-shop-js', WGBL_JS_URL . 'frontend/order_shop.js', ['jquery']);
        wp_localize_script('it-order-shop-js', 'it_wc_gift_add_order_ajax', array(
            'ajax_url'    => admin_url('admin-ajax.php'),
            'security'    => wp_create_nonce('jkhKJS31z4576d2324Z'),
            'order_id'    => $order_id,
            'customer_id' => $customer_id,

        ));
        wp_enqueue_script('it-order-shop-js');

        ?>
        <div class="add-gift-to-order">
            <select id="gift_products_id" class="wc-product-search" multiple="multiple" style="width: 50%;" name="pw_gifts[]" data-placeholder="<?php esc_html_e('Search for a product', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>" data-action="woocommerce_json_search_products_and_variations">
            </select>
            <button type="button" class="button add_gift_order"><?php esc_html_e('Add To This Order', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></button> </div>
    <?php
    }

    public function pw_ajax_add_free_gifts_to_order()
    {

        global $woocommerce;

        // Where the request will be handled
        if (!wp_verify_nonce($_REQUEST['security'], 'jkhKJS31z4576d2324Z')) {
            wp_die('Forbidden!!!');
        }

        if (!isset($_REQUEST['product_ids'])) {
            return '';
        }

        $product_ids = wp_unslash($_REQUEST['product_ids']);
        $order_id    = wp_unslash($_REQUEST['order_id']);
        $note        = 'The Gifts Added By Admin: ';
        $set_gift    = false;
        $order       = wc_get_order($order_id);

        foreach ($product_ids as $product_id) {
            $_product = wc_get_product($product_id);
            $title    = $_product->get_title();
            if ($_product->post_type == 'product_variation') {
                $product_id = wp_get_post_parent_id($product_id);
                $title      = $_product->get_name();
            }
            $item                 = array();
            $item['variation_id'] = $this->get_variation_id($_product);
            @$item['variation_data'] = $item['variation_id'] ? $this->get_variation_attributes($_product) : '';
            $item_id = wc_add_order_item($order_id, array(
                'order_item_name' =>
                $title,
                'order_item_type' => 'line_item'
            ));

            if ($item_id) {
                $note .= $_product->get_title() . '(' . $_product->get_sku() . ') , ';
                wc_add_order_item_meta($item_id, '_qty', 1);
                wc_add_order_item_meta($item_id, '_tax_class', $_product->get_tax_class());
                wc_add_order_item_meta($item_id, '_product_id', $product_id);
                wc_add_order_item_meta($item_id, '_variation_id', $this->get_variation_id($_product));
                wc_add_order_item_meta($item_id, '_line_subtotal', wc_format_decimal(0, 4));
                wc_add_order_item_meta($item_id, '_line_total', wc_format_decimal(0, 4));
                wc_add_order_item_meta($item_id, '_line_tax', wc_format_decimal(0, 4));
                wc_add_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal(0, 4));
                wc_add_order_item_meta($item_id, '_free_gift_type', 'add_admin');
                wc_add_order_item_meta($item_id, '_free_gift', 'yes');

                $set_gift = true;
                if (@$item['variation_data'] && is_array($item['variation_data'])) {
                    foreach ($item['variation_data'] as $key => $value) {
                        wc_add_order_item_meta($item_id, esc_attr(str_replace('attribute_', '', $key)), $value);
                    }
                }
            }
        }
        if ($set_gift) {
            $order->add_order_note($note);
            echo 'success';
        }

        wp_die();
    }

    protected function get_variation_id($_product)
    {
        if (version_compare(WC()->version, "2.7.0") >= 0) {
            return $_product->get_id();
        } else {
            return $_product->variation_id;
        }
    }

    protected function get_variation_attributes($_product)
    {
        if (version_compare(WC()->version, "2.7.0") >= 0) {
            return wc_get_product_variation_attributes($_product->get_id());
        } else {
            return $_product->get_variation_attributes();
        }
    }
}

new class_wc_advanced_gift_admin();
