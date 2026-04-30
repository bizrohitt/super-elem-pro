<?php
namespace SuperElemPro\Modules\Forms\Actions;

if (!defined('ABSPATH'))
    exit;

/**
 * Database Action
 * 
 * @since 1.0.0
 */
class DatabaseAction
{

    public static function save($form_id, $fields)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sep_form_submissions';

        $data = [
            'form_id'    => $form_id,
            'form_name'  => 'Form ' . $form_id,
            'fields'     => json_encode($fields),
            'user_ip'    => self::get_user_ip(),
            'user_agent' => self::get_user_agent(),
            'status'     => 'unread',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ];

        if (is_user_logged_in()) {
            $data['user_id'] = get_current_user_id();
        }

        $format = [
            '%d', // form_id
            '%s', // form_name
            '%s', // fields
            '%s', // user_ip
            '%s', // user_agent
            '%s', // status
            '%s', // created_at
            '%s', // updated_at
        ];
        
        if (isset($data['user_id'])) {
            $format[] = '%d';
        }

        $inserted = $wpdb->insert($table_name, $data, $format);

        if (!$inserted) {
            return false;
        }

        $submission_id = $wpdb->insert_id;

        do_action('sep_after_database_save', $submission_id, $form_id, $fields);

        return $submission_id;
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