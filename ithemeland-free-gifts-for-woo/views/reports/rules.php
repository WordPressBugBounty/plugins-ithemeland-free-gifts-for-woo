<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<?php include WGBL_VIEWS_DIR . "layout/header.php"; ?>
<div id="wgb-body">
    <div class="wgb-tabs wgb-tabs-main">
        <div class="wgb-tabs-navigation">
            <nav class="wgb-tabs-navbar">
                <ul class="wgb-tabs-list" data-content-id="wgb-main-tabs-contents">
                    <?php include WGBL_VIEWS_DIR . 'layout/tabs_title.php' ?>
                </ul>
            </nav>
        </div>
        <div class="wgb-tabs-contents" id="wgb-main-tabs-contents">
            <div class="wgb-tab-content-item">
                <div class="wgb-tab-middle-content">
                    <div class="wgb-wrap">
                        <?php
                        include_once WGBL_VIEWS_DIR . "reports/tabs_title.php";
                        ?>
                        <div class="wgb-tab-middle-content wgb-skeleton">
                            <div class="wgb-row">
                                <div class="wgb-col-12">
                                    <div class="wgb-alert wgb-alert-danger">
                                        <span class="wgb-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WGBL_UPGRADE_URL); ?>"><?php echo esc_html(WGBL_UPGRADE_TEXT); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>