<?php

namespace wgbl\classes\bootstrap;

use wgbl\classes\repositories\Product;

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
                    $field = esc_sql($item['field']);
                    $value = trim(esc_sql($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(posts.{$field} LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(posts.{$field} = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(posts.{$field} != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(posts.{$field} LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(posts.{$field} LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(posts.{$field} IN ({$value}))";
                            break;
                        case 'not_in':
                            $custom_where = "(posts.{$field} NOT IN ({$value}))";
                            break;
                        case 'between':
                            $value = (is_numeric($value[1])) ? "{$value[0]} AND {$value[1]}" : "'{$value[0]}' AND '{$value[1]}'";
                            $custom_where = "(posts.{$field} BETWEEN {$value})";
                            break;
                        case '>':
                            $custom_where = "(posts.{$field} > {$value})";
                            break;
                        case '<':
                            $custom_where = "(posts.{$field} < {$value})";
                            break;
                        case '>_with_quotation':
                            $custom_where = "(posts.{$field} > '{$value}')";
                            break;
                        case '<_with_quotation':
                            $custom_where = "(posts.{$field} < '{$value}')";
                            break;
                    }

                    $product_repository = Product::get_instance();
                    $ids = $product_repository->get_ids_by_custom_query('', $custom_where);
                    $ids = (!empty($ids)) ? $ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }
}
