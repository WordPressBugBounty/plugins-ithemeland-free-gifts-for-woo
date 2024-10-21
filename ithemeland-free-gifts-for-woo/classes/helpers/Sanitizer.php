<?php

namespace wgbl\classes\helpers;

class Sanitizer
{
    public static function array($array)
    {
        $sanitized = null;
        if (is_array($array)) {
            if (count($array) > 0) {
                foreach ($array as $key => $value) {
                    $sanitized[$key] = (is_array($value)) ? self::array($value) : sprintf("%s", stripslashes($value));
                }
            }
        } else {
            $sanitized = sprintf("%s", stripslashes($array));
        }
        return $sanitized;
    }

    public static function allowed_html_tags()
    {
        $allowed = wp_kses_allowed_html('post');

        $allowed['input']['data-*'] = true;
        $allowed['input']['checked'] = true;
        $allowed['input']['disabled'] = true;
        $allowed['input']['type'] = true;
        $allowed['input']['id'] = true;
        $allowed['input']['class'] = true;
        $allowed['input']['placeholder'] = true;
        $allowed['input']['style'] = true;
        $allowed['input']['value'] = true;
        $allowed['input']['name'] = true;

        $allowed['label']['data-*'] = true;
        $allowed['label']['class'] = true;
        $allowed['label']['id'] = true;
        $allowed['label']['for'] = true;
        $allowed['label']['style'] = true;

        $allowed['option']['data-*'] = true;
        $allowed['option']['value'] = true;
        $allowed['option']['selected'] = true;
        $allowed['option']['disabled'] = true;

        $allowed['span']['data-*'] = true;
        $allowed['span']['class'] = true;
        $allowed['span']['style'] = true;
        $allowed['span']['id'] = true;

        $allowed['li']['data-*'] = true;
        $allowed['li']['class'] = true;
        $allowed['li']['style'] = true;
        $allowed['li']['id'] = true;

        $allowed['ul']['data-*'] = true;
        $allowed['ul']['class'] = true;
        $allowed['ul']['style'] = true;
        $allowed['ul']['id'] = true;

        $allowed['i']['data-*'] = true;
        $allowed['i']['class'] = true;
        $allowed['i']['style'] = true;
        $allowed['i']['id'] = true;

        $allowed['select']['data-*'] = true;
        $allowed['select']['class'] = true;
        $allowed['select']['name'] = true;
        $allowed['select']['id'] = true;
        $allowed['select']['disabled'] = true;
        $allowed['select']['multiple'] = true;
        $allowed['select']['style'] = true;
        $allowed['select']['title'] = true;

        $allowed['textarea']['data-*'] = true;
        $allowed['textarea']['title'] = true;
        $allowed['textarea']['placeholder'] = true;
        $allowed['textarea']['name'] = true;
        $allowed['textarea']['disabled'] = true;

        $allowed['div']['style'] = true;
        $allowed['div']['data-*'] = true;
        $allowed['div']['class'] = true;
        $allowed['div']['id'] = true;

        $allowed['a']['data-*'] = true;
        $allowed['a']['class'] = true;
        $allowed['a']['style'] = true;
        $allowed['a']['id'] = true;
        $allowed['a']['href'] = true;
        $allowed['a']['target'] = true;

        $allowed['table']['style'] = true;
        $allowed['table']['data-*'] = true;
        $allowed['table']['class'] = true;
        $allowed['table']['id'] = true;

        $allowed['tr']['style'] = true;
        $allowed['tr']['data-*'] = true;
        $allowed['tr']['class'] = true;
        $allowed['tr']['id'] = true;

        $allowed['th']['style'] = true;
        $allowed['th']['data-*'] = true;
        $allowed['th']['class'] = true;
        $allowed['th']['id'] = true;

        $allowed['td']['style'] = true;
        $allowed['td']['data-*'] = true;
        $allowed['td']['class'] = true;
        $allowed['td']['id'] = true;

        $allowed['style'] = [];
        $allowed['form']['action'] = true;
        $allowed['form']['method'] = true;

        return $allowed;
    }
}
