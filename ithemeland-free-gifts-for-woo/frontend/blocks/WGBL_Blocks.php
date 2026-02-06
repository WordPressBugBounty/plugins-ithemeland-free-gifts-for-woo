<?php

namespace wgb\frontend\blocks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use wgb\frontend\blocks\carousel\WGBL_Block_Carousel;
use wgb\frontend\blocks\datatable\WGBL_Block_Datatable;
use wgb\frontend\blocks\grid\WGBL_Block_Grid;
use wgb\frontend\blocks\notice\WGBL_Block_Notice;

class WGBL_Blocks
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        add_action('woocommerce_blocks_loaded', [$this, 'register'], 10);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_script']);
        $this->register_blocks();
        // Hook to enqueue_block_editor_assets which only runs in the editor
        add_action('enqueue_block_editor_assets', [$this, 'append_blocks_to_cart']);


        add_action('wp_enqueue_scripts', [$this, 'enqueue_cart_scripts']);

        add_action('wp_ajax_update_block_cart_content', [$this, 'update_block_cart_content']);
    }

    public function register()
    {
        WGBL_Blocks_Store_API::init();
    }

    private function register_blocks()
    {
        // WGBL_Block_Notice::register();
        WGBL_Block_Carousel::register();
        WGBL_Block_Grid::register();
        WGBL_Block_Datatable::register();

        // Register the gift grid block with render callback
        $this->register_inlineblock();
    }
    public function enqueue_script()
    {
        // Enqueue common block editor styles
        wp_enqueue_style('wgb-blocks-common-style', WGBL_FRONTEND_URL . 'blocks/assets/css/common-style.css', [], WGBL_VERSION);
    }

    public function enqueue_cart_scripts()
    {
        if (!is_cart()) {
            return;
        }

        // Enqueue jQuery first
        // wp_enqueue_script('jquery');

        // Then enqueue our script with jQuery dependency
        wp_enqueue_script(
            'wgb-cart-updates',
            WGBL_FRONTEND_URL . 'blocks/assets/js/cart-updates.js',
            ['jquery'],
            WGBL_VERSION,
            true
        );

        wp_localize_script('wgb-cart-updates', 'wgb_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('update_block_cart_content')
        ]);
    }

    public function append_blocks_to_cart()
    {
        // Ensure this code only runs in the block editor
        if (!is_admin() || !function_exists('get_current_screen') || !get_current_screen() || !get_current_screen()->is_block_editor()) {
            return;
        }

        list($block_name, $layout) = self::get_layout_from_setting('', '');

        if (!$block_name || !$layout) {
            return;
        }

        // Enqueue our script that will handle block appending
        wp_enqueue_script('wgb-append-blocks', WGBL_FRONTEND_URL . 'blocks/assets/js/append-blocks.js', ['wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-hooks', 'wp-i18n', 'wp-block-editor'], WGBL_VERSION, true);

        $block_html = $this->get_free_gifts_preview_html();

        // Pass the layout setting and preview HTML to our script for use in the editor
        wp_localize_script('wgb-append-blocks', 'WGBL_APPEND_DATA', [
            'layout'  => $layout,
            'blockName' => $block_name,
            'blockHtmlLayout' => $block_html
        ]);
    }

    private static function get_layout_from_setting($register_block_name, $register_layout)
    {
        // Get the layout setting
        $settings = get_option('wgb_settings', []);
        $layout = isset($settings['layout']) ? $settings['layout'] : '';
        $position = isset($settings['position']) ? $settings['position'] : '';

        if ($position === 'bottom_cart') {


            if (empty($layout)) {
                return [null, null];
            }

            // Since all layouts use the same block name, we can simplify this
            $block_name = 'wgb/wc-gift';

            // Validate that the layout is one of our supported types
            if (!in_array($layout, ['carousel', 'grid', 'datatable'])) {
                return [null, null];
            }

            return [$block_name, $layout];
        }
        return false;
    }

    /**
     * Register automatic block in inlineblock of cart block.
     * 
     * @since 11.0.0
     * @return RegisterBlock 
     */
    public function register_inlineblock()
    {
        list($block_name, $layout) = self::get_layout_from_setting('', '');


        if (!$block_name || !$layout) {
            return;
        }
        register_block_type($block_name, [
            'attributes' => [
                'content' => [
                    'type' => 'string',
                    'default' => "[itg_gift_products type=\"$layout\"]"
                ]
            ],
            'render_callback' => function ($attributes, $content) use ($layout) {
                // Execute the shortcode to render the block in the frontend
                $shortcode = isset($attributes['content']) ? $attributes['content'] : "[itg_gift_products type=\"$layout\"]";

                // Only execute shortcode on the frontend, not in admin, AJAX, cron, etc.
                if (!is_admin() && (!function_exists('wp_doing_ajax') || !wp_doing_ajax()) && (!function_exists('wp_doing_cron') || !wp_doing_cron()) && !is_feed()) {
                    return "<div class='wp-block-wgb-wc-gift'>" . do_shortcode($shortcode) . '</div>';
                }

                // In the editor or other contexts, return nothing or a placeholder handled by JS
                return '';
            }
        ]);
    }


    /**
     * Get the free gifts preview HTML.
     * 
     * @since 11.0.0
     * @return HTML
     */
    private function get_free_gifts_preview_html()
    {

        list($block_name, $layout) = self::get_layout_from_setting('', '');

        if (!$block_name || !$layout) {
            return '';
        }

        // Ensure this code only runs in the block editor
        if (!is_admin() || !function_exists('get_current_screen') || !get_current_screen() || !get_current_screen()->is_block_editor()) {
            return '';
        }

        // Start output buffering
        ob_start();

        // Include the grid layout template
        $template_path = WGBL_FRONTEND_DIR . "blocks/" . $layout . "/" . $layout . "-template.php";

        if (file_exists($template_path)) {
            include_once $template_path;
        } else {
            // Fallback or error handling if template not found
            echo '<p>Error: Block preview template not found.</p>';
        }

        $contents = ob_get_contents();
        ob_end_clean(); // Clean the output buffer

        return $contents;
    }


    public function update_block_cart_content()
    {
        check_ajax_referer('update_block_cart_content', 'security');

        $settings = get_option('wgb_settings', []);
        $layout = $settings['layout'] ?? 'grid';

        $shortcode = "[itg_gift_products type=\"$layout\"]";
        $html = do_shortcode($shortcode);

        wp_send_json_success(['html' => $html]);
    }

    /**
     * Update block content dynamically based on current settings
     */
    public function update_block_content($block_content, $block)
    {
        // Check if block and blockName exist and if this is our gift block
        if (isset($block['blockName']) && is_string($block['blockName']) && strpos($block['blockName'], 'wgb/wc-gift-') === 0) {
            // Get current settings
            $settings = get_option('wgb_settings', []);
            $current_layout = isset($settings['layout']) ? $settings['layout'] : '';

            if (!empty($current_layout)) {

                if (!is_admin() && (!function_exists('wp_doing_ajax') || !wp_doing_ajax()) && (!function_exists('wp_doing_cron') || !wp_doing_cron()) && !is_feed()) {
                    // Generate new content based on current layout
                    $shortcode = "[itg_gift_products type=\"$current_layout\"]";
                    $new_content = "<div class='wp-block-wgb-wc-gift'>" . do_shortcode($shortcode) . '</div>';
                    // Only update content on frontend
                    return $new_content;
                }
            }
        }

        return $block_content;
    }

    public static function get_active_rules()
    {
        $rules_data = get_option('wgb_rules', array());

        if (empty($rules_data) || !isset($rules_data['items'])) {
            return array();
        }

        // Filter only enabled rules from the items array
        return array_filter($rules_data['items'], function ($rule) {
            return isset($rule['status']) && $rule['status'] === 'enable';
        });
    }
}
