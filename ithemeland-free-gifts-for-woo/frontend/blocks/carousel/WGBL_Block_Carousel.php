<?php

namespace wgb\frontend\blocks\carousel;

class WGBL_Block_Carousel
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
        wp_enqueue_style('wgb-blocks-carousel', WGBL_FRONTEND_URL . 'blocks/carousel/editor-style.css', [], WGBL_VERSION);
        wp_enqueue_script('wgb-blocks-carousel', WGBL_FRONTEND_URL . 'blocks/carousel/carousel-block.js', ['wp-blocks', 'wp-element', 'wp-i18n'], WGBL_VERSION); //phpcs:ignore
        wp_localize_script('wgb-blocks-carousel', 'WGBL_CAROUSEL_DATA', [
            'images' => [
                'wc_placeholder' => WGBL_IMAGES_URL . 'woocommerce-placeholder.png'
            ]
        ]);
    }

    public function register_callback()
    {
        wp_register_style('wgb-blocks-front-carousel', WGBL_FRONTEND_URL . 'blocks/carousel/front-style.css', [], WGBL_VERSION);
        wp_register_script('wgb-blocks-front-carousel', WGBL_FRONTEND_URL . 'blocks/carousel/front-js.js', [], WGBL_VERSION); //phpcs:ignore

        register_block_type('wgb/gift-carousel', [
            'render_callback' => [$this, 'callback'],
            'script' => 'wgb-blocks-front-carousel',
            'style' => 'wgb-blocks-front-carousel',

            'attributes' => [
                'speed' => [
                    'type' => 'number',
                    'default' => 5000,
                ],
                'mobile' => [
                    'type' => 'number',
                    'default' => 1,
                ],
                'tablet' => [
                    'type' => 'number',
                    'default' => 3,
                ],
                'desktop' => [
                    'type' => 'number',
                    'default' => 5,
                ],
                'loop' => [
                    'type' => 'boolean',
                    'default' => false,
                ],
                'show_dots' => [
                    'type' => 'boolean',
                    'default' => false,
                ],
                'show_nav' => [
                    'type' => 'boolean',
                    'default' => false,
                ],
                'right_to_left' => [
                    'type' => 'boolean',
                    'default' => false,
                ],
            ],
        ]);
    }

    public function callback()
    {

        if (is_admin() || wp_doing_ajax() || empty(WC()->session)) {
            return;
        }

        //return do_shortcode('[itg_gift_products type="carousel" speed="' . intval($attributes['speed']) . '" desktop="' . intval($attributes['desktop']) . '" mobile="' . intval($attributes['mobile']) . '" tablet="' . intval($attributes['tablet']) . '" rtl="' . ($attributes['right_to_left'] === true ? 'true' : 'false') . '" dots="' . ($attributes['show_dots'] === true ? 'true' : 'false') . '" nav="' . ($attributes['show_nav'] === true ? 'true' : 'false') . '" loop="' . ($attributes['loop'] === true ? 'true' : 'false') . '"]');
        return do_shortcode('[itg_gift_products type="carousel"]');
    }
}
