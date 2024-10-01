<div class="wgbl-setting-page-titles">
    <ul>
        <li>
            <a href="<?php echo esc_url(add_query_arg(["sub-page" => 'general'], WGBL_MAIN_PAGE)); ?>#settings" class="<?php echo (empty($_GET['sub-page']) || $_GET['sub-page'] == 'general') ? 'active' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                        ?>"><?php esc_html_e('General', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url(add_query_arg(["sub-page" => 'localization'], WGBL_MAIN_PAGE)); ?>#settings" class="<?php echo (!empty($_GET['sub-page']) && $_GET['sub-page'] == 'localization') ? 'active' : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                                                                                                                            ?>"><?php esc_html_e('Localization', 'ithemeland-free-gifts-for-woocommerce-lite'); ?>
            </a>
        </li>
    </ul>
</div>