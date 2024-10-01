<?php

namespace wgbl\classes\presenters\reports;

interface Handler_Interface
{
    public static function get_instance();

    public function get_reports($data);
}
