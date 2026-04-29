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
        $submission_title = sprintf(
            __('Submission #%s', 'super-elem-pro'),
            time()
        );

        $submission_id = wp_insert_post([
            'post_type' => 'sep_form_submission',
            'post_title' => $submission_title,
            'post_status' => 'publish',
        ]);

        if (is_wp_error($submission_id)) {
            return false;
        }

        update_post_meta($submission_id, '_sep_form_id', $form_id);
        update_post_meta($submission_id, '_sep_form_data', $fields);
        update_post_meta($submission_id, '_sep_submission_date', current_time('mysql'));
        update_post_meta($submission_id, '_sep_user_ip', self::get_user_ip());
        update_post_meta($submission_id, '_sep_user_agent', self::get_user_agent());
        update_post_meta($submission_id, '_sep_referer', wp_get_referer());

        if (is_user_logged_in()) {
            update_post_meta($submission_id, '_sep_user_id', get_current_user_id());
        }

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