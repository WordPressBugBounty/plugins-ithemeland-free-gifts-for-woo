<?php

namespace wgb\frontend\blocks\notice;

use wgb\classes\helpers\Notice;

class WGBL_Block_Notice
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            new self();
        }
    }

    private function __construct()
    {
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_script']);
        add_action('init', [$this, 'register_callback']);
    }

    public function enqueue_script()
    {
        wp_enqueue_style('wgb-blocks-notice', WGBL_FRONTEND_URL . 'blocks/notice/editor-style.css', [], WGBL_VERSION);
        wp_enqueue_script('wgb-blocks-notice', WGBL_FRONTEND_URL . 'blocks/notice/notice-block.js', ['wp-blocks', 'wp-element', 'wp-i18n'], WGBL_VERSION); //phpcs:ignore
    }

    public function register_callback()
    {
        wp_register_style('wgb-blocks-front-notice', WGBL_FRONTEND_URL . 'blocks/notice/front-style.css', [], WGBL_VERSION);
        wp_register_script('wgb-blocks-front-notice', WGBL_FRONTEND_URL . 'blocks/notice/front-js.js', [], WGBL_VERSION); //phpcs:ignore

        register_block_type('wgb/notice', [
            'render_callback' => [$this, 'callback'],
            'script' => 'wgb-blocks-front-notice',
            'style' => 'wgb-blocks-front-notice',

            'attributes' => [
                'notice_message' => [
                    'type' => 'string',
                    'default' => 'Based on your Current Cart Contents, you are eligible for Free Gift(s). Choose your gift [popup_link] [cart_link]',
                ],
                'button_label' => [
                    'type' => 'string',
                    'default' => 'Here',
                ],
            ],
        ]);
    }

    public function callback($attributes)
    {
        if (is_admin() || wp_doing_ajax() || empty(WC()->session)) {
            return;
        }

        if (!isset($attributes['notice_message']) || !(isset($attributes['button_label']))) {
            return;
        }

        $notice = '<span class="itg-checkout-notice"></span>' . esc_html($attributes['notice_message']);
        $popup_link = sprintf('<a class="btn-select-gift-popup-button" href="">%s</a>', esc_html($attributes['button_label']));
        $notice = str_replace('[popup_link]', $popup_link, $notice);
        $cart_page_url = sprintf('<a href="%s">%s</a>', wc_get_cart_url(), esc_html($attributes['button_label']));
        $notice = str_replace('[cart_link]', $cart_page_url, $notice);

        ob_start();
        Notice::print($notice, 'notice');
        return ob_get_clean();
    }
}
