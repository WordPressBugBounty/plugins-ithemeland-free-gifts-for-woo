<div id="wgbl-main">
    <div id="wgbl-loading" class="wgbl-loading">
        <?php esc_html_e('Loading ...', 'ithemeland-free-gifts-for-woocommerce-lite') ?>
    </div>
    <div id="wgbl-header">
        <div class="wgbl-plugin-title">
            <div class="wgbl-plugin-name">
                <img src="<?php echo esc_url(WGBL_IMAGES_URL . 'wgbl_icon_original.svg'); ?>" alt="">
                <span><?php echo (!empty($this->page_title)) ? esc_html($this->page_title) : ''; ?></span>
                <strong>Lite</strong>
            </div>
            <span class="wgbl-plugin-description"><?php esc_html_e("Boost your sales with creating the right gift for the right customer.", 'ithemeland-free-gifts-for-woocommerce-lite'); ?></span>
        </div>
        <div class="wgbl-header-left">
            <div class="wgbl-plugin-help">
                <span>
                    <a href="<?php echo (!empty($this->doc_link)) ? esc_attr($this->doc_link) : '#'; ?>"><strong class="wgbl-plugin-help-text"><?php esc_html_e('Need Help', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></strong> <i class="lni-help"></i></a>
                </span>
            </div>
            <div class="wgbl-full-screen" id="wgbl-full-screen">
                <span><i class="lni lni-frame-expand"></i></span>
            </div>
            <div class="wgbl-upgrade" id="wgbl-upgrade">
                <a href="<?php echo esc_url(WGBL_UPGRADE_URL); ?>"><?php echo esc_html(WGBL_UPGRADE_TEXT); ?></a>
            </div>
            <div class="wgbl-youtube-button" id="wgbl-youtube-button">
                <a target="_blank" href="<?php echo esc_url("https://www.youtube.com/playlist?list=PLo0x1Hax3FuvhwPqSHJQWXT4DqLeqyOCu"); ?>"><?php esc_html_e('Watch Pro version'); ?></a>
            </div>
        </div>
    </div>