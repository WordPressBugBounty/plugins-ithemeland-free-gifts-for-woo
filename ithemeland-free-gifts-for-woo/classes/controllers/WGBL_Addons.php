<?php

namespace wgb\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\presenters\addons\Addons_Presenter;

class WGBL_Addons
{
    private $page_title;
    private $doc_link;

    public function __construct()
    {
        $this->page_title = esc_html__('GIFTiT – iThemeland Free Gifts for Woo Lite', 'ithemeland-free-gifts-for-woo');
        $this->doc_link = "https://ithemelandco.com/support-center";
    }

    public function index()
    {
        $addons = $this->get_addons();
        $addons_presenter = Addons_Presenter::get_instance();

        include_once WGBL_VIEWS_DIR . "addons/main.php";
    }

    private function get_addons()
    {
        return [
            [
                'plugin' => 'ithemeland-free-gifts-for-woo-notice-addon/ithemeland-free-gifts-for-woo-notice-addon.php',
                'image_link' => esc_url(WGBL_IMAGES_URL . "addons/notice.jpg"),
                'label' => 'Notice',
                'license' => true,
                'download_link' => '#',
                'version' => '1.0.0',
                'buy_link' => "https://ithemelandco.com/plugins/woocommerce-bulk-product-editing",
                'landing_page' => 'https://ithemelandco.com/plugins/woocommerce-bulk-product-editing',
            ]
        ];
    }
}
