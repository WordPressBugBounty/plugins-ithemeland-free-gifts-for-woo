<?php

namespace wgbl\classes\bootstrap;

use wgbl\classes\helpers\Sanitizer;

defined('ABSPATH') || exit(); // Exit if accessed directly

class WGBL_Top_Banners
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $expire_date = strtotime('2024-12-05 23:59:59');
        $current_time = time();

        if ($expire_date > $current_time && get_option('it_black_friday_banner_dismissed', 'no') == 'no' && empty(apply_filters('it_black_friday_banner', []))) {
            add_filter('it_black_friday_banner', function ($plugins) {
                $plugins['wgbl'] = 'Bulk products lite';
                return $plugins;
            });
            add_action('admin_notices', [$this, 'add_black_friday_banner']);
            add_action('admin_post_wgbl_black_friday_banner_dismiss', [$this, 'black_friday_banner_dismiss']);
        }
    }
/*
    public function add_black_friday_banner()
    {
        $url = 'https://ithemelandco.com/bfcm2024/?utm_source=plugin&utm_medium=banner&utm_campaign=BF2024';
        $output = '<style>
        .wgbl-dismiss-banner{
            position: absolute;
            top: 5px;
            right: 5px;
            color:#868686;
            border:0;
            padding: 0;
            background:transparent;
            cursor:pointer;
        }

        .wgbl-dismiss-banner i{
            color:#fff;
            font-size: 16px;
            vertical-align: middle;
        }

        .wgbl-dismiss-banner:hover,
        .wgbl-dismiss-banner:focus{
            color:#fff;
        }

        .wgbl-middle-button{
            border: 0;
            padding: 0 15px;
            background: #FF5C00;
            float: right;
            margin: 20px 130px;
            cursor: pointer;
            height: 50px;
            font-size: 16px;
            border-radius: 7px;
            -moz-border-radius: 7px;
            -webkit-border-radius: 7px;
        }
        </style>';
        $output .= '<div class="notice" style="display: inline-block; border: 0; padding: 0; background-color: transparent; box-shadow: none;"><div style="width: 100%; height: auto; display: inline-block; text-align: left; background-color: transparent;">';
        $output .= '<a style="width: 100%; float: left; position: relative;" href="' . esc_url($url) . '" target="_blank">';
        $output .= '<img style="float: left; width: 100%" src="' . WGBL_ASSETS_URL . 'images/black_friday.png" height="auto">';
        $output .= '<form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="wgbl_black_friday_banner_dismiss"><button class="wgbl-dismiss-banner" type="submit"><i class="dashicons dashicons-dismiss"></i></button></form>';
        $output .= '</a>';
        $output .= '</div></div>';

        echo wp_kses($output, Sanitizer::allowed_html_tags());
    }
*/
    public function black_friday_banner_dismiss()
    {
        update_option('it_black_friday_banner_dismissed', 'yes');
        return wp_safe_redirect(wp_get_referer());
    }
}
