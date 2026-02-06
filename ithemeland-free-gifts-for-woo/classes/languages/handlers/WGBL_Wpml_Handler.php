<?php

namespace wgb\classes\languages\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\languages\Handler_Interface;

class WGBL_Wpml_Handler implements Handler_Interface
{
    private $current_language;
    private $languages;
    private $sitepress;

    public function __construct()
    {
        $this->languages = [];

        global $sitepress;
        if ($sitepress) {
            $this->sitepress = $sitepress;
            $this->current_language = $sitepress->get_current_language();
        }

        $this->set_languages();
    }

    private function set_languages()
    {
        $active_languages = apply_filters('wpml_active_languages', []);
        if (!empty($active_languages)) {
            foreach ($active_languages as $lang) {
                if (!empty($lang['code']) && !empty($lang['translated_name'])) {
                    $this->languages[esc_attr($lang['code'])] = esc_html($lang['translated_name']);
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
        return $this->sitepress->switch_lang($language);
    }
}
