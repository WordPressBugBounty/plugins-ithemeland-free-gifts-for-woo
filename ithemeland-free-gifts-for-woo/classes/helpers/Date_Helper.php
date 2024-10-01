<?php

namespace wgbl\classes\helpers;

class Date_Helper
{
    public static function get_nice_date_format($date_timestamp)
    {
        return ($date_timestamp > strtotime('-1 day', time()) && $date_timestamp <= time() && function_exists('human_time_diff')) ? sprintf('%s ago', human_time_diff($date_timestamp, time())) : date_i18n('M j, Y', $date_timestamp);
    }
}
