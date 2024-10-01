<?php

namespace wgbl\classes\repositories;

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
        $this->option_name = "wgbl_settings";
    }

    public function update($settings)
    {
        return update_option($this->option_name, esc_sql($settings));
    }

    public function get()
    {
        return get_option($this->option_name, []);
    }

    public function set_default_settings()
    {
        return update_option($this->option_name, [
            'dashboard_date' => 'one_month_ago',
            'display_price' => 'no',
            'position' => 'bottom_cart',
            'layout' => 'carousel',
            'view_gift_in_cart' => [
                'number_per_page' => 4,
                'desktop_columns' => 'wgb-col-md-2',
                'tablet_columns' => 'wgb-col-sm-2',
                'mobile_columns' => 'wgb-col-2',
                'carousel' => [
                    'speed' => 5000,
                    'mobile' => 1,
                    'tablet' => 3,
                    'desktop' => 5,
                ]
            ]
        ]);
    }

    public function has_settings()
    {
        return (!empty($this->get()));
    }

    public function get_localization()
    {
        global $wpdb;
        //$query = $wpdb->prepare("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", "itg_localization_%");
        $localization_fields = $wpdb->get_results($wpdb->prepare("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", "itg_localization_%"), ARRAY_A);
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
