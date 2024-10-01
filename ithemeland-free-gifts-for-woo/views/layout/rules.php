<?php include WGBL_VIEWS_DIR . "layout/header.php"; ?>
<div id="wgbl-body">
    <div class="wgbl-tabs wgbl-tabs-main">
        <div class="wgbl-tabs-navigation">
            <nav class="wgbl-tabs-navbar">
                <ul class="wgbl-tabs-list" data-content-id="wgbl-main-tabs-contents">
                    <?php if (!empty($tabs_title)) : ?>
                        <?php foreach ($tabs_title as $tab_key => $tab_label) : ?>
                            <li>
                                <a data-content="<?php echo esc_attr($tab_key); ?>" data-type="main-tab" href="#">
                                    <?php echo esc_attr($tab_label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div class="wgbl-tabs-contents" id="wgbl-main-tabs-contents">
            <?php if (!empty($tabs_content)) : ?>
                <?php foreach ($tabs_content as $content_key => $content_file) : ?>
                    <div class="wgbl-tab-content-item" data-content="<?php echo esc_attr($content_key); ?>">
                        <?php
                        if (file_exists($content_file)) {
                            include $content_file;
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>