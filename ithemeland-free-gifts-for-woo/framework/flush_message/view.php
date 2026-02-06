<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="wgb-alert <?php echo (!empty($flush_message['color']) && $flush_message['color'] == "green") ? "wgb-alert-success" : "wgb-alert-danger"; ?>">
    <span><?php echo esc_html($flush_message['message']); ?></span>
</div>