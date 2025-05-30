<?php
function itg_check_quantity_gift_in_session()
{
    $count_gift         = 0;
    $count_rule_gift    = array();
    $count_rule_product = array();
    $gifts_set          = [];
	$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
    if (is_array($retrieved_group_input_value) && count($retrieved_group_input_value) > 0) {
        foreach ($retrieved_group_input_value as $index => $set) {
            $count_gift  += $set['q'];
            $gifts_set[] = $set['id'];
            if (array_key_exists($set['uid'], $count_rule_gift)) {
                $count_rule_gift[$set['uid']]['q'] += $set['q'];
            } else {
                $count_rule_gift[$set['uid']]['q'] = $set['q'];
            }

            if (array_key_exists($index, $count_rule_product)) {
                $count_rule_product[$index]['q'] += $set['q'];
            } else {
                $count_rule_product[$index]['q'] = $set['q'];
            }
        }
    }

    $count_info = [
        'count_gift'         => $count_gift,
        'count_rule_gift'    => $count_rule_gift,
        'count_rule_product' => $count_rule_product,
        'gifts_set'          => $gifts_set,
    ];

    return $count_info;
}

function itg_get_cart_item_quantities_gift_stock()
{
    $quantities                 = array();
    $retrieved_group_input_value = WC()->session->get('gift_group_order_data');
	//echo '<pre>';print_r($retrieved_group_input_value);die;
    if (is_array($retrieved_group_input_value) && count($retrieved_group_input_value) > 0) {

        foreach ($retrieved_group_input_value as $index => $set) {
            if (isset($set['id_product'])) {
                $quantities[$set['id_product']] = isset($quantities[$set['id_product']]) ? $quantities[$set['id_product']] + $set['q'] : $set['q'];
            }
        }
    }

    return $quantities;
}


function itg_get_cart_item_stock_quantities()
{
	if ( ! is_object( WC()->cart ) ) {
		return '';
	}
	$filter_items=[];
	foreach ( WC()->cart->get_cart() as $key => $value ) {
		$product = $value['data'];
		$filter_items[$product->get_stock_managed_by_id()] = isset($filter_items[$product->get_stock_managed_by_id()]) ? $filter_items[$product->get_stock_managed_by_id()] + $value['quantity'] : $value['quantity'];		
	}
	
    return $filter_items;	
}

function itg_quantities_gift_stock($product, $product_qty_in_cart, $gift_id, $product_type, $settings, $item_hover)
{
    $text_stock_qty = '';
    if ($settings['show_stock_quantity'] != 'no') {
        $text_stock_qty = 'in stock';
    }
    $stock_status = 'in_stock';
    $get_stock_quantity = $product->get_stock_quantity();
    if (!$product->is_in_stock() && $get_stock_quantity <= 0) {
        $item_hover     = 'disable-hover';
        $text_stock_qty = 'out of stock';
        $stock_status = 'out_of_stock';
    } else if ($product->is_in_stock() && $get_stock_quantity >= 1) {
        $x = 0;
        $get_cart_item_quantities_gift_stock = itg_get_cart_item_quantities_gift_stock();
        $required_stock_in_cart_gift = isset($get_cart_item_quantities_gift_stock[$gift_id]) ? $get_cart_item_quantities_gift_stock[$gift_id] : 0;
        $required_stock_in_cart = isset($product_qty_in_cart[$product->get_stock_managed_by_id()]) ? $product_qty_in_cart[$product->get_stock_managed_by_id()] : 0;
        $x = $get_stock_quantity - ($required_stock_in_cart + $required_stock_in_cart_gift);

        if ($x <= 0) {
            $item_hover = 'disable-hover';
            $text_stock_qty = 'out of stock';
            $stock_status = 'out_of_stock';
        } else if ($product_type != 'variable' && $settings['show_stock_quantity'] == 'true') {
            $text_stock_qty = $x . ' ' . __('in stock', 'ithemeland-free-gifts-for-woo');
        } elseif ($product_type == 'variable' && $settings['show_stock_quantity'] == 'true') {
            $text_stock_qty = sprintf('%s', $x) . ' ' . __('in stock', 'ithemeland-free-gifts-for-woo');
        } else {
            $text_stock_qty = '';
        }
    }

    return [
        'item_hover'     => $item_hover,
        'text_stock_qty' => $text_stock_qty,
        'stock_status' => $stock_status,
    ];
}

function itg_get_settings()
{

    $settings = wgbl\classes\repositories\Setting::get_instance();
    $settings = $settings->get();

    $settings['position']                             = isset($settings['position']) ? $settings['position'] : 'bottom_cart';
    $settings['show_stock_quantity']                  = isset($settings['show_stock_quantity']) ? 'true' : 'no';
    $settings['layout']                               = isset($settings['layout']) ? $settings['layout'] : 'grid';
    $settings['child']                                = isset($settings['child']) ? 'true' : 'false';
    $settings['view_gift_in_cart']['number_per_page'] = isset($settings['view_gift_in_cart']['number_per_page']) ? $settings['view_gift_in_cart']['number_per_page'] : '4';;
    $settings['view_gift_in_cart']['desktop_columns'] = isset($settings['view_gift_in_cart']['desktop_columns']) ? $settings['view_gift_in_cart']['desktop_columns'] : 'wgb-col-md-2';;
    $settings['view_gift_in_cart']['tablet_columns'] = isset($settings['view_gift_in_cart']['tablet_columns']) ? $settings['view_gift_in_cart']['tablet_columns'] : 'wgb-col-sm-2';;
    $settings['view_gift_in_cart']['mobile_columns'] = isset($settings['view_gift_in_cart']['mobile_columns']) ? $settings['view_gift_in_cart']['mobile_columns'] : 'wgb-col-2';;
    $settings['view_gift_in_cart']['carousel']['loop'] = isset($settings['view_gift_in_cart']['carousel']['loop']) ? 'true' : 'false';;
    $settings['view_gift_in_cart']['carousel']['dots'] = isset($settings['view_gift_in_cart']['carousel']['dots']) ? 'true' : 'false';;
    $settings['view_gift_in_cart']['carousel']['nav'] = isset($settings['view_gift_in_cart']['carousel']['nav']) ? 'true' : 'false';;
    $settings['view_gift_in_cart']['carousel']['speed'] = isset($settings['view_gift_in_cart']['carousel']['speed']) ? $settings['view_gift_in_cart']['carousel']['speed'] : '5000';
    $settings['view_gift_in_cart']['carousel']['desktop'] = isset($settings['view_gift_in_cart']['carousel']['desktop']) ? $settings['view_gift_in_cart']['carousel']['desktop'] : '6';;
    $settings['view_gift_in_cart']['carousel']['tablet'] = isset($settings['view_gift_in_cart']['carousel']['tablet']) ? $settings['view_gift_in_cart']['carousel']['tablet'] : '2';;
    $settings['view_gift_in_cart']['carousel']['mobile'] = isset($settings['view_gift_in_cart']['carousel']['mobile']) ? $settings['view_gift_in_cart']['carousel']['mobile'] : '1';
    $settings['display_price'] = isset($settings['display_price']) ? $settings['display_price'] : 'no';
    $settings['enable_ajax_add_to_cart'] = isset($settings['enable_ajax_add_to_cart']) ? $settings['enable_ajax_add_to_cart'] : 'false';

    return $settings;
}

if (!function_exists('itg_render_product_image')) {
    function itg_render_product_image($product, $echo = true)
    {

        if ($echo) {
            echo wp_kses_post($product->get_image());
        }

        return $product->get_image();
    }
}

if (!function_exists('itg_get_gift_lite_products_data_multilevel')) {
	function itg_get_gift_lite_products_data_multilevel( $args = array() ) {
		$rule_products=['items'=>[] , 'settings' => $args['settings'] , 'is_child'=>$args['is_child']];
				
		$quantity_in_session = itg_check_quantity_gift_in_session();
		
		$gift_quantity_in_cart = itg_get_cart_item_quantities_gift_stock();
		
		foreach ($args['gifts_items_cart'] as $gift_item_key => $gifts_items_cart) {
			$get_parent_id=$gifts_items_cart['item'];
			$product = itg_lite_get_product($get_parent_id);
			
			if (!$product) {
				continue;
			}
			if (!($product->is_purchasable() && $product->is_in_stock() ) ) 
			{
				continue ;
			}
			$eligible_product = array();
			
			$gift_allowed = $args['all_gift_items'][$gifts_items_cart['uid']]['pw_number_gift_allowed'];
			//Number Allow For Other Method
			if (in_array($args['all_gift_items'][$gifts_items_cart['uid']]['method'], array(
				'buy_x_get_x_repeat'
			), true) && $args['all_gift_items'][$gifts_items_cart['uid']]['based_on'] == 'ind') {
				
				$gift_allowed = $args['all_gift_items']['all_gifts'][$gift_item_key]['q'];
			}
				
			$product_ids = ( 'variable' == $product->get_type() ) ? $product->get_children() : array($get_parent_id);
			$flag_parent_status = false;
			foreach ($product_ids as $get_product_id) {
				
				if (isset($args['gift_rule_exclude'][$gifts_items_cart['uid']]) && in_array(
					$get_product_id,
					$args['gift_rule_exclude'][$gifts_items_cart['uid']]
				)) {
					continue;
				}

				$args_data=[
					'product_id'=>$get_product_id,
					'rule' => $gifts_items_cart,
					'products_in_cart'=>$args['quantity_products_in_cart'],
					'gifts_in_cart'=>$gift_quantity_in_cart,
					'gift_allowed'=>$gift_allowed,
					'quantities_in_session'=>$quantity_in_session,
					'all_gift_items'=>$args['all_gift_items'],
					];
					
				$stock_status = itg_lite_get_product_stock_status( $args_data );
				//echo '<pre>';print_r($stock_status);die;
				if (!itg_lite_check_is_array($eligible_product)) {	
					$eligible_product = array(
						'parent_id' => $get_parent_id,
						'product_id' => $get_product_id,
						'rule_id' => $gifts_items_cart['uid'],
						'qty' => $gift_allowed,
						'hide_add_to_cart' => $stock_status['hide_add_to_cart'],
						'stock_qty'=>$stock_status['stock_qty'],
						'variation_ids' => array(),
					);
				}

				// Consider the valid variation in variable product.
				if ('variable' == $product->get_type()) {
	
					$eligible_product['variation_ids'][] = 
					[
						'id'=>$get_product_id ,
						'hide_add_to_cart' => $stock_status['hide_add_to_cart'],
						'stock_qty'=>$stock_status['stock_qty'],
						
					];
				}
				if($stock_status['hide_add_to_cart']== false){
					$flag_parent_status = true;
				}
		
			}
			if (itg_lite_check_is_array($eligible_product)) {
				if ( $flag_parent_status && 'variable' == $product->get_type() && itg_lite_check_is_array($eligible_product['variation_ids'])) {
					$eligible_product['hide_add_to_cart'] = false;
				}

				$rule_products['items'][] = $eligible_product;
			}		
		}
	//	if(!$args['multi_level'])
		//	$rule_products = itg_get_gift_products_data_one_level($rule_products);
		
		//echo '<pre>';print_r($rule_products);die;
		
		//return $rule_products;
		
	}
}

if (!function_exists('itg_lite_get_product_stock_status')) {
	function itg_lite_get_product_stock_status( $args_data ) {
		//echo '<pre>';print_r($args_data);die;
		$qty=0;
		$x = 0;
		$z=0;
		$hide_add_to_cart     = false;
		$count_rule_gift=0;
		$product_id = $args_data['product_id'];
		$rule_id = $args_data['rule']['uid'];
		$rule = $args_data['rule'];
		$quantities_in_session = $args_data['quantities_in_session'];
		$products_in_cart = $args_data['products_in_cart'];

		$product = itg_lite_get_product($product_id);
		$gift_id = $rule['uid'] . '-' . $product_id;
		$get_stock_quantity = $product->get_stock_quantity();		
		if (!$product->is_in_stock() && $get_stock_quantity <= 0) {
			$hide_add_to_cart     = true;
			$qty=0;
		} else
		{
						
			$count_gift_in_gift = isset($args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q']) ? $args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q'] : 0;
			
			$count_in_cart = isset($products_in_cart[$product->get_stock_managed_by_id()]) ? $products_in_cart[$product->get_stock_managed_by_id()] : 0;

			if ($product->is_in_stock() && $get_stock_quantity >= 1) {
				$x = $get_stock_quantity - ($count_in_cart + $count_gift_in_gift);			
			}
	
			$z= $args_data['gift_allowed'] - $count_gift_in_gift;

			if($z>=$x && ($product->is_in_stock() && $get_stock_quantity >= 1))
			{
				$qty=$x;
			}
			else
			{
				$qty=$z;
			}	

			if(array_key_exists($rule['uid'], $quantities_in_session['count_rule_gift'])) {
				 $count_rule_gift=$quantities_in_session['count_rule_gift'][$rule['uid']]['q'];
			}
				
			$y=$args_data['gift_allowed'] - $count_rule_gift;

			if($qty >$y)
			{
				$qty=$y;

			}
			if ($qty <= 0 ) {			
				$hide_add_to_cart     = true;				
			} else if(in_array( $gift_id , $quantities_in_session['gifts_set'] ) && $rule['can_several_gift']=='no'){
				$hide_add_to_cart     = true;	
			}
			else if($rule['can_several_gift']=='no'){
				//$hide_add_to_cart     = false;
				$qty=1;
			}
		}
		$disable = false;
		if (in_array($rule['method'], array('buy_x_get_x_repeat'), true) && $rule['base_q'] == 'ind') {

			if (array_key_exists($rule['key'], $quantities_in_session['count_rule_product']) && $quantities_in_session['count_rule_product'][$rule['key']]['q'] >= $args_data['gift_allowed']) {
				$disable = true;
			}
		} elseif (array_key_exists($rule['uid'], $quantities_in_session['count_rule_gift']) && $quantities_in_session['count_rule_gift'][$rule['uid']]['q'] >= $args_data['gift_allowed']) {
			$disable = true;
		}
		
		if (
			$disable ||
			(in_array($gift_id, $quantities_in_session['gifts_set']) && $rule['can_several_gift'] == 'no')
			||
			(in_array($gift_id, $quantities_in_session['gifts_set']) && $args_data['all_gift_items'][$rule['uid']]['can_several_gift'] == 'no')
		) {
			$hide_add_to_cart = true;
			//$eligible_product['hide_add_to_cart']=false;
		}/**/
		
		$data=[
			'stock_qty' => $qty,
			'hide_add_to_cart' => $hide_add_to_cart ,
		];	
		return $data;
	}
}

if (!function_exists('itg_lite_check_is_array')) {
	/**
	 * Check if the resource is array.
	 *
	 * @return bool
	 */
	function itg_lite_check_is_array( $data) {
		return ( is_array($data) && !empty($data) );
	}
}

if (!function_exists('itg_lite_get_product')) {

	/**
	 * Get the product object by product id.
	 *
	 * @return object/bool
	 */
	function itg_lite_get_product( $product_id) {
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
		return apply_filters('itg_lite_get_product', wc_get_product($product_id), $product_id);
	}
}

if (!function_exists('itg_get_gift_products_data_multilevel')) {
    function itg_get_gift_products_data_multilevel($args = array())
    {
        $rule_products = ['items' => [], 'settings' => $args['settings'], 'is_child' => $args['is_child']];
		
		if(!isset($args['gifts_items_cart']['gifts']) || !itg_lite_check_is_array($args['gifts_items_cart']['gifts']))
		{
			return $rule_products;
		}
        $quantity_in_session = itg_check_quantity_gift_in_session(WC()->cart->get_cart());

        $gift_quantity_in_cart = itg_get_cart_items_gift_quantities();


        foreach ($args['gifts_items_cart']['gifts'] as $gift_item_key => $gifts_items_cart) {

            $get_parent_id = $gifts_items_cart['item'];
            $product = itg_get_product($get_parent_id);

            if (!$product) {
                continue;
            }
            if (!($product->is_purchasable() && $product->is_in_stock())) {
                continue;
            }
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
//echo '<pre>';print_r($stock_status);die;
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
        //echo '<pre>d';print_r($args_data);die;
        $qty = 0;
        $x = 0;
        $z = 0;
        $hide_add_to_cart     = false;
        $count_rule_gift = 0;
        $product_id = $args_data['product_id'];
        $rule_id = $args_data['rule']['uid'];
        $rule = $args_data['rule'];
        $quantities_in_session = $args_data['quantities_in_session'];
        $products_in_cart = $args_data['products_in_cart'];

        $product = itg_get_product($product_id);
        $gift_id = $rule['uid'] . '-' . $product_id;
        $get_stock_quantity = $product->get_stock_quantity();
        if (!$product->is_in_stock() && $get_stock_quantity <= 0) {
            $hide_add_to_cart     = true;
            $qty = 0;
        } else {

            $count_gift_in_gift = isset($args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q']) ? $args_data['quantities_in_session']['count_rule_gift'][$rule_id]['q'] : 0;

            $count_in_cart = isset($products_in_cart[$product->get_stock_managed_by_id()]) ? $products_in_cart[$product->get_stock_managed_by_id()] : 0;

            if ($product->is_in_stock() && $get_stock_quantity >= 1) {
                $x = $get_stock_quantity - ($count_in_cart + $count_gift_in_gift);
            }

            $z = $args_data['gift_allowed'] - $count_gift_in_gift;

            if ($z >= $x && ($product->is_in_stock() && $get_stock_quantity >= 1)) {
                $qty = $x;
            } else {
                $qty = $z;
            }

            if (array_key_exists($rule['uid'], $quantities_in_session['count_rule_gift'])) {
                $count_rule_gift = $quantities_in_session['count_rule_gift'][$rule['uid']]['q'];
            }

            $y = $args_data['gift_allowed'] - $count_rule_gift;

            if ($qty > $y) {
                $qty = $y;
            }
            if ($qty <= 0) {
                $hide_add_to_cart     = true;
            } else if (in_array($gift_id, $quantities_in_session['gifts_set']) && $rule['can_several_gift'] == 'no') {
                $hide_add_to_cart     = true;
            } else if ($rule['can_several_gift'] == 'no') {
                //$hide_add_to_cart     = false;
                $qty = 1;
            }
        }
        $disable = false;
        if (in_array($rule['method'], array('buy_x_get_x_repeat'), true) && $rule['base_q'] == 'ind') {

            if (array_key_exists($rule['key'], $quantities_in_session['count_rule_product']) && $quantities_in_session['count_rule_product'][$rule['key']]['q'] >= $args_data['gift_allowed']) {
                $disable = true;
            }
        } elseif (array_key_exists($rule['uid'], $quantities_in_session['count_rule_gift']) && $quantities_in_session['count_rule_gift'][$rule['uid']]['q'] >= $args_data['gift_allowed']) {
            $disable = true;
        }

        if (
            $disable ||
            (in_array($gift_id, $quantities_in_session['gifts_set']) && $rule['can_several_gift'] == 'no')
            ||
            (in_array($gift_id, $quantities_in_session['gifts_set']) && $args_data['all_gift_items'][$rule['uid']]['can_several_gift'] == 'no')
        ) {
            $hide_add_to_cart = true;
            //$eligible_product['hide_add_to_cart']=false;
        }/**/

        $data = [
            'stock_qty' => $qty,
            'hide_add_to_cart' => $hide_add_to_cart,
        ];
        return $data;
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

if (!function_exists('itg_get_template')) {
    function itg_get_template($template_name, $args = array())
    {
        wc_get_template($template_name, $args, 'ithemeland-free-gifts-for-woo/', plugin_dir_path_wc_adv_gift . 'views/');
    }
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

if (!function_exists('itg_render_stock_status')) {
    function itg_render_stock_status($stock_qty, $settings, $gift_product, $echo = true)
    {

        if ($gift_product['add_or_select'] == 'select') {
            $stack_status =  __('Available Gift', 'ithemeland-free-gifts-for-woo');
        } else if ($stock_qty <= 0) {
            $stack_status = __('Gift Unavailable', 'ithemeland-free-gifts-for-woo');
        } else {
            $stack_status =  __('Available Gift', 'ithemeland-free-gifts-for-woo') . ' : ' . $stock_qty;
        }

        if ($echo) {
            echo wp_kses_post($stack_status);
        }

        return $stack_status;
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

        $limit_char = 20; // limit character from settings;
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

if (!function_exists('itg_get_gift_product_add_to_cart_classes')) {
	/**
	 * Get the gift product add to cart classes.
	 *
	 *  @return array
	 */
	function itg_get_gift_product_add_to_cart_classes($settings) {
		
		$classes = array( 'wgb-add-gift-btn');

		if($settings['enable_ajax_add_to_cart'] == 'true'){
			$classes[] = 'btn-click-add-gift-button' ;
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
	function itg_get_gift_product_add_to_cart_url( $gift_product, $permalink = '' ) {
		
		$settings = itg_get_settings();

		if ('true' == $settings['enable_ajax_add_to_cart'] ) {
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

if (!function_exists('itg_render_title_product_gift')) {
    function itg_render_title_product_gift($title, $gift_id, $settings, $echo = true)
    {
        $retun = '';
        if ('true' != $settings['hide-gift']) {
            $retun = '<a href="' . get_permalink($gift_id) . '">' . sprintf("%s", $title) . '</a>';
        } else {
            $retun = $title;
        }


        if ($echo) {
            echo wp_kses_post($retun);
        }

        return $retun;
    }
}

if (!function_exists('itg_is_block_cart')) {

	/**
	 * Is a block cart page?.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	function itg_is_block_cart() {
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
	function itg_is_block_checkout() {
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