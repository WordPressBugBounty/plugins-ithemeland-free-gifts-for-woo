<li>
    <a class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'rules') ? 'selected' : ''; //phpcs:ignore ?>" data-content="rules" data-type="main-tab" href="<?php echo esc_url(WGBL_MAIN_PAGE . '&tab=rules'); ?>">
        <?php esc_html_e('Rules', 'ithemeland-free-gifts-for-woo'); ?>
    </a>
</li>
<li>
    <a class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'selected' : ''; //phpcs:ignore ?>" data-content="settings" data-type="main-tab" href="<?php echo esc_url(WGBL_MAIN_PAGE . '&tab=settings'); ?>">
        <?php esc_html_e('Settings', 'ithemeland-free-gifts-for-woo'); ?>
    </a>
</li>
<li>
    <a class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'shortcodes') ? 'selected' : ''; //phpcs:ignore ?>" data-content="shortcodes" data-type="main-tab" href="<?php echo esc_url(WGBL_MAIN_PAGE . '&tab=shortcodes'); ?>">
        <?php esc_html_e('Shortcodes', 'ithemeland-free-gifts-for-woo'); ?>
    </a>
</li>
<li>
    <a class="<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'reports') ? 'selected' : ''; //phpcs:ignore ?>" data-content="reports" data-type="main-tab" href="<?php echo esc_url(WGBL_MAIN_PAGE . '&tab=reports'); ?>">
        <?php esc_html_e('Reports', 'ithemeland-free-gifts-for-woo'); ?>
    </a>
</li>