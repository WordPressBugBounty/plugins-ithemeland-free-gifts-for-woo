<?php

namespace ITFreeGift\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class License_Controller
{
    public function index()
    {
        include WGBL_VIEWS_DIR . 'license/main.php';
    }
}
