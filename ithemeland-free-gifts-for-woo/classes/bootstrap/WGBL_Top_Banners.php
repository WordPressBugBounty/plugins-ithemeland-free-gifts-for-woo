<?php

namespace WGB\classes\bootstrap;

use wgb\classes\helpers\Sanitizer;

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
        add_action('all_admin_notices', [$this, 'ithemeland_admin_notice_plugin_lite']);
        add_action('admin_post_wgb_activate_core_plugin', [$this, 'activate_core_plugin']);
    }

    public function ithemeland_admin_notice_plugin_lite()
    {
        $plugin_file = 'ithemeland-free-gifts-for-woo/ithemeland-free-gifts-for-woo.php';

        // If plugin active don't show
        if (is_plugin_active($plugin_file)) {
            return;
        }

        if (current_user_can('install_plugins')) {
            $plugin_slug = 'ithemeland-free-gifts-for-woo';
            $install_url = wp_nonce_url(
                self_admin_url("update.php?action=install-plugin&plugin=$plugin_slug"),
                "install-plugin_$plugin_slug"
            );

            $this->ithemeland_notice_display($install_url);
        }
    }

    public function ithemeland_notice_display($install_url)
    {
        $output = '<style>
            .italic {
                font-style: italic;
            }
            .wgb-required-alert{
                width:100%;
                background:#fff;
                display:inline-table;
                margin-top:10px;
                padding:20px;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                text-align:left;
            }
            .wgb-required-alert-icon{
                width:12%;
                float:left;
                border-right: 2px #dbdbdb solid;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                text-align:center;
                padding:15px 0;
            }
            .wgb-required-alert .wgb-required-alert-right{
                width:85%;
                float:left;
                margin-left:20px;
            }
            .wgb-required-alert .wgb-required-alert-text{
                width:100%;
                display:inline-table;
                margin: 0 0 20px 0;
                font-size:13px;
                line-height:23px;
                float:left;
            }
            .wgb-required-alert .wgb-required-alert-text .title{
                font-size:17px;
                line-height:30px;
            }
            .wgb-required-install,
            .wgb-required-read-more{
                height:42px;
                padding:0 30px;
                text-decoration:none;
                border-radius:4px;
                -moz-border-radius:4px;
                -webkit-border-radius:4px;
                display:inline-table;
                font-size:12pt;
                line-height:42px;
                cursor:pointer;
            }

            .wgb-required-install{
                border:0;
                background:#11db6d;
                color:#fff;
            }

            .wgb-required-install:hover,
            .wgb-required-install:focus{
                color:#fff;
            }
            
            .wgb-required-read-more{
                background:#f3f2f0;
                border:1px #e4e3e1 solid;
                color:#3e3e3e;
            }

            .wgb-required-read-more:hover,
            .wgb-required-read-more:focus{
                color:#3e3e3e;
            }

            .wgb-ml5{
                margin-left:5px;
            }
        </style>';
        $output .= '<form action="' . esc_url(admin_url('admin-post.php')) . '" method="post">';
        $output .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('wgb_post_nonce') . '">';
        $output .= '<input type="hidden" name="action" value="wgb_activate_core_plugin">';
        $output .= '<div class="wrap"><div class="wgb-required-alert">';
        $output .= '<div class="wgb-required-alert-icon">';
        $output .= '<img height="110px" viewBox="0 0 512 512" width="110px" style="fill:#3e3e3e" src="' . esc_url(WGBL_IMAGES_URL) . 'wgb_icon_original_black.svg">';
        $output .= '</div>';
        $output .= '<div class="wgb-required-alert-right">';
        $output .= '<div class="wgb-required-alert-text"><strong class="title">"iThemeland Free Gifts For Woo Lite" is not Installed/Activated.</strong><br>';
        $output .= '<span class="italic"><strong>"iThemeland Free Gifts For Woo"</strong> is an addon to the Free version. The<strong>"iThemeland Free Gifts For WooCommerce"</strong> plugin cannot function without the Free plugin. <br>so you need to download / activate the original plugin. (That`s a free plugin!)</span>';
        $output .= '</div><div class="wgb-required-alert-buttons">';
        if (file_exists(WP_PLUGIN_DIR . '/' . WGBL_LITE_PLUGIN)) {
            $output .= '<button type="submit" name="activation_core" value="1" class="wgb-required-install">Activate</button>';
        } else {
            $output .= '<a href="' . esc_url($install_url) . '" class="wgb-required-install">Install Plugin</a>';
        }
        $output .= '</div></div></div></div></form>';
        echo wp_kses($output, Sanitizer::allowed_html());
    }

    public function activate_core_plugin()
    {
        if (isset($_POST['activation_core']) && file_exists(WP_PLUGIN_DIR . '/' . WGBL_LITE_PLUGIN)) { //phpcs:ignore
            activate_plugin(WGBL_LITE_PLUGIN);
            wp_redirect(WGBL_MAIN_PAGE);
            exit;
        }
    }
}
