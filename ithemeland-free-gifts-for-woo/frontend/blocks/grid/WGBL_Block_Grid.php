<?php

namespace wgb\frontend\blocks\grid;

class WGBL_Block_Grid
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
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_script']);
        add_action('init', [$this, 'register_callback']);
    }

    public function enqueue_script()
    {
        wp_enqueue_style('wgb-blocks-grid', WGBL_FRONTEND_URL . 'blocks/grid/editor-style.css', [], WGBL_VERSION);
        wp_enqueue_script('wgb-blocks-grid', WGBL_FRONTEND_URL . 'blocks/grid/grid-block.js', ['wp-blocks', 'wp-element', 'wp-i18n'], WGBL_VERSION); //phpcs:ignore
        
        wp_register_style('wgb-blocks-front-grid', WGBL_FRONTEND_URL . 'blocks/grid/front-style.css', [], WGBL_VERSION);
        wp_register_script('wgb-blocks-front-grid', WGBL_FRONTEND_URL . 'blocks/grid/front-js.js', [], WGBL_VERSION); //phpcs:ignore
        
        
        wp_localize_script('wgb-blocks-grid', 'WGBL_GRID_DATA', [
            'images' => [
                'wc_placeholder' => WGBL_IMAGES_URL . 'woocommerce-placeholder.png'
            ]
        ]);
    }

    public function register_callback()
    {
        register_block_type('wgb/gift-grid', [
            'render_callback' => [$this, 'callback'],
            'script' => 'wgb-blocks-front-grid',
            'style' => 'wgb-blocks-front-grid',
        ]);
    }

    public function callback()
    {
        if (is_admin() || wp_doing_ajax() || empty(WC()->session)) {
            return;
        }
        return do_shortcode('[itg_gift_products type="grid"]');
    }
}
