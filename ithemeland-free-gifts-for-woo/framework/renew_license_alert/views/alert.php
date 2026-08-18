<?php
if (! defined('ABSPATH')) exit;
?>

<div class="wrap wgb-wp-notice">
    <div class="wgb-license-notice-lite" id="wgb-renew-license-alert" data-name="renew_license_alert">
        <button class="wgb-license-notice-lite-close" type="button" aria-label="Dismiss notice"
            onclick="document.getElementById('wgb-renew-license-alert').style.display='none'"><span class="dashicons dashicons-no-alt"></span></button>
        <div>⚠️</div>
        <div>
            <strong><?php esc_html_e('GIFTiT Pro update required to continue receiving updates', 'ithemeland-free-gifts-for-woo') ?></strong>
            <p><?php esc_html_e('Your current version of GIFTiT Pro is no longer receiving security, compatibility, and maintenance updates. Continuing to use an outdated Pro version may cause compatibility issues with future versions of WordPress and WooCommerce. Renew your license now to restore access to the latest GIFTiT Pro updates, security fixes, improvements, and support.', 'ithemeland-free-gifts-for-woo') ?></p>
        </div>
        <div class="wgb-license-notice-lite-actions">
            <a class="wgb-renew-license-alert-btn-lite wgb-renew-license-alert-btn-lite-green wgb-renew-license-alert-btn-lite-sm" href="https://ithemelandco.com/cart/?add-to-cart=18676/?utm_source=<?php echo esc_url(get_site_url()); ?>&utm_medium=web_links&utm_campaign=renewals-user-buy"><?php esc_html_e('Renew GIFTiT Pro', 'ithemeland-free-gifts-for-woo') ?></a>
        </div>
    </div>
</div>