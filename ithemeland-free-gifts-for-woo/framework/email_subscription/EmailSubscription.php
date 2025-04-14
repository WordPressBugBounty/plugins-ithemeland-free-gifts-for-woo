<?php

namespace wgbl\framework\email_subscription;

defined('ABSPATH') || exit();

class EmailSubscription
{
    private $service_url;

    public function __construct()
    {
        $this->service_url = "http://usage-tracking.ithemelandco.com/index.php";
    }

    public function add_subscription($data)
    {
        $data['service'] = 'email_subscription';
        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'method' => 'POST',
            'timeout' => 45,
            'httpversion' => '1.0',
            'body' => $data
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message()
            ];
        }

        if (wp_remote_retrieve_response_code($response) != 200) {
            return [
                'success' => false,
                'message' => 'Server error: ' . wp_remote_retrieve_response_message($response)
            ];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return is_array($data) ? $data : [
            'success' => false,
            'message' => 'Invalid server response'
        ];
    }
}
