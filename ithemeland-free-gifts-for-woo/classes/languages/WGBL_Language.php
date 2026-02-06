<?php

namespace wgb\classes\languages;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\languages\handlers\WGBL_Polylang_Handler;
use wgb\classes\languages\handlers\WGBL_Wpml_Handler;

class WGBL_Language
{
    private static $instance;

    private $language_plugin;
    private $handler;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        if (defined('WPML_PLUGIN_PATH')) {
            $this->language_plugin = 'wpml';
        }

        if (empty($this->language_plugin) && defined('POLYLANG')) {
            $this->language_plugin = 'polylang';
        }

        if (!empty($this->language_plugin)) {
            $handler_class = $this->get_handler($this->language_plugin);
            if (class_exists($handler_class)) {
                $this->handler = new $handler_class();
            }
        }
    }

    private function get_handler($handler)
    {
        $handlers = $this->get_handlers();
        if (isset($handlers[$handler]) && class_exists($handlers[$handler])) {
            return $handlers[$handler];
        }

        return false;
    }

    private function get_handlers()
    {
        return [
            'wpml' => WGBL_Wpml_Handler::class,
            'polylang' => WGBL_Polylang_Handler::class,
        ];
    }

    public function get_languages()
    {
        if (!is_object($this->handler)) {
            return [];
        }

        return $this->handler->get_languages();
    }

    public function get_current_language()
    {
        if (!is_object($this->handler)) {
            return false;
        }

        return $this->handler->get_current_language();
    }

    public function switch_language($language)
    {
        if (!is_object($this->handler)) {
            return false;
        }

        return $this->handler->switch_language($language);
    }
}
