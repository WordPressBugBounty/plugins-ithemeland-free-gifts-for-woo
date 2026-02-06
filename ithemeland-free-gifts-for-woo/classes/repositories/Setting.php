<?php

namespace wgb\classes\repositories;

use wgb\classes\helpers\Sanitizer;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Setting
{
    private static $instance;

    private $option_name;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->option_name = "wgb_settings";
    }

    public function update($settings)
    {
        return update_option($this->option_name, Sanitizer::array($settings));
    }

    public function get()
    {
        return get_option($this->option_name, []);
    }

    public function update_general_settings($data)
    {
        $settings = $this->get();
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $setting) {
                if ($key == 'view_gift_in_cart' && is_array($setting)) {
                    $settings[$key] = $settings[$key] + esc_sql($setting);
                } else {
                    $settings[$key] = esc_sql($setting);
                }
            }
        }

        return $this->update($settings);
    }

    public function set_default_settings()
    {
        return update_option($this->option_name, [
            'dashboard_date' => 'one_month_ago',
            'enable_ajax_add_to_cart' => 'true',
            'display_price' => 'no',
            'position' => 'bottom_cart',
            'layout' => 'carousel',
            'number_per_page' => 4,
            'desktop_columns' => 'wgb-col-md-2',
            'tablet_columns' => 'wgb-col-sm-2',
            'mobile_columns' => 'wgb-col-2',
            'carousel' => [
                'speed' => 5000,
                'mobile' => 1,
                'tablet' => 3,
                'desktop' => 5,
            ],
            'add_gift_text_color' => $this->get_add_gift_default_color('text_color'),
            'add_gift_text_color_hover' => $this->get_add_gift_default_color('text_color_hover'),
            'add_gift_background' => $this->get_add_gift_default_color('background'),
            'add_gift_background_hover' => $this->get_add_gift_default_color('background_hover'),
            'add_gift_border_color' => $this->get_add_gift_default_color('border_color'),
            'add_gift_border_color_hover' => $this->get_add_gift_default_color('border_color_hover'),
        ]);
    }

    public function get_add_gift_default_color($name)
    {
        $colors = [
            'text_color' => '#e4003b',
            'text_color_hover' => '#ffffff',
            'background' => '#ffffff',
            'background_hover' => '#e4003b',
            'border_color' => '#e4003b',
            'border_color_hover' => '#e4003b',
        ];

        return (!empty($colors[$name])) ? $colors[$name] : '';
    }

    public function maybe_sync()
    {
        $settings = $this->get();
        $need_to_update = false;

        if (!empty($settings['desktop_columns']) && strpos($settings['desktop_columns'], 'wgbl') !== false) {
            $settings['desktop_columns'] = str_replace('wgbl', 'wgb', $settings['desktop_columns']);
            $need_to_update = true;
        }
        if (!empty($settings['tablet_columns']) && strpos($settings['tablet_columns'], 'wgbl') !== false) {
            $settings['tablet_columns'] = str_replace('wgbl', 'wgb', $settings['tablet_columns']);
            $need_to_update = true;
        }
        if (!empty($settings['mobile_columns']) && strpos($settings['mobile_columns'], 'wgbl') !== false) {
            $settings['mobile_columns'] = str_replace('wgbl', 'wgb', $settings['mobile_columns']);
            $need_to_update = true;
        }

        if ($need_to_update) {
            $this->update($settings);
        }
    }

    public function has_settings()
    {
        return (!empty($this->get()));
    }

    public function get_localization()
    {
        global $wpdb;
        $localization_fields = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'itg_localization_%'", ARRAY_A); //phpcs:ignore
        return array_column($localization_fields, 'option_value', 'option_name');
    }

    public function get_dashboard_date()
    {
        $settings = $this->get();
        $date = gmdate('Y/m/d', time() - 2592000);

        if (!empty($settings['dashboard_date'])) {
            switch ($settings['dashboard_date']) {
                case 'one_month_ago':
                    $date = gmdate('Y/m/d', time() - 2592000);
                    break;
                case 'the_last_three_months':
                    $date = gmdate('Y/m/d', time() - 7776000);
                    break;
                case 'the_last_six_months':
                    $date = gmdate('Y/m/d', time() - 15552000);
                    break;
                case 'nine_months_ago':
                    $date = gmdate('Y/m/d', time() - 23328000);
                    break;
                case 'once_year_ago':
                    $date = gmdate('Y/m/d', time() - 31104000);
                    break;
                case 'the_last_two_years':
                    $date = gmdate('Y/m/d', time() - 62208000);
                    break;
                case 'the_last_three_years':
                    $date = gmdate('Y/m/d', time() - 93312000);
                    break;
                default:
                    $date = gmdate('Y/m/d', time() - 2592000);
            }
        }

        return $date;
    }
}
