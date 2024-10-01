<?php


namespace wgbl\classes\helpers;


class Array_Helper
{

    public static function flatten($array, $sanitize = null)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::flatten($value, $sanitize));
            } else {
                switch ($sanitize) {
                    case 'int':
                        $result = array_merge($result, array($key => intval($value)));
                        break;
                    default:
                        $result = array_merge($result, array($key => $value));
                        break;
                }
            }
        }
        return $result;
    }
}
