<?php

namespace wgb\classes\api;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\repositories\Rule;

class Api_Rule_Controller
{
    private $namespace;

    public function __construct()
    {
        $this->namespace = 'wgb';
    }

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/rules',
            [
                'methods' => ['GET', 'POST'],
                'callback' => array($this, 'get_rules'),
                'permission_callback' => array($this, 'permissions_check'),
            ]
        );
    }

    public function get_rules()
    {
        $rule_repository = Rule::get_instance();
        $rules = $rule_repository->get();
        return rest_ensure_response((!empty($rules)) ? $rules : []);
    }

    public function permissions_check($request)
    {
        return (isset($request['auth_key']) && $request['auth_key'] == '663b7c27-116b-4467-92b4-c0e50afa189f');
    }
}
