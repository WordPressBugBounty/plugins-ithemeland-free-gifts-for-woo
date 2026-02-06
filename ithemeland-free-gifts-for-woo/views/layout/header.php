<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div id="wgb-main">
    <div id="wgb-loading" class="wgb-loading">
        <?php esc_html_e('Loading ...', 'ithemeland-free-gifts-for-woo') ?>
    </div>
    <div id="wgb-header">
        <div class="wgb-plugin-title">
            <span class="wgb-plugin-name"><img src="<?php echo esc_url(WGBL_IMAGES_URL . 'giftit-icon-wh.svg'); ?>" width="24" alt=""><?php echo (!empty($this->page_title)) ? esc_html($this->page_title) : ''; ?></span>
        </div>
        <ul class="wgb-header-left">
            <li title="Help">
                <a href="<?php echo (!empty($this->doc_link)) ? esc_url($this->doc_link) : '#'; ?>">
                    <i class="dashicons dashicons-book"></i>
                </a>
            </li>
            <li id="wgb-full-screen" title="Full screen">
                <i class="dashicons dashicons-fullscreen-alt"></i>
            </li>
            <li class="wgb-upgrade" id="wgb-upgrade" style="width: auto;">
                <a href="<?php echo esc_url(WGBL_UPGRADE_URL); ?>"><?php echo esc_html(WGBL_UPGRADE_TEXT); ?></a>
            </li>
            <li class="wgb-youtube-button" id="wgb-youtube-button" style="width: auto;">
                <a target="_blank" href="<?php echo esc_url("https://www.youtube.com/playlist?list=PLo0x1Hax3FuvhwPqSHJQWXT4DqLeqyOCu"); ?>"><?php esc_html_e('Watch Pro version', 'ithemeland-free-gifts-for-woo'); ?></a>
            </li>
        </ul>
    </div>