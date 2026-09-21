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
                iThemeland_enqueue_css_js::enqueue_layout('datatable');

                $itfreegift_template_file = 'datatable-layout.php';
                break;

            case 'grid':
                iThemeland_enqueue_css_js::enqueue_layout('grid');

                $itfreegift_template_file = 'grid-layout.php';
                break;

            case 'carousel':
                iThemeland_enqueue_css_js::enqueue_layout('carousel');

                $itfreegift_template_file = 'carousel-layout.php';
                break;

            case 'dropdown':
                iThemeland_enqueue_css_js::enqueue_layout('dropdown');

                $itfreegift_template_file = 'dropdown-layout.php';
                break;
        }

        itfreegift_get_template($itfreegift_template_file, $data_args);
    } else {
        echo wp_kses_post(itfreegift_get_localization('free_gift_empty_message', false));
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
