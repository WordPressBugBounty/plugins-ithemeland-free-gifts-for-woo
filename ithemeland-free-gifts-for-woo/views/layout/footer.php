<?php

use wgb\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div id="wgb-elements">
    <?php if (!empty($default_product_row)) : ?>
        <div id="wgb-default-product-row">
            <?php echo wp_kses($default_product_row, Sanitizer::allowed_html()); ?>
        </div>
    <?php endif; ?>
</div>
</div>