<?php
namespace SuperElemPro\Modules\PopupBuilder\DisplayConditions;

if (!defined('ABSPATH'))
    exit;

/**
 * Popup Display Conditions
 * 
 * @since 1.0.0
 */
class PopupConditions
{

    public function __construct()
    {
        // Hook for future advanced conditions
    }

    public static function check_user_role($required_role)
    {
        if (!is_user_logged_in()) {
            return false;
        }

        $user = wp_get_current_user();
        return in_array($required_role, $user->roles);
    }

    public static function check_login_status($status)
    {
        if ($status === 'logged_in') {
            return is_user_logged_in();
        }

        if ($status === 'logged_out') {
            return !is_user_logged_in();
        }

        return true;
    }

    public static function check_device($device)
    {
        if ($device === 'mobile') {
            return wp_is_mobile();
        }

        if ($device === 'desktop') {
            return !wp_is_mobile();
        }

        return true;
    }

    public static function check_post_type($post_type)
    {
        return is_singular($post_type);
    }
}