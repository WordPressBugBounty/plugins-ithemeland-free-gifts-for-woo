<label>
    <input
        type="checkbox"
        id="wgb_usage_track"
        name="wgb_usage_track"
        value="yes"
        <?php checked(1, $option); ?> />
    <span><?php esc_html_e('iThemeland Free Gifts For WooCommerce', 'ithemeland-free-gifts-for-woo'); ?></span>
    <p class="description">
        <?php echo esc_html($description); ?>
        <a href="https://ithemelandco.com/usage-tracking?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=telemetry"><?php esc_html_e('Learn More', 'ithemeland-free-gifts-for-woo'); ?></a>
    </p>
</label>