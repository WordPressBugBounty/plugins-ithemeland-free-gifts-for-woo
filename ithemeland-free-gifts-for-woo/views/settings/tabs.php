<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php if (!empty($settings_tabs_title)) : ?>
    <div class="wgb-setting-page-titles">
        <ul>
            <?php foreach ($settings_tabs_title as $itfreegift_name => $itfreegift_label) : ?>
                <li><a href="<?php echo esc_url(add_query_arg(["tab" => "settings", "sub-tab" => esc_attr($itfreegift_name)], WGBL_MAIN_PAGE)); ?>" class="<?php echo (!empty($_GET['sub-tab']) && $_GET['sub-tab'] == esc_attr($itfreegift_name)) ? 'active' : '';  //phpcs:ignore 
                                                                                                                                                            ?>"><?php echo esc_html($itfreegift_label); ?> <?php echo ('promotion' == $itfreegift_name) ? '<span class="wgb-setting-page-title-pro">In Pro Version</span>' : ''; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>