<?php

namespace wgbl\classes\presenters\reports;

use wgbl\classes\presenters\reports\handlers\Dashboard_Handler;

class Report_Presenter
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function get_reports($data)
    {
        if (empty($data['sub-page'])) {
            $data['sub-page'] = 'dashboard';
        }

        $page = (!empty($data['sub-menu'])) ? $data['sub-menu'] : $data['sub-page'];
        $handlerClass = $this->get_handler(sanitize_text_field($page));
        if (empty($handlerClass) || !class_exists($handlerClass)) {
            return false;
        }

        $handler = $handlerClass::get_instance();
        return $handler->get_reports($data);
    }

    private function get_handler($page)
    {
        $handlers = $this->get_handlers();
        return (!empty($handlers[$page]) && class_exists($handlers[$page])) ? $handlers[$page] : null;
    }

    private function get_handlers()
    {
        return [
            'dashboard' => Dashboard_Handler::class,
        ];
    }
}
