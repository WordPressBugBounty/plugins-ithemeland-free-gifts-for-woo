<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php if (!empty($settings_tabs_title)) : ?>
    <div class="wgb-setting-page-titles">
        <ul>
            <?php foreach ($settings_tabs_title as $name => $label) : ?>
                <li><a href="<?php echo esc_url(add_query_arg(["tab" => "settings", "sub-tab" => esc_attr($name)], WGBL_MAIN_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-tab']) && $_GET['sub-tab'] == esc_attr($name)) ? 'active' : '';  //phpcs:ignore 
                                                                                                                                                ?>"><?php echo esc_html($label); ?> <?php echo ('promotion' == $name) ? '<span class="wgb-setting-page-title-pro">In Pro Version</span>' : ''; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>