<?php
function itg_check_quantity_gift_in_session($session_content, $get_cart_item_quantities_gift_stock = '')
{
    $count_gift         = 0;
    $subtotal_price         = 0;
    $count_rule_gift    = [];
    $count_rule_product = [];
    $gifts_set          = [];
    foreach ($session_content as $key => $value) {

        if (!isset($value['it_free_gift'])) {
            continue;
        }

        $subtotal_price += $value['it_free_gift']['base_price'];
        $count_gift  += $value['quantity'];
        $gifts_set[] = $value['it_free_gift']['rule_gift_key'];

        if (array_key_exists($value['it_free_gift']['rule_id'], $count_rule_gift)) {
            $count_rule_gift[$value['it_free_gift']['rule_id']]['q'] += $value['quantity'];
        } else {
            $count_rule_gift[$value['it_free_gift']['rule_id']]['q'] = $value['quantity'];
        }
        if (array_key_exists($value['it_free_gift']['rule_gift_key'], $count_rule_product)) {
            $count_rule_product[$value['it_free_gift']['rule_gift_key']]['q'] += $value['quantity'];
        } else {
            $count_rule_product[$value['it_free_gift']['rule_gift_key']]['q'] = $value['quantity'];
        }
    }
    $count_infoa = [
        'count_gift'         => $count_gift,
        'count_rule_gift'    => $count_rule_gift,
        'count_rule_product' => $count_rule_product,
        'gifts_set'          => $gifts_set,
        'subtotal_price'          => $subtotal_price,
    ];

    return $count_infoa;
}

if (!function_exists('itg_get_cart_item_stock_quantities')) {
    function itg_get_cart_item_stock_quantities($item_cart)
    {
        $quantities = array();
        foreach ($item_cart as $cart_item_key => $values) {
            $product = $values['data'];
            $quantities[$product->get_stock_managed_by_id()] = isset($quantities[$product->get_stock_managed_by_id()]) ? $quantities[$product->get_stock_managed_by_id()] + $values['quantity'] : $values['quantity'];
        }

        return $quantities;
    }
}
if (!function_exists('itg_get_cart_items_gift_quantities')) {
    function itg_get_cart_items_gift_quantities()
    {
        if (!is_object(WC()->cart)) {
            return '';
        }
        $filter_items = [];
        foreach (WC()->cart->get_cart() as $key => $value) {

            if (!isset($value['it_free_gift'])) {
                continue;
            }
            $product = $value['data'];
            $filter_items[$product->get_stock_managed_by_id()] = isset($filter_items[$product->get_stock_managed_by_id()]) ? $filter_items[$product->get_stock_managed_by_id()] + $value['quantity'] : $value['quantity'];
        }

        return $filter_items;
    }
}

if (!function_exists('deprecated_itg_quantities_gift_stock')) {

    function deprecated_itg_quantities_gift_stock($product, $product_qty_in_cart, $gift_id, $product_type, $settings, $item_hover, $pw_number_gift_allowed, $rule_id, $count_info, $some_gift)
    {
        $text_stock_qty = '';
        $stock_status = '';
        $get_stock_quantity = $product->get_stock_quantity();
        $z = 0;
        $x = 0;
        $qty = 0;
        $count_rule_gift = 0;
        if (!$product->is_in_stock() && $get_stock_quantity <= 0) {
            $item_hover     = 'disable-hover';
            $text_stock_qty = 'out of stock';
            $stock_status = 'out_of_stock';
        } else {

            $get_cart_item_quantities_gift_stock = itg_get_cart_items_gift_quantities();

            $count_gift_in_gift = isset($get_cart_item_quantities_gift_stock[$gift_id]) ? $get_cart_item_quantities_gift_stock[$gift_id] : 0;

            $count_in_cart = isset($product_qty_in_cart[$product->get_stock_managed_by_id()]) ? $product_qty_in_cart[$product->get_stock_managed_by_id()] : 0;

            if ($product->is_in_stock() && $get_stock_quantity >= 1) {
                $x = $get_stock_quantity - ($count_in_cart + $count_gift_in_gift);
            }

            //For Quantity
            //if($settings['enabled_qty']=='true')
            //{
            $z = $pw_number_gift_allowed - $count_gift_in_gift;
            //print_r($count_in_cart);
            //echo $pw_number_gift_allowed.'@';

            if ($z >= $x && ($product->is_in_stock() && $get_stock_quantity >= 1)) {
                $qty = $x;
            } else {
                $qty = $z;
            }

            if (array_key_exists($rule_id, $count_info['count_rule_gift'])) {
                $count_rule_gift = $count_info['count_rule_gift'][$rule_id]['q'];
            }

            $y = $pw_number_gift_allowed - $count_rule_gift;

            if ($qty > $y) {
                $qty = $y;
            }
            //}

            if ($qty <= 0) {
                $item_hover = 'disable-hover';
                $text_stock_qty = esc_html__('Gift Unavailable', 'ithemeland-free-gifts-for-woo');
                $stock_status = 'out_of_stock';
            } else if ($some_gift == 'no') {
                $qty = 1;
                $text_stock_qty = esc_html__('Available Gift', 'ithemeland-free-gifts-for-woo') . ' : ' . $qty;
            } else if ($settings['show_stock_quantity'] == 'true') {
                $text_stock_qty = esc_html__('Available Gift', 'ithemeland-free-gifts-for-woo') . ' : ' . $qty;
            } else {
                $text_stock_qty = '';
            }
        }
        /*else{
			$qty='unlimited';
			$count_product_gift_in_cart_as_gift = itg_get_cart_items_gift_quantities();
			$required_stock_in_cart_gift = isset($count_product_gift_in_cart_as_gift[$gift_id]) ? $count_product_gift_in_cart_as_gift[$gift_id] : 0;
			$qty = $pw_number_gift_allowed - $required_stock_in_cart_gift;	
			
			if ($settings['show_stock_quantity'] != 'false') {
				$text_stock_qty = $qty . ' ' . esc_html__('Available Gift', 'ithemeland-free-gifts-for-woo');
			}		
					
			//print_r($get_cart_item_quantities_gift_stock);
			//echo $gift_id;
			
			//die;
		}
		*/

        return [
            'qty'            => $qty,
            'item_hover'     => $item_hover,
            'text_stock_qty' => $text_stock_qty,
            'stock_status' => $stock_status,
        ];
    }
}

function itg_get_settings()
{

    $settings = wgb\classes\repositories\Setting::get_instance();
    $settings = $settings->get();

    $settings['position']                             = isset($settings['position']) ? $settings['position'] : 'bottom_cart';
    $settings['show_stock_quantity']                  = isset($settings['show_stock_quantity']) ? $settings['show_stock_quantity'] : 'false';
    $settings['layout']                               = isset($settings['layout']) ? $settings['layout'] : 'grid';
    $settings['child']                                = isset($settings['child']) ? $settings['child'] : 'false';
    $settings['number_per_page'] = isset($settings['number_per_page']) ? $settings['number_per_page'] : '4';
    $settings['desktop_columns'] = isset($settings['desktop_columns']) ? str_replace('wgbl', 'wgb', $settings['desktop_columns']) : 'wgb-col-md-2';
    $settings['tablet_columns'] = isset($settings['tablet_columns']) ? str_replace('wgbl', 'wgb', $settings['tablet_columns']) : 'wgb-col-sm-2';
    $settings['mobile_columns'] = isset($settings['mobile_columns']) ? str_replace('wgbl', 'wgb', $settings['mobile_columns']) : 'wgb-col-2';
    $settings['carousel']['rtl'] = isset($settings['carousel']['rtl']) ? $settings['carousel']['rtl'] : 'false';
    $settings['carousel']['loop'] = isset($settings['carousel']['loop']) ? $settings['carousel']['loop'] : 'false';
    $settings['carousel']['dots'] = isset($settings['carousel']['dots']) ? $settings['carousel']['dots'] : 'false';
    $settings['carousel']['nav'] = isset($settings['carousel']['nav']) ? $settings['carousel']['nav'] : 'false';
    $settings['carousel']['speed'] = isset($settings['carousel']['speed']) ? $settings['carousel']['speed'] : '5000';
    $settings['carousel']['desktop'] = isset($settings['carousel']['desktop']) ? $settings['carousel']['desktop'] : '6';
    $settings['carousel']['tablet'] = isset($settings['carousel']['tablet']) ? $settings['carousel']['tablet'] : '2';
    $settings['carousel']['mobile'] = isset($settings['carousel']['mobile']) ? $settings['carousel']['mobile'] : '1';
    $settings['display_price'] = isset($settings['display_price']) ? $settings['display_price'] : 'no';
    $settings['show_description'] = isset($settings['show_description']) ? $settings['show_description'] : 'false';
    $settings['enabled_qty'] = isset($settings['enabled-qty']) ? $settings['enabled-qty'] : 'false';
    $settings['show_gift_type_lable'] = isset($settings['show_gift_type_lable']) ?  $settings['show_gift_type_lable'] : 'false';
    $settings['gift_title_Length'] = isset($settings['gift_title_Length']) ?  $settings['gift_title_Length'] : '20';
    $settings['enable_ajax_add_to_cart'] = isset($settings['enable_ajax_add_to_cart']) ?  $settings['enable_ajax_add_to_cart'] : 'false';
    $settings['layout_popup'] = isset($settings['layout_popup']) ?  $settings['layout_popup'] : 'carousel';
    return $settings;
}

if (!function_exists('itg_render_product_image')) {
    function itg_render_product_image($product, $size = 'woocommerce_thumbnail', $echo = true)
    {

        if ($echo) {
            echo wp_kses_post($product->get_image($size));
        }

        return $product->get_image();

        /* For Duplicate Image
		<?php
		if ( has_post_thumbnail( $post-&gt;ID) ) {		
			$product_image_url = wp_get_attachment_url( get_post_thumbnail_id( $gift_product[ 'product_id' ]) );
		}
		 ?>
		<img decoding="async" width="293" height="291" src="<?php echo  $product_image_url; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail ls-is-cached lazyloaded">		
		*/
    }
}

if (!function_exists('itg_render_title_product_gift')) {
    function itg_render_title_product_gift($title, $gift_id, $settings, $echo = true)
    {
        $return = '';
        $return = '<a href="' . get_permalink($gift_id) . '">' . sprintf("%s", $title) . '</a>';

        if ($echo) {
            echo wp_kses_post($return);
        }

        return $return;
    }
}

if (!function_exists('itg_render_product_name')) {
    function itg_render_product_name($product, $settings, $echo = true)
    {
        if (!is_object($product))
            return;

        $product_name = $product->get_title();
        if ($product->post_type == 'product_variation') {
            $product_name = $product->get_name();
        }

        $limit_char = $settings['gift_title_Length']; // limit character from settings;
        $full_name = $product_name;

        if (isset($limit_char) && $limit_char > 0 && strlen($product_name) > $limit_char) {
            $product_name = mb_substr($product_name, 0, $limit_char) . '...';
        }

        $product_name = '<a href="' . get_permalink($product->get_id()) . '" title="' . esc_attr($full_name) . '">' . esc_html($product_name) . '</a>';

        $product_name = apply_filters('itg_gift_product_name', $product_name, $product);

        if ($echo) {
            echo wp_kses_post($product_name);
        }

        return $product_name;
    }
}

if (!function_exists('itg_check_gift_available')) {
    function itg_check_gift_available($show_gift_item_for_cart, $gift_item_variable, $gift_rule_exclude)
    {
        $retun['av_gifts'] = [];

        $retrieved_group_input_value = WC()->cart->get_cart();

        $count_info = itg_check_quantity_gift_in_session($retrieved_group_input_value);

        if (!isset($show_gift_item_for_cart['gifts'])) {
            return $retun;
        }

        foreach ($show_gift_item_for_cart['gifts'] as $gift_item_key => $gift) {
            if (isset($gift['auto']) && $gift['auto'] == 'yes') {
                continue;
            }
            $text_stock_qty = 'in stock';
            $item_hover     = 'hovering';
            $disable        = false;

            $pw_number_gift_allowed = $gift_item_variable[$gift['uid']]['pw_number_gift_allowed'];
            //Number Allow For Other Method
            if (in_array($gift_item_variable[$gift['uid']]['method'], array(
                'buy_x_get_x_repeat'
            ), true) && $gift_item_variable[$gift['uid']]['based_on'] == 'ind') {

                $pw_number_gift_allowed = $gift_item_variable['all_gifts'][$gift_item_key]['q'];
            }

            $product = wc_get_product(intval($gift['item']));
            if (!($product instanceof \WC_Product)) {
                continue;
            }

            if (!($product->is_purchasable() && $product->is_in_stock())) {
                continue;
            }
            $product_type = $product->get_type();
            if ($product_type == 'variable') {
                $variation_ids = version_compare(
                    WC()->version,
                    '2.7.0',
                    '>='
                ) ? $product->get_visible_children() : $product->get_children(true);
                foreach ($variation_ids as $product_id) {
                    $_product = wc_get_product($product_id);
                    $gift_id  = $gift['uid'] . '-' . $product_id;
                    //For exclude in select variations
                    if (isset($gift_rule_exclude[$gift['uid']]) && in_array(
                        $product_id,
                        $gift_rule_exclude[$gift['uid']]
                    )) {
                        continue;
                    }
                    $item_hover = 'hovering';
                    $flag_count = false;

                    if (in_array($gift['method'], array('buy_x_get_x_repeat',), true) && $gift['base_q'] == 'ind') {

                        if (array_key_exists($gift_item_key, $count_info['count_rule_product']) && $count_info['count_rule_product'][$gift_item_key]['q'] >= $pw_number_gift_allowed) {
                            $flag_count = true;
                        }
                    } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $pw_number_gift_allowed) {
                        $flag_count = true;
                    }

                    if (
                        $flag_count ||
                        (in_array($gift_id, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')
                        ||
                        (in_array($gift_id, $count_info['gifts_set']) && $gift_item_variable[$gift['uid']]['can_several_gift'] == 'no')
                    ) {
                        continue;
                        //$item_hover = 'disable-hover';
                    }
                    $retun['av_gifts'][] = $product_id;
                }
            } //End Variable
            else {
                $flag_count = false;
                if (in_array($gift['method'], array('buy_x_get_x_repeat',), true) && $gift['base_q'] == 'ind') {

                    if (array_key_exists($gift_item_key, $count_info['count_rule_product']) && $count_info['count_rule_product'][$gift_item_key]['q'] >= $pw_number_gift_allowed) {
                        $flag_count = true;
                    }
                } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $pw_number_gift_allowed) {
                    $flag_count = true;
                }


                if ($flag_count || (in_array($gift_item_key, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')) {
                    continue;
                    //$item_hover = 'disable-hover';
                }
                $retun['av_gifts'][] = $gift['item'];
            }
        }
        return $retun;
    }
}

function it_get_cart_gift_contents()
{
    if (!is_object(WC()->cart)) {
        return '';
    }
    $filter_items = [];
    foreach (WC()->cart->get_cart() as $key => $value) {

        if (!isset($value['it_free_gift'])) {
            continue;
        }
        $filter_items[$key] = $value;
    }
    return $filter_items;
}


function it_sort_by_price($cart_item_a, $cart_item_b)
{
    return $cart_item_a['data']->get_price() > $cart_item_b['data']->get_price();
}

if (!function_exists('itg_get_template')) {
    function itg_get_template($template_name, $args = array(), $path = '')
    {
        //wc_get_template('/views/'.$template_name, $args, 'ithemeland-free-gifts-for-woo/', plugin_dir_path_wc_adv_gift );
        wc_get_template($template_name, $args, 'ithemeland-free-gifts-for-woo/', plugin_dir_path_wc_adv_gift . 'views/');
    }
}

if (!function_exists('itg_get_gift_products_data_multilevel')) {
    function itg_get_gift_products_data_multilevel($args = array())
    {
        $rule_products = ['items' => [], 'settings' => $args['settings'], 'is_child' => $args['is_child']];

        $quantity_in_session = itg_check_quantity_gift_in_session(WC()->cart->get_cart());

        $gift_quantity_in_cart = itg_get_cart_items_gift_quantities();

        foreach ($args['gifts_items_cart']['gifts'] as $gift_item_key => $gifts_items_cart) {

            $get_parent_id = $gifts_items_cart['item'];
            $product = itg_get_product($get_parent_id);
            //$product = get_product( $get_parent_id );
            //$type = WC_Product_Factory::get_product_type($get_parent_id);
            if (!$product) {
                continue;
            }
            /*
            if (!($product->is_purchasable() && $product->is_in_stock())) {
                continue;
            }
			*/
            $eligible_product = array();

            $gift_allowed = $args['all_gift_items'][$gifts_items_cart['uid']]['pw_number_gift_allowed'];
            //Number Allow For Other Method
            if (in_array($args['all_gift_items'][$gifts_items_cart['uid']]['method'], array(
                'buy_x_get_x_repeat'
            ), true) && $args['all_gift_items'][$gifts_items_cart['uid']]['based_on'] == 'ind') {

                $gift_allowed = $args['all_gift_items']['all_gifts'][$gift_item_key]['q'];
            }

            $product_ids = ('variable' == $product->get_type()) ? $product->get_children() : array($get_parent_id);
            $flag_parent_status = false;
            foreach ($product_ids as $get_product_id) {

                if (isset($args['gift_rule_exclude'][$gifts_items_cart['uid']]) && in_array(
                    $get_product_id,
                    $args['gift_rule_exclude'][$gifts_items_cart['uid']]
                )) {
                    continue;
                }

                $args_data = [
                    'product_id' => $get_product_id,
                    'rule' => $gifts_items_cart,
                    'products_in_cart' => $args['quantity_products_in_cart'],
                    'gifts_in_cart' => $gift_quantity_in_cart,
                    'gift_allowed' => $gift_allowed,
                    'quantities_in_session' => $quantity_in_session,
                    'all_gift_items' => $args['all_gift_items'],
                ];

                $stock_status = itg_get_product_stock_status($args_data);

                if (!itg_check_is_array($eligible_product)) {
                    $eligible_product = array(
                        'parent_id' => $get_parent_id,
                        'product_id' => $get_product_id,
                        'rule_id' => $gifts_items_cart['uid'],
                        'qty' => $gift_allowed,
                        'hide_add_to_cart' => $stock_status['hide_add_to_cart'],
                        'stock_qty' => $stock_status['stock_qty'],
                        'variation_ids' => array(),
                    );
                }

                // Consider the valid variation in variable product.
                if ('variable' == $product->get_type()) {

                    $eligible_product['variation_ids'][] =
                        [
                            'id' => $get_product_id,
                            'hide_add_to_cart' => $stock_status['hide_add_to_cart'],
                            'stock_qty' => $stock_status['stock_qty'],

                        ];
                }
                if ($stock_status['hide_add_to_cart'] == false) {
                    $flag_parent_status = true;
                }
            }
            if (itg_check_is_array($eligible_product)) {
                if ($flag_parent_status && 'variable' == $product->get_type() && itg_check_is_array($eligible_product['variation_ids'])) {
                    $eligible_product['hide_add_to_cart'] = false;
                }

                $rule_products['items'][] = $eligible_product;
            }
        }

        if (!$args['multi_level'])
            $rule_products = itg_get_gift_products_data_one_level($rule_products);

        //echo '<pre>';print_r($rule_products);die;

        return $rule_products;
    }
}

if (!function_exists('itg_get_gift_products_data_one_level')) {
    function itg_get_gift_products_data_one_level($item_array)
    {
        $eligible_product = array();
        $rule_products = [];
        foreach ($item_array['items'] as $key => $gift_product) {
            $eligible_product = array(
                'product_id' => $gift_product['parent_id'],
                'rule_id' => $gift_product['rule_id'],
                'add_or_select' => 'add',
                'stock_qty' => $gift_product['stock_qty'],
                'hide_add_to_cart' => $gift_product['hide_add_to_cart'],
            );
            if (itg_check_is_array($gift_product['variation_ids'])) {
                if ($item_array['is_child']) {
                    foreach ($gift_product['variation_ids'] as $variation_id) {
                        $eligible_product = array(
                            'product_id' => $variation_id['id'],
                            'rule_id' => $gift_product['rule_id'],
                            'add_or_select' => 'add',
                            'stock_qty' => $variation_id['stock_qty'],
                            'hide_add_to_cart' => $variation_id['hide_add_to_cart'],
                        );
                        $rule_products[] = $eligible_product;
                    }
                } else {
                    $eligible_product['add_or_select'] = 'select';
                    $rule_products[] = $eligible_product;
                }
            } else {
                $rule_products[] = $eligible_product;
            }
        }

        $item_array['items'] = $rule_products;

        return $item_array;
    }
}

if (!function_exists('itg_get_product')) {

    /**
     * Get the product object by product id.
     *
     * @return object/bool
     */
    function itg_get_product($product_id)
    {
        /**
         * This hook is used to validate the product.
         * 
         * @since 2.0.0
         */
        if (!apply_filters('itg_is_valid_product', true, $product_id)) {
            return false;
        }
        /**
         * This hook is used to alter the product.
         * 
         * @since 2.0.0
         */
        return apply_filters('itg_get_product', wc_get_product($product_id), $product_id);
    }
}

if (!function_exists('itg_check_is_array')) {
    /**
     * Check if the resource is array.
     *
     * @return bool
     */
    function itg_check_is_array($data)
    {
        return (is_array($data) && !empty($data));
    }
}

if (!function_exists('itg_get_product_stock_status')) {
    function itg_get_product_stock_status($args_data)
    {
        $qty = 0;
        $hide_add_to_cart     = false;
        $product_id = $args_data['product_id'];
        $rule_id = $args_data['rule']['uid'];
        $rule = $args_data['rule'];
        $quantities_in_session = $args_data['quantities_in_session'];
        $product_gift_allowed = $args_data['gift_allowed'];

        $product = itg_get_product($product_id);
        $gift_id = $rule['uid'] . '-' . $product_id;

        //Return if stock is out of stock.
        if (!$product || (!$product->is_on_backorder() && !$product->is_in_stock())) {
            return [
                'stock_qty' => 0,
                'hide_add_to_cart' => true,
            ];
        }

        $any_rule_gift_count_in_cart = isset($args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q']) ? $args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q'] : 0;

        $get_stock_quantity = $product->get_stock_quantity();

        if (in_array($rule['method'], array('buy_x_get_x_repeat'), true) && $rule['base_q'] == 'ind') {
            $product_gift_allowed = $args_data['all_gift_items']['all_gifts'][$gift_id]['q'];
            if (array_key_exists($rule['key'], $quantities_in_session['count_rule_product']) && $quantities_in_session['count_rule_product'][$rule['key']]['q']) {
                $any_rule_gift_count_in_cart = $quantities_in_session['count_rule_product'][$rule['key']]['q'];
            } else {
                $any_rule_gift_count_in_cart = 0;
            }
        }
        // Return if managing stock is not enabled.
        if (!$product->managing_stock() || $product->is_on_backorder()) {
            $qty =  $product_gift_allowed - $any_rule_gift_count_in_cart;
        } else {
            $product_count_in_cart = itg_get_product_count_in_cart($product_id);
            $stock_mines_in_cart = $get_stock_quantity - $product_count_in_cart;

            if ($product_gift_allowed > $stock_mines_in_cart) {
                $qty = $stock_mines_in_cart;
            } else {
                $qty = $product_gift_allowed - $any_rule_gift_count_in_cart;
            }
        }

        if ($qty <= 0) {
            $hide_add_to_cart     = true;
        } else if (in_array($gift_id, $quantities_in_session['gifts_set']) && $rule['can_several_gift'] == 'no') {
            $hide_add_to_cart     = true;
        } else if ($rule['can_several_gift'] == 'no') {
            //$hide_add_to_cart     = false;
            $qty = 1;
        }
        $data = [
            'stock_qty' => $qty,
            'hide_add_to_cart' => $hide_add_to_cart,
        ];
        return $data;
    }
}

if (!function_exists('itg_render_stock_status')) {
    function itg_render_stock_status($stock_qty, $settings, $gift_product, $echo = true)
    {
        if ($gift_product['add_or_select'] == 'select') {
            $stack_status =  esc_html__('Available Gift', 'ithemeland-free-gifts-for-woo');
        } else if ($stock_qty <= 0) {
            $stack_status = esc_html__('Gift Unavailable', 'ithemeland-free-gifts-for-woo');
        } else {
            $stack_status =  esc_html__('Available Gift', 'ithemeland-free-gifts-for-woo') . ' : ' . $stock_qty;
        }

        if ($echo) {
            echo wp_kses_post($stack_status);
        }

        return $stack_status;
    }
}

if (!function_exists('itg_render_price_gift')) {
    function itg_render_price_gift($product, $gift_product, $echo = true)
    {

        if (!is_object($product))
            return;

        //if ( itg_check_is_array( $gift_product[ 'variation_ids' ] ))
        //			return ;

        $text_temp = '';
        $product_price = $product->get_price();
        $price = get_post_meta($product->get_id(), '_price_for_gift', true);

        if ($price != '') {
            $text_temp = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price($price) . '</ins>';
        } else {
            $free_txt = get_option('itg_localization_free', 'Free');
            $text_temp = '<del>' . wc_price($product_price) . '</del> <ins>' . $free_txt . '</ins>';
        }

        $text_price = '<div class="gift-price" >' . $text_temp . '</div>';
        if ($echo) {
            echo wp_kses_post($text_price);
        }

        return $text_price;
    }
}

if (!function_exists('itg_get_removed_automatic_free_gift_products_from_session')) {

    /**
     * Get removed automatic free gift products from session
     * @since 2.1.1
     */
    function itg_get_removed_automatic_free_gift_products_from_session()
    {
        return array_filter(WC()->session->get('itg_removed_automatic_free_gift_products', array()));
    }
}

if (!function_exists('itg_unset_removed_automatic_free_gift_products_from_session')) {

    /**
     * @since 2.1.1
     */
    function itg_unset_removed_automatic_free_gift_products_from_session()
    {
        WC()->session->__unset('itg_removed_automatic_free_gift_products');
    }
}

if (!function_exists('itg_get_current_applicable_free_gift_rules_from_session')) {

    /**
     * @since 2.1.1
     */
    function itg_get_current_applicable_free_gift_rules_from_session()
    {
        return array_filter(WC()->session->get('itg_free_gift_current_applicable_rules', array()));
    }
}



if (!function_exists('validate_automatic_gift_product_before_add_to_cart')) {

    /**
     * Check automatic free gift products before Add to cart
     * @since 2.1.1
     */
    function validate_automatic_gift_product_before_add_to_cart($gift_product_id, $rule_id)
    {
        return true;
    }
}

if (!function_exists('itg_get_gift_product_add_to_cart_classes')) {
    /**
     * Get the gift product add to cart classes.
     *
     *  @return array
     */
    function itg_get_gift_product_add_to_cart_classes($settings)
    {

        $classes = array('wgb-add-gift-btn');

        if ($settings['enable_ajax_add_to_cart'] == 'true') {
            $classes[] = 'btn-click-add-gift-button';
        }
        /**
         * This hook is used to alter the gift product add to cart classes.
         * 
         * @since 1.0
         */
        return apply_filters('itg_gift_product_add_to_cart_classes', $classes);
    }
}

if (!function_exists('itg_get_gift_product_add_to_cart_url')) {
    /**
     * Get the gift product add to cart URL.
     *
     *  @return array
     */
    function itg_get_gift_product_add_to_cart_url($gift_product, $permalink = '')
    {

        $settings = itg_get_settings();

        if ('true' == $settings['enable_ajax_add_to_cart'] || itg_is_block_checkout() || itg_is_block_cart()) {
            $url = '#';
        } else {
            $args = array(
                'pw_add_gift' => $gift_product['gift_id'],
                'itg_rule_id' => $gift_product['rule_id'],
            );

            $permalink = apply_filters('itgift_permalink_add_to_cart_url', $permalink);

            $url = esc_url(add_query_arg($args, $permalink));
        }

        return apply_filters('itgift_product_add_to_cart_url', $url);
    }
}


if (!function_exists('itg_is_block_cart')) {

    /**
     * Is a block cart page?.
     *
     * @since 2.0.0
     * @return boolean
     */
    function itg_is_block_cart()
    {
        static $is_block_cart;
        if (isset($is_block_cart)) {
            return $is_block_cart;
        }

        global $post;
        $is_singular = true;
        if (!is_a($post, 'WP_Post')) {
            $is_singular = false;
        }

        // Consider as block cart while the request call via Store API.
        if (isset($GLOBALS['wp']->query_vars['rest_route']) && false !== strpos($GLOBALS['wp']->query_vars['rest_route'], '/wc/store/v1')) {
            return true;
        }

        $is_block_cart = $is_singular && has_block('woocommerce/cart', $post);

        return $is_block_cart;
    }
}

if (!function_exists('itg_is_block_checkout')) {

    /**
     * Is a block checkout page?.
     *
     * @since 2.0.0
     * @return boolean
     */
    function itg_is_block_checkout()
    {
        static $is_block_checkout;
        if (isset($is_block_checkout)) {
            return $is_block_checkout;
        }

        global $post;
        $is_singular = true;
        if (!is_a($post, 'WP_Post')) {
            $is_singular = false;
        }

        // Consider as block checkout while the request call via Store API.
        if (isset($GLOBALS['wp']->query_vars['rest_route']) && false !== strpos($GLOBALS['wp']->query_vars['rest_route'], '/wc/store/v1/cart')) {
            return true;
        }

        $is_block_checkout = $is_singular && has_block('woocommerce/checkout', $post);

        return $is_block_checkout;
    }
}

if (!function_exists('wgb_get_active_layout_popup_items')) {
    function wgb_get_active_layout_popup_items($layout = 'carousel')
    {
        switch ($layout) {
            case 'carousel':
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/carousel-items.php';
                break;
            case 'list':
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/list-items.php';
                break;
            default:
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/carousel-items.php';
        }

        return $layout;
    }
}

if (!function_exists('wgb_is_cart_page')) {
    function wgb_is_cart_page()
    {
        if (is_cart()) {
            return true;
        }
        if (function_exists('has_block')) {
            return has_block('woocommerce/cart');
        }

        return false;
    }
}

if (!function_exists('wgb_is_checkout_page')) {
    function wgb_is_checkout_page()
    {
        if (is_checkout()) {
            return true;
        }

        if (function_exists('has_block')) {
            return has_block('woocommerce/checkout');
        }

        return false;
    }
}

if (!function_exists('wgb_is_block_cart')) {
    function wgb_is_block_cart()
    {
        static $is_block_cart;
        if (isset($is_block_cart)) {
            return $is_block_cart;
        }

        global $post;
        $is_singular = true;
        if (!is_a($post, 'WP_Post')) {
            $is_singular = false;
        }

        if (isset($GLOBALS['wp']->query_vars['rest_route']) && false !== strpos($GLOBALS['wp']->query_vars['rest_route'], '/wc/store/v1')) {
            return true;
        }

        $is_block_cart = $is_singular && has_block('woocommerce/cart', $post);

        return $is_block_cart;
    }
}

if (!function_exists('wgb_is_block_checkout')) {
    function wgb_is_block_checkout()
    {
        static $is_block_checkout;
        if (isset($is_block_checkout)) {
            return $is_block_checkout;
        }

        global $post;
        $is_singular = true;
        if (!is_a($post, 'WP_Post')) {
            $is_singular = false;
        }

        if (isset($GLOBALS['wp']->query_vars['rest_route']) && false !== strpos($GLOBALS['wp']->query_vars['rest_route'], '/wc/store/v1/cart')) {
            return true;
        }

        $is_block_checkout = $is_singular && has_block('woocommerce/checkout', $post);

        return $is_block_checkout;
    }
}
if (!function_exists('itg_get_rate_instance_id')) {
    function itg_get_rate_instance_id($rate)
    {
        $instance_id = false;

        if (method_exists($rate, 'get_instance_id') && strlen(strval($rate->get_instance_id())) > 0) {
            $instance_id = $rate->get_instance_id();
        } else {
            if ($rate->method_id == 'oik_weight_zone_shipping') {
                $ids = explode('_', $rate->id);
                $instance_id = end($ids);
            } else {
                $ids = explode(':', $rate->id);
                if (count($ids) >= 2) {
                    $instance_id = $ids[1];
                }
            }
        }

        $instance_id = apply_filters('itg_shipping_get_instance_id', $instance_id, $rate);

        return $instance_id;
    }
}
if (!function_exists('itg_shipping_method_selected')) {
    function itg_shipping_method_selected($instance_id, $shipping_rules)
    {
        $shipping_method_ids = isset($shipping_rules) ? (array) $shipping_rules : [];

        $shipping_method_ids = array_map('strval', $shipping_method_ids);

        $passes = [
            'all' => in_array('all', $shipping_method_ids, true),
            'instance' => ($instance_id !== false && in_array(strval($instance_id), $shipping_method_ids, true)),
        ];

        return in_array(true, $passes, true);
    }
}

if (!function_exists('itg_get_product_count_in_cart')) {

    /**
     * Get the product count in the cart.
     *
     * @return int
     */
    function itg_get_product_count_in_cart($product_id)
    {
        $product_count = 0;
        if (!is_object(WC()->cart)) {
            return $product_count;
        }

        foreach (WC()->cart->get_cart() as $key => $value) {

            $cart_product_id = !empty($value['variation_id']) ? $value['variation_id'] : $value['product_id'];

            if ($cart_product_id != $product_id) {
                continue;
            }

            $product_count += $value['quantity'];
        }

        return $product_count;
    }
}
