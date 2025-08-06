<?php

namespace wgbl\classes\bootstrap;

use wgbl\classes\repositories\Product;

defined('ABSPATH') || exit();

class WGBL_Custom_Queries
{
    public function init()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;

        if ($search_term = $wp_query->get('wgbl_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $field   = sanitize_key($item['field']);
                    $value   = $item['value'];
                    $operator = $item['operator'];

                    $clause = '';
                    $params = [];

                    switch ($operator) {
                        case 'like':
                            $clause = "posts.{$field} LIKE %s";
                            $params[] = '%' . $value . '%';
                            break;
                        case 'exact':
                            $clause = "posts.{$field} = %s";
                            $params[] = $value;
                            break;
                        case 'not':
                            $clause = "posts.{$field} != %s";
                            $params[] = $value;
                            break;
                        case 'begin':
                            $clause = "posts.{$field} LIKE %s";
                            $params[] = $value . '%';
                            break;
                        case 'end':
                            $clause = "posts.{$field} LIKE %s";
                            $params[] = '%' . $value;
                            break;
                        case 'in':
                            $placeholders = implode(',', array_fill(0, count((array)$value), '%s'));
                            $clause = "posts.{$field} IN ($placeholders)";
                            $params = (array)$value;
                            break;
                        case 'not_in':
                            $placeholders = implode(',', array_fill(0, count((array)$value), '%s'));
                            $clause = "posts.{$field} NOT IN ($placeholders)";
                            $params = (array)$value;
                            break;
                        case 'between':
                            $clause = "posts.{$field} BETWEEN %s AND %s";
                            $params[] = $value[0];
                            $params[] = $value[1];
                            break;
                        case '>':
                            $clause = "posts.{$field} > %s";
                            $params[] = $value;
                            break;
                        case '<':
                            $clause = "posts.{$field} < %s";
                            $params[] = $value;
                            break;
                        case '>_with_quotation':
                            $clause = "posts.{$field} > %s";
                            $params[] = $value;
                            break;
                        case '<_with_quotation':
                            $clause = "posts.{$field} < %s";
                            $params[] = $value;
                            break;
                        default:
                            continue 2; // skip unknown operator
                    }

                    $product_repository = Product::get_instance();
                    $ids = $product_repository->get_ids_by_custom_query('', [
                        'clause' => $clause,
                        'params' => $params,
                    ]);

                    $ids = (!empty($ids)) ? $ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }
}
