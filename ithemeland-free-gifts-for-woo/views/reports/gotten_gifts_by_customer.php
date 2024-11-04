<?php include WGBL_VIEWS_DIR . "layout/reports_header.php"; ?>
<div class="wgbl-wrap">
    <div class="wgbl-tab-middle-content wgbl-skeleton">
        <div class="wgbl-row">
            <div class="wgbl-col-12">
                <div class="wgbl-alert wgbl-alert-danger">
                    <span class="wgbl-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                    <a href="<?php echo esc_url(WGBL_UPGRADE_URL); ?>">Download Pro Version</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include WGBL_VIEWS_DIR . "layout/reports_footer.php"; ?>