<?php


function get_wc_product_absolute_id($product)
{
    // Load product object
    if (! is_a($product, 'WC_Product')) {
        $product = wc_get_product($product);
    }

    // Return appropriate id
    return $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
}

function user_roles($user_id)
{

    // Get user
    $user = get_userdata($user_id);

    // Get user roles
    return $user ? (array) $user->roles : array();
}


function user_capabilities($user_id)
{
    // Groups plugin active?
    if (class_exists('Groups_User') && class_exists('Groups_Wordpress')) {
        $groups_user = new Groups_User($user_id);

        if ($groups_user) {
            return $groups_user->capabilities_deep;
        } else {
            return array();
        }
    } // Get regular WP capabilities
    else {

        // Get user data
        $user                  = get_userdata($user_id);
        $all_user_capabilities = $user->allcaps;
        $user_capabilities     = array();

        if (is_array($all_user_capabilities)) {
            foreach ($all_user_capabilities as $capability => $status) {
                if ($status) {
                    $user_capabilities[] = $capability;
                }
            }
        }

        return $user_capabilities;
    }
}


function get_datetime_object($date = null, $date_is_timestamp = true)
{
    if ($date !== null && $date_is_timestamp) {
        $date = '@' . $date;
    }

    // Get datetime object
    $date_time = new DateTime();

    // Set timestamp if passed in
    if ($date_is_timestamp && $date !== null) {
        $date_time->modify($date);
    }

    // Set correct time zone
    $time_zone = get_time_zone();
    $date_time->setTimezone($time_zone);

    // Set date if passed in
    if (! $date_is_timestamp && $date !== null) {
        $date_time->modify($date);
    }

    return $date_time;
}

function get_time_zone()
{
    return new DateTimeZone(get_time_zone_string());
}

function php_version_gte($version)
{
    return version_compare(PHP_VERSION, $version, '>=');
}



function order_get_meta($order, $key, $single = true, $context = 'view')
{
    return get_meta($order, $key, $single, $context, 'order', 'post');
}


function get_meta($object, $key, $single, $context, $store, $legacy_store)
{
    // Load object
    if (! is_object($object)) {
        $object = load_object($object, $store);
    }

    // Internal meta is not supported
    if (is_internal_meta($object, $key)) {
        return $single ? '' : array();
    }

    // Get meta
    return $object ? $object->get_meta($key, $single, $context) : false;
}


function load_object($object_id, $store)
{
    $method = 'wc_get_' . $store;

    if ($method == 'wc_get_order') {
        return wc_get_order($object_id);
    } else if ($method == 'wc_get_order') {
        return wc_get_product($object_id);
    } else if ($method == 'wc_get_customer') {
        $customer = new WC_Customer($object_id);

        return $customer->get_id() ? $customer : false;
    } else if ($method == 'wc_get_order_item') {
        try {
            $order_item = new WC_Order_Item_Product($object_id);

            return $order_item->get_id() ? $order_item : false;
        } catch (Exception $e) {
            return false;
        }
    }
}

function is_internal_meta($object, $key, $suppress_warning = false)
{
    // Get data store
    if (is_callable(array($object, 'get_data_store'))) {
        if ($data_store = $object->get_data_store()) {

            // Get internal meta keys
            if (is_callable(array($data_store, 'get_internal_meta_keys'))) {
                if ($internal_meta_keys = $data_store->get_internal_meta_keys()) {

                    // Key is internal meta key
                    if (in_array($key, $internal_meta_keys, true)) {

                        // Maybe add warning
                        // if (! $suppress_warning) {
                            // error_log('methods must not be used to interact with WooCommerce internal meta (used key "' . $key . '").');
                        // }

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

function get_timeframes()
{
    // Define timeframes
    $timeframes = array(

        // Current
        'current' => array(
            'label'    => esc_html__('Current', 'ithemeland-free-gifts-for-woo'),
            'children' => array(
                'current_day' => array(
                    'label' => esc_html__('current day', 'ithemeland-free-gifts-for-woo'),
                    'value' => 'midnight',
                ),

                'current_month' => array(
                    'label' => esc_html__('current month', 'ithemeland-free-gifts-for-woo'),
                    'value' => 'midnight first day of this month',
                ),
                'current_year'  => array(
                    'label' => esc_html__('current year', 'ithemeland-free-gifts-for-woo'),
                    'value' => 'midnight first day of january',
                ),
            ),
        ),

        // Days
        'days'    => array(
            'label'    => esc_html__('Days', 'ithemeland-free-gifts-for-woo'),
            'children' => array(),
        ),

        // Weeks
        'weeks'   => array(
            'label'    => esc_html__('Weeks', 'ithemeland-free-gifts-for-woo'),
            'children' => array(),
        ),

        // Months
        'months'  => array(
            'label'    => esc_html__('Months', 'ithemeland-free-gifts-for-woo'),
            'children' => array(),
        ),

        // Years
        'years'   => array(
            'label'    => esc_html__('Years', 'ithemeland-free-gifts-for-woo'),
            'children' => array(),
        ),
    );

    // Generate list of days
    for ($i = 1; $i <= 6; $i++) {
        $timeframes['days']['children'][$i . '_day'] = array(
            'label' => $i . ' ' . _n('day', 'days', $i, 'ithemeland-free-gifts-for-woo'),
            'value' => '-' . $i . ($i === 1 ? ' day' : ' days'),
        );
    }

    // Generate list of weeks
    for ($i = 1; $i <= 4; $i++) {
        $timeframes['weeks']['children'][$i . '_week'] = array(
            'label' => $i . ' ' . _n('week', 'weeks', $i, 'ithemeland-free-gifts-for-woo'),
            'value' => '-' . $i . ($i === 1 ? ' week' : ' weeks'),
        );
    }

    // Generate list of months
    for ($i = 1; $i <= 12; $i++) {
        $timeframes['months']['children'][$i . '_month'] = array(
            'label' => $i . ' ' . _n('month', 'months', $i, 'ithemeland-free-gifts-for-woo'),
            'value' => '-' . $i . ($i === 1 ? ' month' : ' months'),
        );
    }

    // Generate list of years
    for ($i = 2; $i <= 10; $i++) {
        $timeframes['years']['children'][$i . '_year'] = array(
            'label' => $i . ' ' . _n('year', 'years', $i, 'ithemeland-free-gifts-for-woo'),
            'value' => '-' . $i . ($i === 1 ? ' year' : ' years'),
        );
    }

    // Allow developers to override
    $timeframes = $timeframes;

    return $timeframes;
}

function get_wc_order_is_paid_statuses($include_prefix = false)
{
    $statuses = wc_get_is_paid_statuses();

    return $include_prefix ? preg_filter('/^/', 'wc-', $statuses) : $statuses;
}

function get_checkout_billing_email()
{
    // Check for specific ajax requests
    if (!empty($_GET['wc-ajax']) && in_array($_GET['wc-ajax'], array('update_order_review', 'checkout'), true)) { //phpcs:ignore

        $billing_email = null;

        // Check if request contains billing email
        if (!empty($_POST['billing_email'])) { //phpcs:ignore
            $billing_email = sanitize_email($_POST['billing_email']); //phpcs:ignore
        } else if (!empty($_POST['post_data'])) { //phpcs:ignore

            parse_str($_POST['post_data'], $checkout_data); //phpcs:ignore

            if (!empty($checkout_data['billing_email'])) {
                $billing_email = $checkout_data['billing_email'];
            }
        }

        // Validate billing email format
        if (filter_var($billing_email, FILTER_VALIDATE_EMAIL)) {
            return $billing_email;
        }
    }

    return null;
}

function date_create_from_format_finction($format, $value)
{
    $timezone_string = get_time_zone_string();
    $timezone        = new DateTimeZone($timezone_string);

    return DateTime::createFromFormat($format, $value, $timezone);
}

function get_time_zone_string()
{
    // Timezone string
    if ($time_zone = get_option('timezone_string')) {
        return $time_zone;
    }

    // Offset
    if ($utc_offset = get_option('gmt_offset')) {

        // Offsets supported
        if (php_version_gte('5.5.10')) {
            return ($utc_offset < 0 ? '-' : '+') . gmdate('Hi', floor(abs($utc_offset) * 3600));
        } // Offsets not supported
        else {

            $utc_offset = $utc_offset * 3600;
            $dst        = gmdate('I');

            // Try to get timezone name from offset
            if ($time_zone = timezone_name_from_abbr('', $utc_offset)) {
                return $time_zone;
            }

            // Try to guess timezone by looking at a list of all timezones
            foreach (timezone_abbreviations_list() as $abbreviation) {
                foreach ($abbreviation as $city) {
                    if ($city['dst'] == $dst && $city['offset'] == $utc_offset && isset($city['timezone_id'])) {
                        return $city['timezone_id'];
                    }
                }
            }
        }
    }

    return 'UTC';
}

function get_current_week_value()
{
    // Today is first day of week
    if ((int) get_adjusted_datetime(null, 'w') === get_start_of_week()) {
        return 'midnight';
    } else {
        return 'midnight last ' . get_literal_start_of_week();
    }
}

function get_adjusted_datetime($timestamp = null, $format = null)
{
    // Get timestamp
    $timestamp = ($timestamp !== null ? $timestamp : time());

    // Get datetime object
    $date_time = get_datetime_object($timestamp);

    // Get datetime as string in ISO format
    $date_time_iso = $date_time->format('Y-m-d H:i:s');

    // Hack to make date_i18n() work with our time zone
    $date_time_utc = new DateTime($date_time_iso);
    $time_zone_utc = new DateTimeZone('UTC');
    $date_time_utc->setTimezone($time_zone_utc);

    // Get format
    $format = ($format !== null ? $format : (get_option('date_format') . ' ' . get_option('time_format')));

    // Format and return
    return date_i18n($format, $date_time_utc->format('U'));
}

function get_start_of_week()
{
    return intval(get_option('start_of_week', 0));
}

function get_literal_start_of_week()
{
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

    return $weekdays[$start_of_week];
}

function it_date_time()
{
    $date = get_datetime_object();
    $date->setTime(0, 0, 0);

    return $date;
}

function it_date_time_weekend()
{
    $date = get_datetime_object();

    return $date->format('w');
}

function it_date_time_time()
{
    return get_datetime_object();
    //	return $date->format( 'H:i' );
}

function get_datetime($option_key, $condition_value)
{
    // Get condition date
    try {
        $condition_date = get_datetime_object($condition_value, false);
        $condition_date->setTime(0, 0, 0);

        return $condition_date;
    } catch (Exception $e) {
        return false;
    }
}

function array_is_multidimensional($array)
{
    return count($array) !== count($array, COUNT_RECURSIVE);
}


function group_quantities($filter_items_by_rules, $quantities_based_on)
{

    $quantities = array();
    // Get Quantities Based On method
    $based_on = $quantities_based_on;

    foreach ($filter_items_by_rules as $cart_item_key => $cart_item) {
        $quantity = $cart_item['quantity'];

        // Get absolute product id (i.e. parent product id for variations)
        $product_id = get_wc_product_absolute_id($cart_item['data']);

        $quantities[$product_id][$cart_item_key] = $quantity;

    }
    // Return quantities
    return $quantities;
}

function group_prices($filter_items_by_rules, $quantities_based_on)
{

    $prices = array();
    // Get prices Based On method
    $based_on = $quantities_based_on;

    $include_tax = wc_tax_enabled();


    foreach ($filter_items_by_rules as $cart_item_key => $cart_item) {
        $quantity = $cart_item['quantity'];

        $item_price = 0;
        $item_price = $cart_item['line_subtotal'];
        if (isset($cart_item['line_subtotal_tax']) && $include_tax) {
            $item_price += $cart_item['line_subtotal_tax'];
        }

        // Get absolute product id (i.e. parent product id for variations)
        $product_id = get_wc_product_absolute_id($cart_item['data']);

        $prices[$product_id][$cart_item_key] = $item_price;
    }

    // Return prices
    return $prices;
}



function it_get_cart_subtotal($item_cart)
{
    $items_count = array(
        'flag'                                     => false,
        'quantity'                                 => 0,
        'quantity_repeat'                          => 0,
        'quantity_number_gift_allow_for_each_line' => 0,
        'subtotal'                                 => 0,
        'subtotal_with_tax'                        => 0,
    );

    foreach ($item_cart as $cart_item_key => $cart_item) {
        //subtotal

        /* //Compatible with WPC Product Bundles for WooCommerce
		if(isset($cart_item['woosb_keys']))
		{
			$items_count['subtotal'] += $cart_item['woosb_price'];
		}
		if(!isset($cart_item['woosb_key']))
		{
			$items_count['subtotal'] += $cart_item['line_subtotal'];
			if ( isset( $cart_item['line_subtotal_tax'] ) && $include_tax ) {
				$items_count['subtotal'] += $cart_item['line_subtotal_tax'];
			}
		}
		*/
        $items_count['quantity']          += 1;
        $include_tax                      = wc_tax_enabled();

        // Check if 'line_subtotal' key exists
        if (isset($cart_item['line_subtotal'])) {
            $items_count['subtotal_with_tax'] += $cart_item['line_subtotal'];
            $items_count['subtotal']          += $cart_item['line_subtotal'];
        }

        // Check if 'line_subtotal_tax' key exists and tax is enabled
        if (isset($cart_item['line_subtotal_tax']) && $include_tax) {
            $items_count['subtotal_with_tax'] += $cart_item['line_subtotal_tax'];
            // Remove later if needed
            $items_count['subtotal'] += $cart_item['line_subtotal_tax'];
        }
    }
    //	$items_count['subtotal']= WC()->cart->cart_contents_total;
    /*
	add_filter('it_gift_cart_subtotal','it_gift_cart_subtotal');
	function it_gift_cart_subtotal($items_cart_subtotal){
		  $items_cart_subtotal['subtotal']= WC()->cart->cart_contents_total;
		  return $items_cart_subtotal;
	}
	*/
    return apply_filters('it_gift_cart_subtotal', $items_count);
}


if (!function_exists('itg_get_cart_contents')) {
    /**
     * Check if the resource is array.
     *
     * @return bool
     */
    function itg_get_cart_contents()
    {
        if (! is_object(WC()->cart)) {
            return '';
        }
        $filter_items = [];
        foreach (WC()->cart->get_cart() as $key => $value) {

            if (isset($value['it_free_gift'])) {
                continue;
            }
            $filter_items[$key] = $value;
        }
        return $filter_items;
    }
}