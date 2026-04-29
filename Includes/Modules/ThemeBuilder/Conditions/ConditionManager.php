<?php
/**
 * Condition Manager
 *
 * Checks whether a template's display conditions match
 * the current page being viewed.
 *
 * @package SuperElemPro\Modules\ThemeBuilder\Conditions
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\ThemeBuilder\Conditions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ConditionManager
 */
class ConditionManager
{

    /**
     * Check if a template's conditions match the current page.
     *
     * @param int $template_id Post ID of the template.
     * @return bool
     */
    public function check(int $template_id): bool
    {

        // Get saved conditions for this template
        $conditions = get_post_meta($template_id, '_sep_display_conditions', true);

        // If no conditions set, default to showing everywhere
        if (empty($conditions) || !is_array($conditions)) {
            return true;
        }

        foreach ($conditions as $condition) {

            $type = $condition['type'] ?? 'general';
            $subtype = $condition['subtype'] ?? '';
            $value = $condition['value'] ?? '';

            if ($this->evaluate_condition($type, $subtype, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Evaluate a single condition.
     *
     * @param string $type    Condition type (general, singular, archive, etc.)
     * @param string $subtype Condition subtype.
     * @param string $value   Condition value.
     * @return bool
     */
    private function evaluate_condition(string $type, string $subtype, string $value): bool
    {

        switch ($type) {

            case 'general':
                return $this->check_general($subtype);

            case 'singular':
                return $this->check_singular($subtype, $value);

            case 'archive':
                return $this->check_archive($subtype, $value);

            case 'search':
                return is_search();

            case '404':
                return is_404();

            case 'front_page':
                return is_front_page();

            case 'blog':
                return is_home();

            default:
                return apply_filters(
                    'sep/theme_builder/condition',
                    false,
                    $type,
                    $subtype,
                    $value
                );
        }
    }

    /**
     * Check general conditions.
     *
     * @param string $subtype
     * @return bool
     */
    private function check_general(string $subtype): bool
    {
        switch ($subtype) {
            case 'all':
                return true;
            case 'logged_in':
                return is_user_logged_in();
            case 'logged_out':
                return !is_user_logged_in();
            default:
                return false;
        }
    }

    /**
     * Check singular conditions.
     *
     * @param string $subtype
     * @param string $value
     * @return bool
     */
    private function check_singular(string $subtype, string $value): bool
    {

        if (!is_singular()) {
            return false;
        }

        switch ($subtype) {
            case 'all':
                return true;
            case 'post_type':
                return is_singular($value);
            case 'post_id':
                return get_the_ID() === (int) $value;
            case 'taxonomy':
                global $post;
                if (!$post)
                    return false;
                $terms = wp_get_post_terms($post->ID, $value);
                return !empty($terms) && !is_wp_error($terms);
            default:
                return false;
        }
    }

    /**
     * Check archive conditions.
     *
     * @param string $subtype
     * @param string $value
     * @return bool
     */
    private function check_archive(string $subtype, string $value): bool
    {

        if (!is_archive() && !is_home()) {
            return false;
        }

        switch ($subtype) {
            case 'all':
                return true;
            case 'post_type':
                return is_post_type_archive($value);
            case 'taxonomy':
                return is_tax($value) || is_category() || is_tag();
            default:
                return false;
        }
    }
}