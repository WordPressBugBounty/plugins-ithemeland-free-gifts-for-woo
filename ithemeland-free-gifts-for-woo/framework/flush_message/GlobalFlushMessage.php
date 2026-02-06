<?php

namespace wgb\framework\flush_message;

defined('ABSPATH') || exit(); // Exit if accessed directly

class GlobalFlushMessage
{
    private static $flush_message_option_name = 'ithemeland_flush_message';

    public static function set($data)
    {
        return update_option(self::$flush_message_option_name, $data);
    }

    public static function get()
    {
        $flush_message = get_option(self::$flush_message_option_name);
        self::delete();
        return $flush_message;
    }

    public static function delete()
    {
        return delete_option(self::$flush_message_option_name);
    }
}
