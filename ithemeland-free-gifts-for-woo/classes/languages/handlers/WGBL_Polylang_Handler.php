<?php

namespace wgb\classes\languages\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\languages\Handler_Interface;

class WGBL_Polylang_Handler implements Handler_Interface
{
    private $current_language;
    private $languages;

    public function __construct()
    {
        $this->languages = [];
        $this->set_languages();
    }

    public function set_languages()
    {
        $this->current_language = pll_current_language('slug');

        $active_languages = pll_the_languages(['raw' => 1]);
        if (!empty($active_languages)) {
            foreach ($active_languages as $lang) {
                if (!empty($lang['slug']) && !empty($lang['name'])) {
                    $this->languages[esc_attr($lang['slug'])] = esc_html($lang['name']);
                }
            }
        }
    }

    public function get_languages()
    {
        return $this->languages;
    }

    public function get_current_language()
    {
        return $this->current_language;
    }

    public function switch_language($language)
    {
        return false;
    }
}
