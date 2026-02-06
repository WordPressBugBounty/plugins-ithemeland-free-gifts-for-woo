<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}
class iThemeland_enqueue_css_js
{
    private $settings;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, "woo_advanced_gift_js_css"));
    }


    public function woo_advanced_gift_js_css()
    {
        $this->settings = itg_get_settings();

        if (is_cart() || is_checkout() || is_singular() || is_shop()) {
            wp_register_style('it-gift-owl-carousel-style', plugin_dir_url_wc_advanced_gift . 'assets/css/owl-carousel/owl.carousel.min.css', [], WGBL_VERSION);
            wp_register_script('owl-carousel', plugin_dir_url_wc_advanced_gift . 'assets/js/owl-carousel/owl.carousel.min.js', [], WGBL_VERSION); //phpcs:ignore

            wp_enqueue_style('it-gift-owl-carousel-style');
            wp_enqueue_script('it-gift-owl-carousel-jquery');

            //Register dropdown
            //Register datatable
            wp_register_style('it-gift-datatables-style', plugin_dir_url_wc_advanced_gift . 'assets/css/datatables/jquery.dataTables.min.css', [], WGBL_VERSION);
            wp_register_script('it-gift-datatables-js', plugin_dir_url_wc_advanced_gift . 'assets/js/datatables/jquery.dataTables.min.js', array('jquery'), WGBL_VERSION); //phpcs:ignore
            //wp_register_script('it-gift-customjs-datatable', plugin_dir_url_wc_advanced_gift . 'assets/js/datatables/custom-js.js', [], WGBL_VERSION);		

            wp_enqueue_script('cart-pagination', plugin_dir_url_wc_advanced_gift . 'assets/js/pagination.js', array('jquery'), WGBL_VERSION, true);
            $per_page = !empty($this->settings['promotion']['per_page']) ? intval($this->settings['promotion']['per_page']) : 5;
            wp_localize_script(
                'cart-pagination', // Handle of the script you're localizing
                'cart_ajax', // Name of the JavaScript object
                array(
                    'ajax_url' => admin_url('admin-ajax.php'), // URL for AJAX requests
                    'freeTxt' => get_option('itg_localization_free', 'Free'),
                    'per_page' => $per_page
                )
            );
            //Carousel
            /*if ($this->settings['layout'] == 'carousel') {
			wp_enqueue_style('it-gift-owl-carousel-style');
			wp_enqueue_script('it-gift-owl-carousel-jquery');				
            }
            //DropDown
            else*/

            if ($this->settings['layout'] == 'dropdown') {
                wp_enqueue_style('it-gift-dropdown-css', plugin_dir_url_wc_advanced_gift . 'assets/css/dropdown/dropdown.css', [], WGBL_VERSION);
                wp_enqueue_script('it-gift-dropdown-js', plugin_dir_url_wc_advanced_gift . 'assets/js/dropdown/dropdown.js', [], WGBL_VERSION, true); //phpcs:ignore
            }

            //DataTase
            /*elseif ($this->settings['layout'] == 'datatable') {
			wp_enqueue_style('it-gift-datatables-style');
			wp_enqueue_script('it-gift-datatables-js');				
			wp_enqueue_script('it-gift-customjs-datatable');
            }*/
            //$permalink = get_permalink();

            $permalink = apply_filters('itg_redirect_after_click_gift_item',  get_permalink());
            $add_to_cart_link = esc_url(add_query_arg(array('pw_add_gift' => '%s', 'qty' => '%q'), $permalink));

            $select_gift = get_option('itg_localization_select_gift', 'Select Gift');

            wp_register_script('pw-gift-add-jquery-adv', plugin_dir_url_wc_advanced_gift . 'assets/js/custom-jquery-gift.js', array('jquery', 'owl-carousel', 'react', 'wc-blocks-checkout', 'wc-settings', 'wp-data', 'wp-element'), WGBL_VERSION, true); //phpcs:ignore
            wp_localize_script('pw-gift-add-jquery-adv', 'pw_wc_gift_adv_ajax', array(
                'ajaxurl'                           => admin_url('admin-ajax.php'),
                'add_to_cart_link'                  => $add_to_cart_link,
                'security'                          => wp_create_nonce('jkhKJSdd4576d234Z'),
                'action_show_variation'             => 'handel_pw_gift_show_variation',
                'action_display_gifts_in_popup'     => 'handel_display_gifts_in_popup',
                'action_gift_show_popup_checkout'     => 'handel_pw_gift_show_popup_checkout',
                'show_quantity'                     => $this->settings['enabled_qty'],
                'language_info'                        => sprintf('%s _PAGE_ %s _PAGES_', esc_html__('Showing page', 'ithemeland-free-gifts-for-woo'), esc_html__('of', 'ithemeland-free-gifts-for-woo')),
                'language_search'                    => esc_html__('search', 'ithemeland-free-gifts-for-woo'),
                'language_first'                    => esc_html__('first', 'ithemeland-free-gifts-for-woo'),
                'language_previous'                    => esc_html__('previous', 'ithemeland-free-gifts-for-woo'),
                'language_next'                        => esc_html__('next', 'ithemeland-free-gifts-for-woo'),
                'language_last'                        => esc_html__('last', 'ithemeland-free-gifts-for-woo'),
                'language_select_gift'                => $select_gift,
                'language_select_your_gift'                => $select_gift,
                'add_gift_ajax_manual'                => $this->settings['enable_ajax_add_to_cart'],
                'is_block_cart' => wgb_is_block_cart(),
                'is_block_checkout' => wgb_is_block_checkout(),
                'loop' => $this->settings['carousel']['loop'],
                'rtl' => $this->settings['carousel']['rtl'],
                'dots' => $this->settings['carousel']['dots'],
                'nav' => $this->settings['carousel']['nav'],
                'speed' => $this->settings['carousel']['speed'],
                'mobile' => $this->settings['carousel']['mobile'],
                'tablet' => $this->settings['carousel']['tablet'],
                'desktop' => $this->settings['carousel']['desktop'],
            ));
            wp_enqueue_script('pw-gift-add-jquery-adv');

            // Debug script for development
            // if (defined('WP_DEBUG') && WP_DEBUG) {
            //     wp_enqueue_script('pw-gift-debug', plugin_dir_url_wc_advanced_gift . 'assets/js/debug-ajax.js', array('jquery'), '1.0.0', true);
            // }

            //Css
            //wp_enqueue_style('it-gift-modal-style', plugin_dir_url_wc_advanced_gift . 'assets/css/modal/modal.css');

            //wp_register_style( 'it-gift-popup-style', plugin_dir_url_wc_advanced_gift . 'assets/css/popup/popup.css' );

            //Grid
            wp_enqueue_style('it-gift-style', plugin_dir_url_wc_advanced_gift . 'assets/css/style/style.css', [], WGBL_VERSION);
            wp_enqueue_style('it-gift-popup', plugin_dir_url_wc_advanced_gift . 'assets/css/popup/popup.css', [], WGBL_VERSION);

            wp_register_script('it-gift-grid-jquery', plugin_dir_url_wc_advanced_gift . 'assets/js/grid/grid.js', [], WGBL_VERSION); //phpcs:ignore

            //Scrollbar
            wp_enqueue_script('pw-gift-scrollbar-js', plugin_dir_url_wc_advanced_gift . 'assets/js/scrollbar/jquery.scrollbar.min.js', [], WGBL_VERSION); //phpcs:ignore
        }
    }
}

new iThemeland_enqueue_css_js();
