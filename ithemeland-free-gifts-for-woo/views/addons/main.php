<?php

use wgb\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div id="wgb-body">
    <div class="wgb-wrap">
        <div class="wgb-header">
            <h2>
                <img src="<?php echo esc_url(WGBL_IMAGES_URL . "wgb_icon_original_black.svg"); ?>" alt="">
                <?php esc_html_e("Available Add-Ons", 'ithemeland-free-gifts-for-woo'); ?>
            </h2>
            <span class="wgb-header-sub-icon">
                <?php
                $checkmark_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve"><g><path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" /><path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" /></g></svg>';
                echo (!empty($addons) && is_array($addons)) ? wp_kses($checkmark_icon . ' ' . count($addons), Sanitizer::allowed_html()) . ' Add-Ons Available' : '';
                ?>
            </span>
        </div>
    </div>
    <div class="wgb-add-ons-body">
        <div class="wgb-wrap">
            <div class="wgb-boxes">
                <?php if (!empty($addons) && $addons_presenter instanceof wgb\classes\presenters\addons\Addons_Presenter) : ?>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <?php wp_nonce_field('wgb_post_nonce'); ?>
                        <input type="hidden" name="action" value="wgb_addons_requests">
                        <?php foreach ($addons as $addon) : ?>
                            <?php $inactive_box = (!file_exists(WGBL_PLUGINS_DIR . $addon['plugin'])) ? 'inactive-box' : ''; ?>
                            <div class="wgb-box-4 <?php echo esc_attr($inactive_box); ?>">
                                <?php echo wp_kses($addons_presenter->get_addon_status_icon($addon), Sanitizer::allowed_html()); ?>
                                <div class="wgb-box-image">
                                    <img src="<?php echo esc_url($addon['image_link']); ?>" alt="">
                                </div>
                                <div class="wgb-box-name">
                                    <strong><a href="<?php echo (!empty($addon['landing_page'])) ? esc_url($addon['landing_page']) : ''; ?>"><?php echo esc_html($addon['label']) ?></a></strong>
                                </div>
                                <div class="wgb-box-footer">
                                    <div class="wgb-box-footer-left">
                                        <div>
                                            <?php echo wp_kses($addons_presenter->get_status($addon['plugin']), Sanitizer::allowed_html()); ?>
                                        </div>
                                    </div>
                                    <div class="wgb-box-footer-right">
                                        <?php echo wp_kses($addons_presenter->get_action_button($addon), Sanitizer::allowed_html()); ?>
                                    </div>
                                </div>
                                <div class="wgb-box-license">
                                    <input type="text" placeholder="<?php esc_attr_e('Purchase Key ...', 'ithemeland-free-gifts-for-woo'); ?>">
                                    <button type="button" class="wgb-button wgb-button-green">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                                            <g>
                                                <path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" />
                                                <path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" />
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>