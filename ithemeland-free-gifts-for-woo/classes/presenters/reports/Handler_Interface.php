<?php

namespace wgb\classes\presenters\reports;

defined('ABSPATH') || exit();

interface Handler_Interface
{
    public static function get_instance();

    public function get_reports($data);
}
