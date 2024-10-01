<?php

namespace wgbl\classes\repositories;

use wgbl\classes\helpers\Array_Helper;

class User
{
    public function get_users_by_name($name)
    {
        $args = array(
            'search' => '*' . sanitize_text_field($name) . '*',
            'search_columns' => array('user_nicename', 'display_name')
        );
        $users = new \WP_User_Query($args);
        return $users;
    }

    public function get_users_by_id($user_ids)
    {
        $output = [];
        $users = get_users([
            'include' => Array_Helper::flatten($user_ids),
        ]);
        if (!empty($users)) {
            foreach ($users as $user) {
                if ($user instanceof \WP_User) {
                    $output[$user->ID] = $user->user_nicename;
                }
            }
        }
        return $output;
    }

    public function get_user_roles()
    {
        $output = [];
        $roles = wp_roles();
        if (!empty($roles)) {
            foreach ($roles->roles as $roleKey => $role) {
                $output[$roleKey] = $role['name'];
            }
        }
        return $output;
    }

    public function get_user_capabilities()
    {
        $output = [];
        $capabilities = get_role('administrator')->capabilities;
        if (!empty($capabilities)) {
            foreach ($capabilities as $capability => $value) {
                $output[$capability] = $capability;
            }
        }
        return $output;
    }
}
