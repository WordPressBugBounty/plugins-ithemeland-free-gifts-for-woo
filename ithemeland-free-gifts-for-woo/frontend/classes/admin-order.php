<?php

use wgb\classes\helpers\Sanitizer;

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


        //Show 
        add_action('woocommerce_variation_options_pricing', array($this, 'show_product_variation_price_rule'), 10, 3);
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_price_adjustment_data_fields'));

        //Save 
        add_action('woocommerce_process_product_meta', array($this, 'woo_add_custom_general_fields_save'));
        add_action('woocommerce_save_product_variation', array($this, 'save_product_variation_meta'), 25, 2);
    }

    public function woo_add_custom_general_fields_save($post_id)
    {
        //to update product role based hide price
        if (isset($_POST['_price_for_gift']) && !empty($_POST['_price_for_gift'])) { //phpcs:ignore
            //update_post_meta($post_id, '_price_for_gift', sanitize_text_field[$_POST['_price_for_gift']]);
            //$product = wc_get_product( $post_id );
            //$product->update_meta_data( '_price_for_gift', $_POST['_price_for_gift'] );
            //$product->save();			
        } else {
            delete_post_meta($post_id, '_price_for_gift');
        }

        $product = wc_get_product($post_id);
        $num_package = isset($_POST['_price_for_gift']) ? sanitize_text_field(wp_unslash($_POST['_price_for_gift'])) : ''; //phpcs:ignore
        $product->update_meta_data('_price_for_gift', $num_package);
        $product->save();
        //if( !empty( $_POST['super_product'] ) ) {
        //	update_post_meta( $id, 'super_product', $_POST['super_product'] );
        //} else {
        //	delete_post_meta( $id, 'super_product' );
        //}

    }

    public function save_product_variation_meta($variation_id, $loop)
    {
        $custom_field = sanitize_text_field(wp_unslash($_POST['_price_for_gift'][$loop])); //phpcs:ignore
        if (isset($custom_field)) update_post_meta($variation_id, '_price_for_gift', esc_attr($custom_field));
    }

    public function add_price_adjustment_data_fields()
    {
        woocommerce_wp_text_input([
            'id' => '_price_for_gift',
            'label' => esc_html__('Gifts Price', 'ithemeland-free-gifts-for-woo'),
            'desc_tip'    => 'true',
            'placeholder' => 'Leave as free',
            'description' => esc_html__("Enter price For Gift , Leave as free", 'ithemeland-free-gifts-for-woo'),
        ]);
    }

    public function show_product_variation_price_rule($loop, $variation_data, $variation)
    {
        $free_txt = get_option('itg_localization_free', 'Free');
?>
        <div class='form-field form-row form-row-first'>
            <?php
            woocommerce_wp_text_input(array(
                'id' => '_price_for_gift[' . $loop . ']',
                'class' => 'short',
                'label' => esc_html__('Gifts Price', 'ithemeland-free-gifts-for-woo'),
                'value' => get_post_meta($variation->ID, '_price_for_gift', true),
                'desc_tip'    => 'true',
                'placeholder' => $free_txt,
                'description' => esc_html__("Enter price For Gift , Leave empty as free", 'ithemeland-free-gifts-for-woo'),
            ));
            ?>
        </div>
    <?php
        /*woocommerce_wp_text_input([
				'id' => '_price_for_gift',
				'label' => esc_html__('Gifts Price', 'txtdomain'),
		]);	*/
        // wc_get_template('views/admin/product_variation.php', array('loop' => $loop, 'variation_data' => $variation_data, 'variation' => $variation), '', plugin_dir_path_wc_adv_gift);
    }

    public function pw_woocommerce_order_item_display_meta_value($display_value)
    {
        if ($display_value == 'yes') {
            $display_value = esc_html__('Yes', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'tiered_quantity') {
            $display_value = esc_html__('Tiered Quantity', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'bulk_quantity') {
            $display_value = esc_html__('Bulk Quantity', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'buy_x_get_y') {
            $display_value = esc_html__('Buy X Get Y', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'buy_x_get_y_repeat') {
            $display_value = esc_html__('Buy X Get Y Repeat', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'buy_x_get_x_repeat') {
            $display_value = esc_html__('Buy X Get X Repeat', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'buy_x_get_x') {
            $display_value = esc_html__('Buy X Get X', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'simple') {
            $display_value = esc_html__('Simple', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'auto') {
            $display_value = esc_html__('Autimatic', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'subtotal_repeat') {
            $display_value = esc_html__('Subtotal Repeat', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'subtotal') {
            $display_value = esc_html__('Subtotal', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'manual') {
            $display_value = esc_html__('Selected Manual', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_value == 'add_admin') {
            $display_value = esc_html__('Added By Admin', 'ithemeland-free-gifts-for-woo');
        }

        return $display_value;
    }

    public function pw_woocommerce_order_item_display_meta_key($display_key)
    {
        if ($display_key == '_free_gift') {
            $display_key = esc_html__('Free Gift', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_key == '_rule_id_free_gift') {
            $display_key = esc_html__('Rule Gift ID', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_key == '_free_gift_type') {
            $display_key = esc_html__('Type', 'ithemeland-free-gifts-for-woo');
        } elseif ($display_key == '_free_gift_method') {
            $display_key = esc_html__('Method', 'ithemeland-free-gifts-for-woo');
        }

        return $display_key;
    }

    public function register_metaboxes()
    {
        add_meta_box('woocommerce-customer-add-gift', esc_html__('Add Manual Gift To this Order', 'ithemeland-free-gifts-for-woo'), array(
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

        wp_enqueue_script('it-order-shop-js', WGBL_JS_URL . 'frontend/order_shop.js', ['jquery'], WGBL_VERSION); //phpcs:ignore

        wp_localize_script('it-order-shop-js', 'it_wc_gift_add_order_ajax', array(
            'ajax_url'    => admin_url('admin-ajax.php'),
            'security'    => wp_create_nonce('jkhKJS31z4576d2324Z'),
            'order_id'    => $order_id,
            'customer_id' => $customer_id,

        ));

    ?>
        <div class="add-gift-to-order">
            <select id="gift_products_id" class="wc-product-search" multiple="multiple" style="width: 50%;" name="pw_gifts[]" data-placeholder="<?php esc_html_e('Search for a product', 'ithemeland-free-gifts-for-woo'); ?>" data-action="woocommerce_json_search_products_and_variations">
            </select>
            <button type="button" class="button add_gift_order"><?php esc_html_e('Add To This Order', 'ithemeland-free-gifts-for-woo'); ?></button>
        </div>
<?php
    }

    public function pw_ajax_add_free_gifts_to_order()
    {

        global $woocommerce;

        // Where the request will be handled
        if (!isset($_REQUEST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['security'])), 'jkhKJS31z4576d2324Z')) {
            wp_die('Forbidden!!!');
        }

        if (!isset($_REQUEST['product_ids']) || !is_array($_REQUEST['product_ids']) || empty($_REQUEST['order_id'])) { //phpcs:ignore
            return '';
        }

        $product_ids = array_map('intval', $_REQUEST['product_ids']); //phpcs:ignore
        $order_id    = intval($_REQUEST['order_id']); //phpcs:ignore
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
                //wc_add_order_item_meta($item_id, '_rule_id_free_gift', 'add_admin');

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
