<?php
function get_wc_product_attribute_ids( $product_id, $selected = array() ) {
	$attribute_ids = array();

	// Get product
	$product = wc_get_product( $product_id );

	// Product is variation, switch to parent variable product
	if ( $product->is_type( 'variation' ) ) {
		$product_id = $product->get_parent_id();
		$product    = wc_get_product( $product_id );
	}

	// Unable to load product
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return $attribute_ids;
	}

	// Get product attributes
	$attributes = $product->get_attributes();

	// Iterate over product attributes
	foreach ( $attributes as $attribute ) {

		// Only taxonomy-based attributes are supported
		if ( ! $attribute->is_taxonomy() ) {
			continue;
		}

		// Attribute is not used for variations
		if ( ! in_array( $product->get_type(), array(
				'variable',
				'variation'
			), true ) || ! $attribute->get_variation() ) {

			// Add attribute terms
			foreach ( $attribute->get_terms() as $term ) {
				$attribute_ids[] = $term->term_id;
			}
		} // Attribute is used for variations
		else {

			// Iterate over attribute terms
			foreach ( $attribute->get_terms() as $term ) {

				// Check if this term has been selected
				foreach ( $selected as $attribute_key => $selected_term_slug ) {
					if ( string_ends_with_substring( $attribute_key, $term->taxonomy ) && ( $selected_term_slug === $term->slug || $selected_term_slug === '' ) ) {
						$attribute_ids[] = $term->term_id;
					}
				}
			}
		}
	}

	return $attribute_ids;
}

function string_ends_with_substring( $string, $substring ) {
	return $substring === '' || ( ( $temp = strlen( $string ) - strlen( $substring ) ) >= 0 && strpos( $string, $substring, $temp ) !== false );
}

function get_wc_product_category_ids_from_product_ids( $product_ids ) {
	$category_ids = array();

	// Iterate over product ids
	foreach ( $product_ids as $product_id ) {

		// Get product
		if ( $product = wc_get_product( $product_id ) ) {

			// Get category ids for current product
			if ( $current_category_ids = $product->get_category_ids() ) {

				// Merge with main array
				$category_ids = array_merge( $category_ids, $current_category_ids );
			}
		}
	}

	return $category_ids;
}


function get_term_with_children( $id, $taxonomy ) {
	$term_ids = array();

	// Check if term exists
	if ( ! get_term_by( 'id', $id, $taxonomy ) ) {
		return $term_ids;
	}

	// Store parent
	$term_ids[] = (int) $id;

	// Get and store children
	$children = get_term_children( $id, $taxonomy );
	$term_ids = array_merge( $term_ids, $children );
	$term_ids = array_unique( $term_ids );

	return $term_ids;
}


function get_wc_product_tag_ids_from_product_ids( $product_ids ) {
	$tag_ids = array();

	// Iterate over product ids
	foreach ( $product_ids as $product_id ) {

		// Get tag ids
		if ( $current_ids = get_wc_product_tag_ids( $product_id ) ) {
			$tag_ids = array_merge( $tag_ids, $current_ids );
		}
	}

	return array_unique( $tag_ids );
}


function get_wc_product_tag_ids( $product_id ) {
	if ( $product = wc_get_product( $product_id ) ) {
		return $product->get_tag_ids();
	}

	return array();
}


function get_wc_product_shipping_class_id( $product_id, $variation_id = null ) {
	foreach ( array( $variation_id, $product_id ) as $id ) {

		if ( $id !== null ) {

			if ( $product = wc_get_product( $id ) ) {

				if ( ! $product->is_virtual() ) {

					if ( $class_id = $product->get_shipping_class_id() ) {
						return $class_id;
					}
				}
			}
		}
	}

	return false;
}

function user_roles( $user_id ) {

	// Get user
	$user = get_userdata( $user_id );

	// Get user roles
	return $user ? (array) $user->roles : array();
}

function user_capabilities( $user_id ) {
	// Groups plugin active?
	if ( class_exists( 'Groups_User' ) && class_exists( 'Groups_Wordpress' ) ) {
		$groups_user = new Groups_User( $user_id );

		if ( $groups_user ) {
			return $groups_user->capabilities_deep;
		} else {
			return array();
		}
	} // Get regular WP capabilities
	else {

		// Get user data
		$user                  = get_userdata( $user_id );
		$all_user_capabilities = $user->allcaps;
		$user_capabilities     = array();

		if ( is_array( $all_user_capabilities ) ) {
			foreach ( $all_user_capabilities as $capability => $status ) {
				if ( $status ) {
					$user_capabilities[] = $capability;
				}
			}
		}

		return $user_capabilities;
	}
}

function get_date_from_timeframe( $option_key ) {

	// Get timeframes
	$timeframes = get_timeframes();
	// Iterate over timeframes
	foreach ( $timeframes as $timeframe_group_key => $timeframe_group ) {

		// Check if current timeframe was requested
		if ( isset( $timeframe_group['children'][ $option_key ] ) ) {
			// Get datetime object
			return get_datetime_object( $timeframe_group['children'][ $option_key ]['value'], false );
		}
	}

	return null;
}


function get_datetime_object( $date = null, $date_is_timestamp = true ) {
	if ( $date !== null && $date_is_timestamp ) {
		$date = '@' . $date;
	}

	// Get datetime object
	$date_time = new DateTime();

	// Set timestamp if passed in
	if ( $date_is_timestamp && $date !== null ) {
		$date_time->modify( $date );
	}

	// Set correct time zone
	$time_zone = get_time_zone();
	$date_time->setTimezone( $time_zone );

	// Set date if passed in
	if ( ! $date_is_timestamp && $date !== null ) {
		$date_time->modify( $date );
	}

	return $date_time;
}

function get_time_zone() {
	return new DateTimeZone( get_time_zone_string() );
}

function php_version_gte( $version ) {
	return version_compare( PHP_VERSION, $version, '>=' );
}

function get_wc_order_ids( $params = array() ) {
	// Start building query
	$config = array(
		'return' => 'ids',
		'limit'  => - 1,
	);

	// Date
	if ( ! empty( $params['date'] ) && is_object( $params['date'] ) ) {
		$config['date_created'] = '>=' . $params['date']->format( 'U' );
	}

	// Customer id
	if ( ! empty( $params['customer_id'] ) ) {
		$config['customer_id'] = $params['customer_id'];
	}

	// Billing email
	if ( ! empty( $params['billing_email'] ) ) {
		$config['billing_email'] = $params['billing_email'];
	}

	// Order statuses
	if ( ! empty( $params['status'] ) ) {
		$config['status'] = array_map( function ( $status ) {
			return ( substr( $status, 0, 3 ) === 'wc-' ) ? substr( $status, 3 ) : $status;
		}, (array) $params['status'] );
	}
	// Get order ids
	$order_ids = wc_get_orders( $config );

	// Return order ids
	return is_array( $order_ids ) ? $order_ids : array();
}

function order_get_meta( $order, $key, $single = true, $context = 'view' ) {
	return get_meta( $order, $key, $single, $context, 'order', 'post' );
}


function get_meta( $object, $key, $single, $context, $store, $legacy_store ) {
	// Load object
	if ( ! is_object( $object ) ) {
		$object = load_object( $object, $store );
	}

	// Internal meta is not supported
	if ( is_internal_meta( $object, $key ) ) {
		return $single ? '' : array();
	}

	// Get meta
	return $object ? $object->get_meta( $key, $single, $context ) : false;
}


function load_object( $object_id, $store ) {
	$method = 'wc_get_' . $store;

	if ( $method == 'wc_get_order' ) {
		return wc_get_order( $object_id );
	} else if ( $method == 'wc_get_order' ) {
		return wc_get_product( $object_id );
	} else if ( $method == 'wc_get_customer' ) {
		$customer = new WC_Customer( $object_id );

		return $customer->get_id() ? $customer : false;
	} else if ( $method == 'wc_get_order_item' ) {
		try {
			$order_item = new WC_Order_Item_Product( $object_id );

			return $order_item->get_id() ? $order_item : false;
		} catch ( Exception $e ) {
			return false;
		}
	}
}

function is_internal_meta( $object, $key, $suppress_warning = false ) {
	// Get data store
	if ( is_callable( array( $object, 'get_data_store' ) ) ) {
		if ( $data_store = $object->get_data_store() ) {

			// Get internal meta keys
			if ( is_callable( array( $data_store, 'get_internal_meta_keys' ) ) ) {
				if ( $internal_meta_keys = $data_store->get_internal_meta_keys() ) {

					// Key is internal meta key
					if ( in_array( $key, $internal_meta_keys, true ) ) {

						// Maybe add warning
						if ( ! $suppress_warning ) {
							error_log( 'methods must not be used to interact with WooCommerce internal meta (used key "' . $key . '").' );
						}

						return true;
					} // Key is regular meta key
					else {
						return false;
					}
				}
			}
		}
	}

	return false;
}

function get_timeframes() {
	// Define timeframes
	$timeframes = array(

		// Current
		'current' => array(
			'label'    => __( 'Current', 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'children' => array(
				'current_day' => array(
					'label' => __( 'current day', 'ithemeland-free-gifts-for-woocommerce-lite' ),
					'value' => 'midnight',
				),

				'current_month' => array(
					'label' => __( 'current month', 'ithemeland-free-gifts-for-woocommerce-lite' ),
					'value' => 'midnight first day of this month',
				),
				'current_year'  => array(
					'label' => __( 'current year', 'ithemeland-free-gifts-for-woocommerce-lite' ),
					'value' => 'midnight first day of january',
				),
			),
		),

		// Days
		'days'    => array(
			'label'    => __( 'Days', 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'children' => array(),
		),

		// Weeks
		'weeks'   => array(
			'label'    => __( 'Weeks', 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'children' => array(),
		),

		// Months
		'months'  => array(
			'label'    => __( 'Months', 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'children' => array(),
		),

		// Years
		'years'   => array(
			'label'    => __( 'Years', 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'children' => array(),
		),
	);

	// Generate list of days
	for ( $i = 1; $i <= 6; $i ++ ) {
		$timeframes['days']['children'][ $i . '_day' ] = array(
			'label' => $i . ' ' . _n( 'day', 'days', $i, 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'value' => '-' . $i . ( $i === 1 ? ' day' : ' days' ),
		);
	}

	// Generate list of weeks
	for ( $i = 1; $i <= 4; $i ++ ) {
		$timeframes['weeks']['children'][ $i . '_week' ] = array(
			'label' => $i . ' ' . _n( 'week', 'weeks', $i, 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'value' => '-' . $i . ( $i === 1 ? ' week' : ' weeks' ),
		);
	}

	// Generate list of months
	for ( $i = 1; $i <= 12; $i ++ ) {
		$timeframes['months']['children'][ $i . '_month' ] = array(
			'label' => $i . ' ' . _n( 'month', 'months', $i, 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'value' => '-' . $i . ( $i === 1 ? ' month' : ' months' ),
		);
	}

	// Generate list of years
	for ( $i = 2; $i <= 10; $i ++ ) {
		$timeframes['years']['children'][ $i . '_year' ] = array(
			'label' => $i . ' ' . _n( 'year', 'years', $i, 'ithemeland-free-gifts-for-woocommerce-lite' ),
			'value' => '-' . $i . ( $i === 1 ? ' year' : ' years' ),
		);
	}

	// Allow developers to override
	$timeframes = $timeframes;

	return $timeframes;
}

function get_wc_order_is_paid_statuses( $include_prefix = false ) {
	$statuses = wc_get_is_paid_statuses();

	return $include_prefix ? preg_filter( '/^/', 'wc-', $statuses ) : $statuses;
}

function date_create_from_format_finction( $format, $value ) {
	$timezone_string = get_time_zone_string();
	$timezone        = new DateTimeZone( $timezone_string );

	return DateTime::createFromFormat( $format, $value, $timezone );
}

function get_time_zone_string() {
	// Timezone string
	if ( $time_zone = get_option( 'timezone_string' ) ) {
		return $time_zone;
	}

	// Offset
	if ( $utc_offset = get_option( 'gmt_offset' ) ) {

		// Offsets supported
		if ( php_version_gte( '5.5.10' ) ) {
			return ( $utc_offset < 0 ? '-' : '+' ) . gmdate( 'Hi', floor( abs( $utc_offset ) * 3600 ) );
		} // Offsets not supported
		else {

			$utc_offset = $utc_offset * 3600;
			$dst        = gmdate( 'I' );

			// Try to get timezone name from offset
			if ( $time_zone = timezone_name_from_abbr( '', $utc_offset ) ) {
				return $time_zone;
			}

			// Try to guess timezone by looking at a list of all timezones
			foreach ( timezone_abbreviations_list() as $abbreviation ) {
				foreach ( $abbreviation as $city ) {
					if ( $city['dst'] == $dst && $city['offset'] == $utc_offset && isset( $city['timezone_id'] ) ) {
						return $city['timezone_id'];
					}
				}
			}
		}
	}

	return 'UTC';
}

function string_begins_with_substring( $string, $substring ) {
	return $substring === '' || strpos( $string, $substring ) === 0;
}

function wc_filter_order_items( $order_items, $params ) {
	$items = array();

	// Prepare items
	foreach ( $order_items as $order_item_key => $order_item ) {

		// Get product id
		$product_id   = $order_item->get_product_id();
		$variation_id = $order_item->get_variation_id();

		// Get attributes
		$attribute_ids = array();

		if ( ! empty( $product_id ) && ! empty( $variation_id ) ) {

			// Get selected variation attributes
			$selected = array();

			// Load variation
			$variation = wc_get_product( $variation_id );

			// Get variation attributes
			$attributes = $variation->get_variation_attributes();

			// Get selected attributes
			foreach ( $attributes as $attribute_key => $attribute_value ) {

				// Value is set
				if ( $attribute_value !== '' ) {
					$selected[ $attribute_key ] = $attribute_value;
				} // Search for value
				else {
					$selected[ $attribute_key ] = $order_item->get_meta( str_replace( 'attribute_', '', $attribute_key ), true, 'edit' );
				}
			}

			// Get attribute ids
			$attribute_ids = get_wc_product_attribute_ids( $product_id, $selected );
		}

		// Fill items array
		$items[ $order_item_key ] = array(
			'product_id'    => ! empty( $product_id ) ? (string) $product_id : null,
			'variation_id'  => ! empty( $variation_id ) ? (string) $variation_id : null,
			'attribute_ids' => $attribute_ids,
		);
	}

	// Filter items
	$items = wc_filter_items( $items, $params );

	// Filter order items by remaining items
	$order_items = array_intersect_key( $order_items, $items );

	// Return filtered order items array
	return $order_items;
}

function wc_filter_items( $items, $params ) {
	$filtered = array();

	// Iterate over items
	foreach ( $items as $item_key => $item ) {
		// Products
		if ( ! empty( $params['products'] ) ) {
			if ( empty( $item['product_id'] ) || ! in_array( $item['product_id'], $params['products'] ) ) {

				continue;
			}
		}
		// Product variations
		if ( ! empty( $params['variations'] ) ) {
			if ( empty( $item['variation_id'] ) || ! in_array( (string) $item['variation_id'], $params['variations'], true ) ) {
				continue;
			}
		}
		// Product categories
		if ( ! empty( $params['categories'] ) ) {

			// No product id
			if ( empty( $item['product_id'] ) ) {
				continue;
			}

			// Get item category ids
			$item_category_ids = get_wc_product_category_ids_from_product_ids( array( $item['product_id'] ) );
			$item_category_ids = array_map( 'strval', $item_category_ids );

			// Get condition category ids with children
			$condition_category_ids = array();

			foreach ( $params['categories'] as $category_id ) {
				$condition_category_ids = array_merge( $condition_category_ids, get_term_with_children( $category_id, 'product_cat' ) );
			}

			$condition_category_ids = array_map( 'strval', $condition_category_ids );

			// Get matching category ids
			$matching_category_ids = array_intersect( $item_category_ids, $condition_category_ids );

			// Check if at least one category id is matching
			if ( empty( $matching_category_ids ) ) {
				continue;
			}
		}

		// Product attributes
		if ( ! empty( $params['attributes'] ) ) {

			// Get item attribute ids
			$item_attribute_ids = array_map( 'strval', $item['attribute_ids'] );

			// Get matching attribute ids
			$matching_attribute_ids = array_intersect( $item_attribute_ids, $params['attributes'] );
//			print_r($params['attributes']);

			// Check if at least one attribute id is matching
			if ( empty( $matching_attribute_ids ) ) {
//				die;
				continue;
			}
		}

		// Product tags
		if ( ! empty( $params['tags'] ) ) {

			// No product id
			if ( empty( $item['product_id'] ) ) {
				continue;
			}

			// Get item tag ids
			$item_tag_ids = get_wc_product_tag_ids_from_product_ids( array( $item['product_id'] ) );
			$item_tag_ids = array_map( 'strval', $item_tag_ids );

			// Get matching tag ids
			$matching_tag_ids = array_intersect( $item_tag_ids, $params['tags'] );

			// Check if at least one tag id is matching
			if ( empty( $matching_tag_ids ) ) {
				continue;
			}
		}

		// Shipping classes
		if ( ! empty( $params['shipping_classes'] ) ) {

			// Get item shipping class id
			$shipping_class_id = (string) get_wc_product_shipping_class_id( $item['product_id'], $item['variation_id'] );

			// Check if shipping class id is in list
			if ( ! in_array( $shipping_class_id, $params['shipping_classes'], true ) ) {
				continue;
			}
		}

		// If we ended up here, item matches all criteria
		$filtered[ $item_key ] = $item;

	}

	return $filtered;
}

function get_current_week_value() {
	// Today is first day of week
	if ( (int) get_adjusted_datetime( null, 'w' ) === get_start_of_week() ) {
		return 'midnight';
	} else {
		return 'midnight last ' . get_literal_start_of_week();
	}
}

function get_adjusted_datetime( $timestamp = null, $format = null ) {
	// Get timestamp
	$timestamp = ( $timestamp !== null ? $timestamp : time() );

	// Get datetime object
	$date_time = get_datetime_object( $timestamp );

	// Get datetime as string in ISO format
	$date_time_iso = $date_time->format( 'Y-m-d H:i:s' );

	// Hack to make date_i18n() work with our time zone
	$date_time_utc = new DateTime( $date_time_iso );
	$time_zone_utc = new DateTimeZone( 'UTC' );
	$date_time_utc->setTimezone( $time_zone_utc );

	// Get format
	$format = ( $format !== null ? $format : ( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) );

	// Format and return
	return date_i18n( $format, $date_time_utc->format( 'U' ) );
}

function get_start_of_week() {
	return intval( get_option( 'start_of_week', 0 ) );
}

function get_literal_start_of_week() {
	$weekdays = array(
		0 => 'sunday',
		1 => 'monday',
		2 => 'tuesday',
		3 => 'wednesday',
		4 => 'thursday',
		5 => 'friday',
		6 => 'saturday',
	);

	$start_of_week = get_start_of_week();

	return $weekdays[ $start_of_week ];
}

function it_date_time() {
	$date = get_datetime_object();
	$date->setTime( 0, 0, 0 );

	return $date;
}

function it_date_time_weekend() {
	$date = get_datetime_object();

	return $date->format( 'w' );
}

function it_date_time_time() {
	return get_datetime_object();
//	return $date->format( 'H:i' );
}

function get_datetime( $option_key, $condition_value ) {
	// Get condition date
	try {
		$condition_date = get_datetime_object( $condition_value, false );
		$condition_date->setTime( 0, 0, 0 );

		return $condition_date;
	} catch ( Exception $e ) {
		return false;
	}
}

function array_is_multidimensional( $array ) {
	return count( $array ) !== count( $array, COUNT_RECURSIVE );
}

function it_get_cart_subtotal( $item_cart ) {
	$items_count = array(
		'flag'                                     => false,
		'quantity'                                 => 0,
		'quantity_repeat'                          => 0,
		'quantity_number_gift_allow_for_each_line' => 0,
		'subtotal'                                 => 0,
		'subtotal_with_tax'                        => 0,
	);

	foreach ( $item_cart as $cart_item_key => $cart_item ) {
		//subtotal
		
		$items_count['quantity']          += 1;
		$include_tax                      = wc_tax_enabled();
		$items_count['subtotal_with_tax'] += $cart_item['line_subtotal'];
		$items_count['subtotal']          += $cart_item['line_subtotal'];
		if ( isset( $cart_item['line_subtotal_tax'] ) && $include_tax ) {
			$items_count['subtotal_with_tax'] += $cart_item['line_subtotal_tax'];
			//Remove later
			$items_count['subtotal'] += $cart_item['line_subtotal_tax'];
		}
	}

	return $items_count;
}

function get_wc_cart_subtotal( $include_tax = true ) {
	global $woocommerce;

	if ( is_object( $woocommerce ) && isset( $woocommerce->cart ) && is_object( $woocommerce->cart ) ) {

		// Including tax
		if ( $include_tax && isset( $woocommerce->cart->subtotal ) ) {
			return $woocommerce->cart->subtotal;
		} // Excluding tax
		else if ( isset( $woocommerce->cart->subtotal_ex_tax ) ) {
			return $woocommerce->cart->subtotal_ex_tax;
		}
	}

	return 0;
}
?>