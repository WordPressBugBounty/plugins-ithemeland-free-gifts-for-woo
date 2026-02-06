<?php

namespace wgb\classes\api;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Api_Handler
{
    private static $instance;

    public static function init()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_rest_routes()
    {
        $controllers = apply_filters('wgb_api_controllers', $this->get_controllers());

        if (!empty($controllers)) {
            foreach ($controllers as $controller) {
                if (class_exists($controller)) {
                    $controller_object = new $controller();
                    if (method_exists($controller_object, 'register_routes')) {
                        $controller_object->register_routes();
                    }
                }
            }
        }
    }

    private function get_controllers()
    {
        return [
            Api_Rule_Controller::class,
        ];
    }
}
