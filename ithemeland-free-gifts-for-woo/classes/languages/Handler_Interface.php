<?php

namespace wgb\classes\languages;

defined('ABSPATH') || exit(); // Exit if accessed directly

interface Handler_Interface
{
    public function get_languages();

    public function get_current_language();

    public function switch_language($language);
}
