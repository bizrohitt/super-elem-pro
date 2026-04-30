<?php
/**
 * Plugin Name:       Super Elem Pro
 * Plugin URI:        https://github.com/rohit/super-elem-pro
 * Description:       A powerful personal Elementor addon that unlocks pro-level features using 100% free and open-source resources. Built for personal use by Rohit.
 * Version:           1.1.0 
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Rohit
 * Author URI:        https://github.com/rohit
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       super-elem-pro
 * Domain Path:       /languages
 *
 * @package SuperElemPro
 * @author  Rohit
 * @copyright 2024 Rohit. All rights reserved.
 *
 * ============================================================
 * PERSONAL USE ONLY
 * ============================================================
 * This plugin is created for personal use only by Rohit.
 * Redistribution, resale, or use in commercial products
 * is strictly prohibited.
 * ============================================================
 */

// ============================================================
// SECURITY: Block direct file access
// ============================================================
if (!defined('ABSPATH')) {
    exit('Direct access is not allowed.');
}

// ============================================================
// CONSTANTS
// ============================================================
define('SEP_VERSION', '1.1.0');
define('SEP_PLUGIN_FILE', __FILE__);
define('SEP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEP_PLUGIN_SLUG', 'super-elem-pro');
define('SEP_PLUGIN_NAME', 'Super Elem Pro');
define('SEP_AUTHOR', 'Rohit');
define('SEP_MIN_PHP', '7.4');
define('SEP_MIN_WP', '6.0');
define('SEP_MIN_ELEMENTOR', '3.5.0');
define('SEP_ASSETS_URL', SEP_PLUGIN_URL . 'assets/');
define('SEP_ASSETS_DIR', SEP_PLUGIN_DIR . 'assets/');
define('SEP_INCLUDES_DIR', SEP_PLUGIN_DIR . 'includes/');
define('SEP_TEMPLATES_DIR', SEP_PLUGIN_DIR . 'templates/');
define('SEP_MODULES_DIR', SEP_PLUGIN_DIR . 'includes/Modules/');

// ============================================================
// COMPOSER AUTOLOADER
// ============================================================
if (file_exists(SEP_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once SEP_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    // Show admin notice if composer install has not been run
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>Super Elem Pro:</strong> Composer autoloader not found. ';
        echo 'Please run <code>composer install</code> in the plugin directory.';
        echo '</p></div>';
    });
    return;
}

// ============================================================
// COMPATIBILITY CHECKS
// ============================================================
/**
 * Check if all requirements are met before loading the plugin.
 */
function sep_check_requirements()
{

    $errors = [];

    // PHP version check
    if (version_compare(PHP_VERSION, SEP_MIN_PHP, '<')) {
        $errors[] = sprintf(
            'Super Elem Pro requires PHP %s or higher. You are running PHP %s.',
            SEP_MIN_PHP,
            PHP_VERSION
        );
    }

    // WordPress version check
    if (version_compare(get_bloginfo('version'), SEP_MIN_WP, '<')) {
        $errors[] = sprintf(
            'Super Elem Pro requires WordPress %s or higher.',
            SEP_MIN_WP
        );
    }

    // Elementor installed and active check
    if (!did_action('elementor/loaded')) {
        $errors[] = 'Super Elem Pro requires Elementor (free) to be installed and activated.';
    }

    // Elementor version check
    if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, SEP_MIN_ELEMENTOR, '<')) {
        $errors[] = sprintf(
            'Super Elem Pro requires Elementor %s or higher. You are running Elementor %s.',
            SEP_MIN_ELEMENTOR,
            ELEMENTOR_VERSION
        );
    }

    return $errors;
}

/**
 * Display admin notices for requirement errors.
 */
function sep_requirement_notices()
{
    $errors = sep_check_requirements();
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="notice notice-error is-dismissible"><p>';
            echo '<strong>Super Elem Pro:</strong> ' . esc_html($error);
            echo '</p></div>';
        }
    }
}

// ============================================================
// PLUGIN INITIALIZATION
// ============================================================
/**
 * Initialize the plugin after Elementor is loaded.
 * We hook into elementor/init to ensure Elementor is ready.
 */
function sep_initialize()
{

    // Run requirement checks
    $errors = sep_check_requirements();

    if (!empty($errors)) {
        add_action('admin_notices', 'sep_requirement_notices');
        return;
    }

    // Load text domain for translations
    load_plugin_textdomain(
        'super-elem-pro',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );

    // Boot the main plugin class
    \SuperElemPro\Core\Plugin::instance();
}
add_action('elementor/init', 'sep_initialize');

// ============================================================
// ACTIVATION HOOK
// ============================================================
register_activation_hook(__FILE__, function () {

    // Check PHP version immediately on activation
    if (version_compare(PHP_VERSION, SEP_MIN_PHP, '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            sprintf(
                'Super Elem Pro requires PHP %s or higher.',
                SEP_MIN_PHP
            )
        );
    }

    // Set default options
    $defaults = [
        'sep_version' => SEP_VERSION,
        'sep_activated_at' => current_time('mysql'),
        'sep_modules_enabled' => sep_get_default_modules(),
    ];

    foreach ($defaults as $key => $value) {
        if (false === get_option($key)) {
            update_option($key, $value, false);
        }
    }

    // Create database tables (for form submissions etc.)
    sep_create_tables();

    // Flush rewrite rules for theme builder custom post types
    flush_rewrite_rules();
});

// ============================================================
// DEACTIVATION HOOK
// ============================================================
register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

// ============================================================
// DEFAULT MODULES LIST
// ============================================================
/**
 * Returns the list of all modules with their default enabled state.
 * All modules are enabled by default (personal use plugin).
 *
 * @return array
 */
function sep_get_default_modules()
{
    return [
        // Theme Builder
        'theme_builder' => true,
        // Popup Builder
        'popup_builder' => true,
        // Forms
        'forms' => true,
        // Dynamic Tags
        'dynamic_tags' => true,
        // Custom Widgets
        'widgets' => true,
        // Extensions (Sticky, Motion, CSS, JS, Display Conditions)
        'extensions' => true,
        // WooCommerce (auto-disabled if WooCommerce not active)
        'woocommerce' => true,
        // Mega Menu
        'mega_menu' => true,
        // Marketing Tools
        'marketing' => true,
        // Performance
        'performance' => true,
        // Developer Tools
        'developer' => true,
    ];
}

// ============================================================
// DATABASE TABLE CREATION
// ============================================================
/**
 * Creates all custom database tables needed by the plugin.
 */
function sep_create_tables()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Form submissions table
    $table_submissions = $wpdb->prefix . 'sep_form_submissions';

    $sql = "CREATE TABLE IF NOT EXISTS {$table_submissions} (
        id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        form_id     BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        form_name   VARCHAR(255) NOT NULL DEFAULT '',
        fields      LONGTEXT NOT NULL,
        user_id     BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        user_ip     VARCHAR(100) NOT NULL DEFAULT '',
        user_agent  VARCHAR(500) NOT NULL DEFAULT '',
        status      VARCHAR(50) NOT NULL DEFAULT 'unread',
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY form_id (form_id),
        KEY status (status),
        KEY created_at (created_at)
    ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}