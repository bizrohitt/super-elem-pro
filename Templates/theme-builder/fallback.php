<?php
/**
 * Theme Builder — Fallback page template
 * Used when a custom single/archive/search/404 template is active.
 *
 * @package SuperElemPro
 * @author  Rohit
 */

if (!defined('ABSPATH')) {
    exit;
}

global $sep_current_template_id;

get_header();

if ($sep_current_template_id) {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(
        $sep_current_template_id,
        true
    );
}

get_footer();