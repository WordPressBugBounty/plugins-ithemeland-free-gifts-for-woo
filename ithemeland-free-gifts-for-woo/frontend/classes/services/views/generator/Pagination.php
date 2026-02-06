<?php

namespace wgb\frontend\classes\services\views\generator;

if (!defined('ABSPATH')) {
    exit;
}

class Pagination
{
    public function paginate(array $items, int $page, int $perPage): array
    {
        $totalItems = count($items);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        $paginatedItems = array_slice($items, $offset, $perPage);

        return [
            'items' => $paginatedItems,
            'total' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'per_page' => $perPage,
        ];
    }

    public static function render_div($pagination_id, $rule_uid)
    {
        echo '<div id="' . esc_attr($pagination_id) . '" class="wgb-pagination" data-rule-uid="' . esc_attr($rule_uid) . '"></div>';
    }
}
