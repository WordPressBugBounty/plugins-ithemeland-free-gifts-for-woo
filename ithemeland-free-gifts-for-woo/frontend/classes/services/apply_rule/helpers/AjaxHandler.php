<?php

namespace wgb\frontend\classes\services\apply_rule\helpers;

use wgb\frontend\classes\services\views\generator\Pagination;

class AjaxHandler
{
    public function __construct()
    {
        add_action('wp_ajax_load_rules', [$this, 'handle_load_rules']);
        add_action('wp_ajax_nopriv_load_rules', [$this, 'handle_load_rules']);
    }

    // In your AjaxHandler class
    public function handle_load_rules()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; //phpcs:ignore
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 5; //phpcs:ignore


        $response = [
            'page' => $page,
            'per_page' => $per_page,
            'total_items' => 0
        ];

        wp_send_json_success($response);
    }
}
new AjaxHandler();
