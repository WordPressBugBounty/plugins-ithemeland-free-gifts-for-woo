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
            <div class="wgb-tab-content-item" data-content="shortcodes">
                <div class="wgb-wrap">
                    <div class="wgb-tab-middle-content">
                        <?php
                        if (!empty($flush_message) && is_array($flush_message)) {
                            include WGBL_VIEWS_DIR . "alerts/flush_message.php";
                        }
                        ?>
                        <!-- Shortcode item -->
                        <div class="wgb-shortcode-box">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Shortcode</th>
                                        <th colspan="1">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>[itg_gift_products type="datatable"]</td>
                                        <td>Display Current Gift products in Datatable</td>
                                    </tr>
                                    <tr>
                                        <td>[itg_gift_products type="grid"]</td>
                                        <td>Display Current Gift products in Grid</td>
                                    </tr>
                                    <tr>
                                        <td>[itg_gift_products type="carousel"]</td>
                                        <td>Display Current Gift products in Carousel</td>
                                    </tr>
                                    <tr>
                                        <td>[itg_gift_products type="dropdown"]</td>
                                        <td>Display Current Gift products in Dropdown</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once  WGBL_VIEWS_DIR . "layout/footer.php"; ?>