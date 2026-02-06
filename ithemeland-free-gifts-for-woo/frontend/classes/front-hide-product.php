<?php

use wgb\classes\repositories\Rule;

/**
 *  Handles the frontend.
 * */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (! class_exists('It_front_hide_gift')) {

    /**
     * Class.
     * */
    class It_front_hide_gift
    {
        public static $buffer_on = true;
        /**
         * Class Initialization.
         * */
        public static function init()
        {
            // 
        }

        //Hide add to cart single page
        /*
		public static function on_before_add_to_cart_button()
		{
			// Return if free gifts does not exists.
			$free_products = self::get_all_gift_hide();
			if ( ! is_array( $free_products ) ) {
				return '';
			}
			global $product;
			$product_id = $product->get_id();			
			if ( in_array($product_id , $free_products ) ) {
				It_front_hide_gift::$buffer_on = ob_start();
			}			
			
		}
		*/
        public static function on_before_add_to_cart_button()
        {
            // Return if free gifts do not exist.
            $free_products = self::get_all_gift_hide();

            if (! is_array($free_products)) {
                return '';
            }

            global $product;

            // Check if $product is set and has the method get_id.
            if (isset($product) && method_exists($product, 'get_id')) {
                $product_id = $product->get_id();

                if (in_array($product_id, $free_products)) {
                    It_front_hide_gift::$buffer_on = ob_start();
                }
            } else {
                // Optional: Log or handle the case where $product is not set or is invalid.
                // error_log('Warning: $product is null or does not have a get_id method in It_front_hide_gift::on_before_add_to_cart_button');
            }
        }

        //Hide add to cart single page
        public static function on_after_add_to_cart_button()
        {
            // Return if free gifts does not exists.
            $free_products = self::get_all_gift_hide();
            if (! is_array($free_products)) {
                return '';
            }
            global $product;
            $product_id = $product->get_id();
            if (in_array($product_id, $free_products)) {
                if (It_front_hide_gift::$buffer_on) {
                    ob_end_clean();
                }
            }
        }

        //hide add to cart shop page
        public static function hide_add_to_cart_loop($link, $product)
        {
            //check single product For Guest
            global $product;
            $product_id = $product->get_id();

            // Return if free gifts does not exists.
            $free_products = self::get_all_gift_hide();
            if (! is_array($free_products)) {
                return '';
            }
            global $product;
            if (in_array(absint($product_id), $free_products)) {
                return '';
            }

            return $link;
        }

        public static function pre_get_posts($query)
        {

            // Return if a query is not the main query. 
            if (! $query->is_main_query()) {
                return;
            }

            // Return if the admin page. 
            if (is_admin()) {
                return;
            }

            // Return if the post type is product post type.
            if (! isset($query->query_vars['post_type']) || 'product' != $query->query_vars['post_type']) {
                return;
            }

            // Return if free gifts does not exists.
            $free_products = It_front_hide_gift::get_all_gift_hide();
            if (! is_array($free_products)) {
                return;
            }

            $post_not_in = $query->get('post__not_in');
            $post_not_in = (is_array($post_not_in)) ? array_merge($post_not_in, $free_products) : $free_products;

            // Set post is not in gift product ids.
            $query->set('post__not_in', $post_not_in);
        }

        public static function alter_product_is_visible($visible, $product_id)
        {

            // Return if the admin page. 
            if (is_admin()) {
                return $visible;
            }

            // Return if free gifts does not exists.
            $free_products = self::get_all_gift_hide();
            if (! is_array($free_products)) {
                return $visible;
            }

            if (in_array($product_id, $free_products)) {
                return false;
            }

            return $visible;
        }

        public static function alter_shortcode_products_query($args, $attributes, $type)
        {

            // Return if the admin page. 
            if (is_admin()) {
                return $args;
            }

            // Return if free gifts does not exists.
            $free_products = self::get_all_gift_hide();
            if (! is_array($free_products)) {
                return $args;
            }

            $post__not_in = $free_products;
            if (isset($args['post__not_in']) && is_array($args['post__not_in'])) {
                $post__not_in = array_merge($args['post__not_in'], $post__not_in);
            }

            $args['post__not_in'] = $post__not_in; //phpcs:ignore

            return $args;
        }

        public static function get_all_gift_hide()
        {
            $rules = wgb\classes\repositories\Rule::get_instance();
            $rules_item = $rules->get();
            $final_arrray = [];
            $array_merge = [];
            foreach ($rules_item['items'] as $rule_key => $rule_value) {
                if ($rule_value['status'] != 'enable') {
                    continue;
                }
                $return_query = $rules->get_option_cache($rule_value);
                $array_merge = array_merge($array_merge, $return_query['pw_gifts_cache_simple_variation_']);
                //pw_gifts_cache_simple_variation_
                //pw_gifts_cache_simple_childes_
            }
            $final_arrray = array_unique($array_merge);
            return $final_arrray;
        }
    }
    It_front_hide_gift::init();
}
