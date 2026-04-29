<?php
/**
 * Plugin Uninstall
 *
 * Runs only when the plugin is deleted from WordPress admin.
 * Cleans up all plugin data from the database.
 *
 * @package SuperElemPro
 * @author  Rohit
 */

// Only run when WordPress is uninstalling this plugin
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// ─── Delete plugin options ──────────────────────────────────
$options = [
    'sep_version',
    'sep_activated_at',
    'sep_modules_enabled',
];

foreach ( $options as $option ) {
    delete_option( $option );
}

// ─── Delete custom post types and their meta ────────────────
$post_types = [ 'sep_template', 'sep_popup' ];

foreach ( $post_types as $post_type ) {
    $posts = get_posts([
        'post_type'      => $post_type,
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ( $posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }
}

// ─── Drop custom database tables ───────────────────────────
// phpcs:ignore WordPress.DB.DirectDatabaseQuery
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sep_form_submissions" );

// ─── Delete taxonomy terms ──────────────────────────────────
$taxonomies = [ 'sep_template_type' ];

foreach ( $taxonomies as $taxonomy ) {
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'fields'     => 'ids',
    ]);

    if ( ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term_id ) {
            wp_delete_term( $term_id, $taxonomy );
        }
    }
}

// ─── Clear any cached data ─────────────────────────────────
wp_cache_flush();