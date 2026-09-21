<?php

defined('ABSPATH') || exit;

/** Conditionally loads frontend assets for UI that the Free edition can render. */
class iThemeland_enqueue_css_js
{
    private static $settings = [];
    private static $loaded = [];

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'maybe_enqueue_assets'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enforce_asset_policy'], 999);
    }

    public function maybe_enqueue_assets()
    {
        if (is_admin() || wp_doing_ajax()) return;
        self::$settings = itfreegift_get_settings();
        if (!self::has_candidate_gift_context()) return;

        foreach ($this->required_layouts() as $layout => $popup) {
            self::enqueue_layout($layout, $popup);
        }
    }

    public function enforce_asset_policy()
    {
        if (is_admin() || wp_doing_ajax()) return;
        self::$settings = itfreegift_get_settings();
        if (self::has_candidate_gift_context() && $this->required_layouts()) return;

        foreach (['it-gift-style', 'it-gift-popup', 'it-gift-owl-carousel-style', 'it-gift-datatables-style', 'it-gift-dropdown-css'] as $handle) {
            $this->dequeue_owned($handle, 'style');
        }
        foreach (['pw-gift-add-jquery-adv', 'pw-gift-scrollbar-js', 'owl-carousel', 'it-gift-owl-carousel-jquery', 'it-gift-datatables-js', 'it-gift-dropdown-js', 'it-gift-grid-jquery', 'cart-pagination'] as $handle) {
            $this->dequeue_owned($handle, 'script');
        }
    }

    private function dequeue_owned($handle, $type)
    {
        $registry = $type === 'style' ? wp_styles() : wp_scripts();
        if (empty($registry->registered[$handle])) return;
        if (strpos((string) $registry->registered[$handle]->src, plugin_dir_url_wc_advanced_gift) !== 0) return;
        $type === 'style' ? wp_dequeue_style($handle) : wp_dequeue_script($handle);
    }

    public static function has_candidate_gift_context()
    {
        if (!function_exists('WC') || !WC()->cart || WC()->cart->get_cart_contents_count() < 1) return false;
        $rules = \ITFreeGift\classes\repositories\Rule::get_instance()->get();
        if (empty($rules['items']) || !is_array($rules['items'])) return false;
        foreach ($rules['items'] as $rule) {
            if (($rule['status'] ?? 'disable') !== 'disable') return true;
        }
        return false;
    }

    private function required_layouts()
    {
        $settings = self::$settings;
        $layouts = [];

        if (is_cart()) {
            if (!empty($settings['position']) && $settings['position'] !== 'none') {
                $layouts[$this->layout($settings['layout'] ?? 'carousel')] = false;
            }
        }

        // Popup assets are enqueued by layout_popup() only when rule evaluation
        // has produced gift UI. This keeps checkout pages with no eligible gift
        // free of popup, carousel, and scrollbar assets.

        foreach ($this->shortcode_layouts() as $layout) $layouts[$layout] = false;
        foreach ($this->block_layouts() as $layout) $layouts[$layout] = false;
        return apply_filters('itfreegift_required_frontend_asset_layouts', $layouts, $settings);
    }

    private function shortcode_layouts()
    {
        global $post;
        if (!is_singular() || is_shop() || !$post instanceof WP_Post || !has_shortcode($post->post_content, 'itg_gift_products')) return [];
        $layouts = [];
        if (preg_match_all('/' . get_shortcode_regex(['itg_gift_products']) . '/s', $post->post_content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $atts = shortcode_parse_atts($match[3]);
                $layouts[] = $this->layout($atts['type'] ?? 'dropdown');
            }
        }
        return array_unique($layouts ?: ['dropdown']);
    }

    private function block_layouts()
    {
        if (!function_exists('has_block')) return [];
        $layouts = [];
        foreach (['wgb/gift-carousel' => 'carousel', 'wgb/gift-grid' => 'grid', 'wgb/gift-datatable' => 'datatable'] as $block => $layout) {
            if (has_block($block)) $layouts[] = $layout;
        }
        if (has_block('wgb/wc-gift')) $layouts[] = $this->layout(self::$settings['layout'] ?? 'carousel');
        return $layouts;
    }

    private function layout($layout)
    {
        return in_array($layout, ['carousel', 'grid', 'datatable', 'dropdown', 'list'], true) ? $layout : 'grid';
    }

    public static function enqueue_layout($layout, $popup = false)
    {
        self::enqueue_core();
        if ($popup) self::enqueue_popup();
        if ($layout === 'carousel') self::enqueue_carousel();
        elseif ($layout === 'datatable') self::enqueue_datatable();
        elseif ($layout === 'dropdown') self::enqueue_dropdown();
        elseif ($layout === 'grid') self::enqueue_grid();
        elseif ($layout === 'list') self::enqueue_list();
    }

    public static function enqueue_core()
    {
        if (!empty(self::$loaded['core'])) return;
        self::$loaded['core'] = true;
        self::settings();
        wp_enqueue_style('it-gift-style', plugin_dir_url_wc_advanced_gift . 'assets/css/style/style.css', [], WGBL_VERSION . '-unavailable-gifts-1');
        wp_enqueue_script('pw-gift-add-jquery-adv', plugin_dir_url_wc_advanced_gift . 'assets/js/custom-jquery-gift.js', ['jquery'], WGBL_VERSION . '-conditional-assets-2-unavailable-gifts-1', true);

        $s = self::$settings;
        $carousel = $s['carousel'] ?? [];
        $permalink = apply_filters('itfreegift_redirect_after_click_gift_item', get_permalink());
        $select = itfreegift_get_localization('select_gift', 'Select Gift');
        wp_localize_script('pw-gift-add-jquery-adv', 'pw_wc_gift_adv_ajax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'add_to_cart_link' => esc_url(add_query_arg(['pw_add_gift' => '%s', 'qty' => '%q'], $permalink)),
            'security' => wp_create_nonce('jkhKJSdd4576d234Z'),
            'action_show_variation' => 'handel_pw_gift_show_variation',
            'action_display_gifts_in_popup' => 'handel_display_gifts_in_popup',
            'action_gift_show_popup_checkout' => 'handel_pw_gift_show_popup_checkout',
            'show_quantity' => $s['enabled_qty'] ?? 'false',
            'language_info' => sprintf('%s _PAGE_ %s _PAGES_', esc_html__('Showing page', 'ithemeland-free-gifts-for-woo'), esc_html__('of', 'ithemeland-free-gifts-for-woo')),
            'language_search' => esc_html__('search', 'ithemeland-free-gifts-for-woo'),
            'language_first' => esc_html__('first', 'ithemeland-free-gifts-for-woo'),
            'language_previous' => esc_html__('previous', 'ithemeland-free-gifts-for-woo'),
            'language_next' => esc_html__('next', 'ithemeland-free-gifts-for-woo'),
            'language_last' => esc_html__('last', 'ithemeland-free-gifts-for-woo'),
            'language_select_gift' => $select,
            'language_select_your_gift' => $select,
            'cart_auto_load' => $s['cart_auto_load'] ?? 'false',
            'checkout_auto_load' => $s['checkout_auto_load'] ?? 'false',
            'add_gift_ajax_manual' => $s['enable_ajax_add_to_cart'] ?? 'false',
            'is_checkout' => itfreegift_is_checkout_page(),
            'is_block_cart' => itfreegift_is_block_cart(),
            'is_block_checkout' => itfreegift_is_block_checkout(),
            'loop' => $carousel['loop'] ?? 'false', 'rtl' => $carousel['rtl'] ?? 'false',
            'dots' => $carousel['dots'] ?? 'false', 'nav' => $carousel['nav'] ?? 'false',
            'speed' => $carousel['speed'] ?? 5000, 'mobile' => $carousel['mobile'] ?? 1,
            'tablet' => $carousel['tablet'] ?? 3, 'desktop' => $carousel['desktop'] ?? 4,
        ]);
    }

    public static function enqueue_popup()
    {
        if (!empty(self::$loaded['popup'])) return;
        self::$loaded['popup'] = true;
        wp_enqueue_style('it-gift-popup', plugin_dir_url_wc_advanced_gift . 'assets/css/popup/popup.css', ['it-gift-style'], WGBL_VERSION);
        wp_enqueue_script('pw-gift-scrollbar-js', plugin_dir_url_wc_advanced_gift . 'assets/js/scrollbar/jquery.scrollbar.min.js', ['jquery'], WGBL_VERSION, true);
    }

    public static function enqueue_carousel()
    {
        if (!empty(self::$loaded['carousel'])) return;
        self::$loaded['carousel'] = true;
        wp_enqueue_style('it-gift-owl-carousel-style', plugin_dir_url_wc_advanced_gift . 'assets/css/owl-carousel/owl.carousel.min.css', [], WGBL_VERSION);
        wp_enqueue_script('owl-carousel', plugin_dir_url_wc_advanced_gift . 'assets/js/owl-carousel/owl.carousel.min.js', ['jquery'], WGBL_VERSION, true);
    }

    public static function enqueue_datatable()
    {
        if (!empty(self::$loaded['datatable'])) return;
        self::$loaded['datatable'] = true;
        wp_enqueue_style('it-gift-datatables-style', plugin_dir_url_wc_advanced_gift . 'assets/css/datatables/jquery.dataTables.min.css', [], WGBL_VERSION);
        wp_enqueue_script('it-gift-datatables-js', plugin_dir_url_wc_advanced_gift . 'assets/js/datatables/jquery.dataTables.min.js', ['jquery'], WGBL_VERSION, true);
    }

    public static function enqueue_dropdown()
    {
        if (!empty(self::$loaded['dropdown'])) return;
        self::$loaded['dropdown'] = true;
        wp_enqueue_style('it-gift-dropdown-css', plugin_dir_url_wc_advanced_gift . 'assets/css/dropdown/dropdown.css', [], WGBL_VERSION);
        wp_enqueue_script('it-gift-dropdown-js', plugin_dir_url_wc_advanced_gift . 'assets/js/dropdown/dropdown.js', ['jquery'], WGBL_VERSION, true);
    }

    public static function enqueue_grid()
    {
        if (!empty(self::$loaded['grid'])) return;
        self::$loaded['grid'] = true;
        wp_enqueue_script('it-gift-grid-jquery', plugin_dir_url_wc_advanced_gift . 'assets/js/grid/grid.js', ['jquery'], WGBL_VERSION, true);
    }

    public static function enqueue_list()
    {
        // List is a popup-native layout. Core, popup CSS, and scrollbar are
        // enqueued by enqueue_layout(..., true); it has no layout-specific file.
        self::$loaded['list'] = true;
    }

    private static function settings()
    {
        if (empty(self::$settings)) self::$settings = itfreegift_get_settings();
    }
}

new iThemeland_enqueue_css_js();
