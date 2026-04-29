<?php
namespace SuperElemPro\Modules\Forms\Actions;

if (!defined('ABSPATH'))
    exit;

/**
 * Webhook Action
 * 
 * @since 1.0.0
 */
class WebhookAction
{

    public static function trigger($webhook_url, $form_id, $fields)
    {
        if (empty($webhook_url) || !filter_var($webhook_url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $payload = [
            'form_id' => $form_id,
            'timestamp' => current_time('mysql'),
            'fields' => $fields,
            'meta' => [
                'site_url' => get_site_url(),
                'site_name' => get_bloginfo('name'),
                'user_ip' => self::get_user_ip(),
                'user_agent' => self::get_user_agent(),
            ],
        ];

        $response = wp_remote_post($webhook_url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($payload),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            error_log('SEP Webhook Error: ' . $response->get_error_message());
            return false;
        }

        $status_code = wp_remote_retrieve_response_code($response);

        do_action('sep_after_webhook_trigger', $status_code, $webhook_url, $payload);

        return $status_code >= 200 && $status_code < 300;
    }

    private static function get_user_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
        } else {
            return sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        }
    }

    private static function get_user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
    }
}