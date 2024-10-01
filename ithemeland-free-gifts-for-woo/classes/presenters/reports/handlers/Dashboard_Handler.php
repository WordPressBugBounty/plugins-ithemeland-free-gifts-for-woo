<?php

namespace wgbl\classes\presenters\reports\handlers;

use wgbl\classes\helpers\Date_Helper;
use wgbl\classes\helpers\Plugin_Helper;
use wgbl\classes\presenters\reports\Handler_Interface;
use wgbl\classes\repositories\Order;
use wgbl\classes\repositories\Product;
use wgbl\classes\repositories\Rule;

class Dashboard_Handler implements Handler_Interface
{
    private static $instance;

    private $rule_repository;
    private $product_repository;
    private $order_repository;
    private $orders_used_gift;
    private $data;
    private $all_rules;
    private $rules_name;
    private $rules_count;
    private $used_rules;
    private $order_item_ids;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->rule_repository = Rule::get_instance();
        $this->product_repository = Product::get_instance();
        $this->order_repository = Order::get_instance();

        // get all rules
        $rules = $this->rule_repository->get();
        $this->all_rules = (!empty($rules['items'])) ? $rules['items'] : [];
        $this->rules_name = array_column($this->all_rules, 'rule_name', 'uid');
    }

    public function get_reports($data)
    {
        if (empty($data['from']) || empty($data['to'])) {
            return false;
        }

        $this->data = $data;

        // sanitize dates
        $this->sanitize_dates();

        // get used rules by date
        $this->orders_used_gift = $this->order_repository->get_orders_used_gift([
            'date' => [
                'from' => sanitize_text_field($this->data['from']),
                'to' => sanitize_text_field($this->data['to'])
            ]
        ]);

        // get used rules
        $this->used_rules = $this->rule_repository->get_used_rules(sanitize_text_field($data['from']), sanitize_text_field($data['to']));
        $this->rule_ids = [];
        $this->rules_count = [];
        $this->order_item_ids = [];

        if (!empty($this->used_rules) && is_array($this->used_rules)) {
            $this->rule_ids = array_column($this->used_rules, 'meta_value');
            $this->order_item_ids = array_unique(array_column($this->used_rules, 'order_item_id'));
            $this->rules_count = array_count_values($this->rule_ids);
        }

        return [
            'total_gift_count' => $this->get_total_gift_count(),
            'total_customers' => $this->get_total_customers(),
            'number_of_used_rule' => $this->get_number_of_used_rule(),
            'number_of_orders' => $this->get_number_of_orders(),
            'chart1' => $this->get_chart1(),
            'chart2' => $this->get_chart2(),
            'recent_orders_used_the_gift' => $this->get_recent_orders_used_the_gift(),
            'top_methods' => $this->get_top_methods(),
            'top_rules' => $this->get_top_rules(),
            'top_gifts' => $this->Get_top_gifts(),
            'top_categories' => $this->get_top_categories(),
            'top_countries' => $this->get_top_countries(),
            'top_states' => $this->get_top_states(),
            'recent_customers_get_gift' => $this->get_recent_customers_get_gift(),
            'used_gifts' => $this->get_used_gifts(),
        ];
    }

    private function sanitize_dates()
    {
        $this->data['from'] = gmdate('Y-m-d', strtotime($this->data['from'])) . ' 00:00:00';
        $this->data['to'] = gmdate('Y-m-d', strtotime($this->data['to'])) . ' 23:59:59';
    }

    private function get_total_gift_count()
    {
        return !empty($this->rule_ids && is_array($this->rule_ids)) ? count($this->rule_ids) : 0;
    }

    private function get_total_customers()
    {
        $customers = $this->rule_repository->get_total_customers_used_gift(sanitize_text_field($this->data['from']), sanitize_text_field($this->data['to']));
        return (!empty($customers) && is_array($customers)) ? count($customers) : 0;
    }

    private function get_number_of_used_rule()
    {
        return !empty($this->rule_ids && is_array($this->rule_ids)) ? count(array_unique($this->rule_ids)) : 0;
    }

    private function get_number_of_orders()
    {
        return (!empty($this->orders_used_gift) && is_array($this->orders_used_gift)) ? count($this->orders_used_gift) : 0;
    }

    private function get_chart1()
    {
        return [
            'day' => $this->order_repository->get_chart1_data_by_order_item_ids($this->order_item_ids, 'day'),
            'week' => $this->order_repository->get_chart1_data_by_order_item_ids($this->order_item_ids, 'week'),
            'month' => $this->order_repository->get_chart1_data_by_order_item_ids($this->order_item_ids, 'month'),
            'year' => $this->order_repository->get_chart1_data_by_order_item_ids($this->order_item_ids, 'year'),
        ];
    }

    private function get_chart2()
    {
        $output['product'] = [];
        $output['category'] = [];

        // get top products
        $top_products = $this->product_repository->get_products_by_item_ids($this->order_item_ids, 5);
        if (!empty($top_products) && is_array($top_products)) {
            foreach ($top_products as $product) {
                if (!empty($product['product_count'])) {
                    $output['product'][] = [
                        'name' => (isset($product['product_name'])) ? sanitize_text_field($product['product_name']) : '',
                        'count' => sanitize_text_field($product['product_count']),
                    ];
                }
            }
        }

        // get top categories
        $categories = $this->product_repository->get_product_category_with_count_by_order_item_ids($this->order_item_ids, 5);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if (!empty($category['object']) && !empty($category['count']) && $category['object'] instanceof \WP_Term) {
                    $output['category'][] = [
                        'name' => (mb_strlen($category['object']->name) > 13) ? mb_substr($category['object']->name, 0, 12) . ' ...' : sanitize_text_field($category['object']->name),
                        'count' => intval($category['count']),
                    ];
                }
            }
        }

        return $output;
    }

    private function get_recent_orders_used_the_gift()
    {
        $output = [];

        if (!empty($this->orders_used_gift)) {
            foreach (array_slice($this->orders_used_gift, 0, 10) as $order) {
                if (!empty($order['order_id'])) {
                    $rules_name = [];
                    $rule_ids = (!empty($order['rule_ids'])) ? explode(',', $order['rule_ids']) : [];
                    if (!empty($rule_ids)) {
                        foreach (array_unique($rule_ids) as $rule_id) {
                            $rules_name[$rule_id] = !empty($this->rules_name[$rule_id]) ? sanitize_text_field($this->rules_name[$rule_id]) : sanitize_text_field($rule_id);
                        }
                    }

                    $output[] = [
                        'order_id' => intval($order['order_id']),
                        'order_link' => admin_url("post.php?post={$order['order_id']}&action=edit"),
                        'order_date' => (!empty($order['order_date'])) ? Date_Helper::get_nice_date_format(strtotime($order['order_date'])) : '',
                        'order_status' => (!empty($order['order_status'])) ? sanitize_text_field($order['order_status']) : '',
                        'rules_name' => (!empty($rules_name)) ? $rules_name : '',
                        'gifts_name' => (!empty($order['product_names'])) ? $order['product_names'] : ''
                    ];
                }
            }
        }

        return $output;
    }

    private function get_top_methods()
    {
        $top_methods = [];
        $rule_methods = array_column($this->all_rules, 'method', 'uid');

        $all_methods = $this->rule_repository->get_rule_methods();
        if (!empty($rule_methods)) {
            foreach ($rule_methods as $rule_id => $method) {
                if (!empty($this->rules_count[$rule_id])) {
                    if (isset($top_methods[$method])) {
                        $top_methods[$method]['count'] += sanitize_text_field($this->rules_count[$rule_id]);
                    } else {
                        $top_methods[$method] = [
                            'method_name' => sanitize_text_field($method),
                            'method_label' => (!empty($all_methods[$method])) ? sanitize_text_field($all_methods[$method]) : sanitize_text_field($method),
                            'count' => sanitize_text_field($this->rules_count[$rule_id])
                        ];
                    }
                }
            }
        }

        usort($top_methods, function ($a, $b) {
            return - ($a['count'] - $b['count']);
        });

        return array_slice($top_methods, 0, 5);
    }

    private function get_top_rules()
    {
        $top_rules = [];

        if (!empty($this->rules_name)) {
            foreach ($this->rules_name as $rule_id => $name) {
                if (!empty($this->rules_count[$rule_id])) {
                    $top_rules[] = [
                        'rule_name' => (!empty($name)) ? sanitize_text_field($name) : sanitize_text_field($rule_id),
                        'count' => sanitize_text_field($this->rules_count[$rule_id])
                    ];
                }
            }
        }

        usort($top_rules, function ($a, $b) {
            return - ($a['count'] - $b['count']);
        });

        return array_slice($top_rules, 0, 5);
    }

    private function get_top_gifts()
    {
        $top_gifts = [];

        $products = $this->product_repository->get_products_by_item_ids($this->order_item_ids, 5);
        if (!empty($products)) {
            foreach ($products as $product) {
                if (!empty($product['product_count'])) {
                    $product_id = intval($product['product_id']);
                    $top_gifts[] = [
                        'product_id' => $product_id,
                        'product_name' => sanitize_text_field($product['product_name']),
                        'count' => sanitize_text_field($product['product_count'])
                    ];
                }
            }
        }

        return $top_gifts;
    }

    private function get_top_categories()
    {
        $top_categories = [];

        $categories = $this->product_repository->get_product_category_with_count_by_order_item_ids($this->order_item_ids, 5);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if (!empty($category['object']) && !empty($category['count']) && $category['object'] instanceof \WP_Term) {
                    $category_id = intval($category['object']->term_id);
                    $top_categories[] = [
                        'category_link' => "term.php?taxonomy=product_cat&tag_ID={$category_id}&post_type=product",
                        'category_name' => sanitize_text_field($category['object']->name),
                        'count' => sanitize_text_field($category['count'])
                    ];
                }
            }
        }

        return $top_categories;
    }

    private function get_top_countries()
    {
        $top_countries = [];
        $countries = $this->order_repository->get_order_countries_with_count_by_order_item_ids($this->order_item_ids);
        $wc_countries = WC()->countries->countries;

        if (!empty($countries)) {
            foreach ($countries as $country) {
                if (!empty($country['country_name']) && !empty($country['country_count'])) {
                    $country_name = (!empty($wc_countries[$country['country_name']])) ? sanitize_text_field($wc_countries[$country['country_name']]) : sanitize_text_field($country['country_name']);
                    $top_countries[] = [
                        'country_name' => $country_name,
                        'count' => sanitize_text_field($country['country_count'])
                    ];
                }
            }
        }

        return $top_countries;
    }

    private function get_top_states()
    {
        $top_states = [];
        $states = $this->order_repository->get_order_states_with_count_by_order_item_ids($this->order_item_ids);
        if (!empty($states)) {
            foreach ($states as $state) {
                if (!empty($state['state_name']) && !empty($state['country_name']) && !empty($state['state_count'])) {
                    $states = WC()->countries->get_states(sanitize_text_field($state['country_name']));
                    $state_name = (!empty($states)) ? $states[sanitize_text_field($state['state_name'])] : sanitize_text_field($state['state_name']);
                    $top_states[] = [
                        'state_name' => $state_name,
                        'count' => sanitize_text_field($state['state_count'])
                    ];
                }
            }
        }

        return $top_states;
    }

    private function get_recent_customers_get_gift()
    {
        $output = [];
        if (!empty($this->orders_used_gift)) {
            foreach (array_slice($this->orders_used_gift, 0, 10) as $order) {
                if (!empty($order['order_id'])) {
                    $output[] = [
                        'customer_email' => (!empty($order['user_email'])) ? $order['user_email'] : '',
                        'customer_name' => (!empty($order['display_name'])) ? $order['display_name'] : '',
                        'customer_id' => (!empty($order['user_id'])) ? $order['user_id'] : '',
                        'customer_username' => (!empty($order['user_login'])) ? $order['user_login'] : '',
                        'used_gifts' => (!empty($order['product_names'])) ? $order['product_names'] : '',
                        'order_link' => admin_url("post.php?post={$order['order_id']}&action=edit"),
                        'order_id' => $order['order_id'],
                        'order_date' => (!empty($order['order_date'])) ? Date_Helper::get_nice_date_format(strtotime($order['order_date'])) : '',
                    ];
                }
            }
        }

        return $output;
    }

    private function get_used_gifts()
    {
        $output = [];
        $products = $this->product_repository->get_products_by_item_ids($this->order_item_ids);
        if (!empty($products)) {
            foreach ($products as $product) {
                if (!empty($product['product_id'])) {
                    $product_id = intval($product['product_id']);
                    $sku = get_post_meta($product_id, '_sku', true);

                    // get product categories
                    $product_categories = '';
                    $categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'id=>name']);
                    if (!empty($categories)) {
                        $i = 1;
                        foreach ($categories as $category_id => $category_name) {
                            $product_categories .= "<a href='term.php?taxonomy=product_cat&tag_ID={$category_id}&post_type=product'>{$category_name}</a>";
                            if (count($categories) > $i) {
                                $product_categories .= ", ";
                            }
                            $i++;
                        }
                    }

                    // get product brands
                    $product_brands = '';
                    if (Plugin_Helper::it_brands_is_active()) {
                        $brands = wp_get_post_terms($product_id, 'as-brand', ['fields' => 'id=>name']);
                        if (!empty($brands)) {
                            $i = 1;
                            foreach ($brands as $brand_id => $brand_name) {
                                $product_brands .= "<a href='term.php?taxonomy=as-brand&tag_ID={$brand_id}&post_type=product'>{$brand_name}</a>";
                                if (count($brands) > $i) {
                                    $product_brands .= ", ";
                                }
                                $i++;
                            }
                        }
                    }


                    $output[] = [
                        'product_link' => admin_url("post.php?post={$product_id}&action=edit"),
                        'product_name' => sanitize_text_field($product['product_name']),
                        'sku' => sanitize_text_field($sku),
                        'brand' => $product_brands,
                        'category' => $product_categories,
                        'count' => sanitize_text_field($product['product_count'])
                    ];
                }
            }
        }

        return $output;
    }
}
