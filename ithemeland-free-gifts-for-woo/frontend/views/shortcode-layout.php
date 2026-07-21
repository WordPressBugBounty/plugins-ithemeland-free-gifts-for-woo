<?php

/**
 * This template displays contents inside shortcode layout
 *
 * This template can be overridden by copying it to yourtheme/ithemeland-free-gifts-for-woo/shortcode-layout.php
 *
 * To maintain compatibility, Free Gifts for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<div class="itg_shortcode_gift_products_wrapper">
    <?php
    /**
     * This hook is used to display the extra content before gift products content.
     * 
     * @since 2.0.0
     */
    do_action('itfreegift_before_shortcode_gift_products_content');

    if ($data_args) {
        switch ($template) {
            case 'datatable':
                wp_enqueue_style('it-gift-datatables-style');
                wp_enqueue_script('it-gift-datatables-js');

                $itfreegift_template_file = 'datatable-layout.php';
                break;

            case 'grid':
                wp_enqueue_script('it-gift-grid-jquery');

                $itfreegift_template_file = 'grid-layout.php';
                break;

            case 'carousel':

                wp_enqueue_style('it-gift-owl-carousel-style');
                wp_enqueue_script('it-gift-owl-carousel-jquery');

                $itfreegift_template_file = 'carousel-layout.php';
                break;

            case 'dropdown':
                wp_enqueue_style('it-gift-dropdown-css');
                wp_enqueue_script('it-gift-dropdown-js');

                $itfreegift_template_file = 'dropdown-layout.php';
                break;
        }

        itfreegift_get_template($itfreegift_template_file, $data_args);
    } else {
        echo wp_kses_post(get_option('itg_localization_free_gift_empty_message'));
    }

    /**
     * This hook is used to display the extra content after gift products content.
     * 
     * @since 2.0.0
     */
    do_action('itfreegift_after_shortcode_gift_products_content');
    ?>
</div>
<?php
