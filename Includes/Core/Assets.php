<?php
/**
 * Asset Manager
 *
 * Handles conditional loading of CSS and JS files.
 * Only loads assets when they are actually needed.
 *
 * @package SuperElemPro\Core
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Assets
 */
class Assets
{

    /**
     * Constructor — hooks into WordPress asset system.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_assets']);
    }

    /**
     * Enqueue frontend assets.
     * Assets load conditionally based on what is on the page.
     *
     * @return void
     */
    public function enqueue_frontend_assets(): void
    {

        // Main frontend CSS (very lightweight base)
        wp_register_style(
            'sep-frontend',
            SEP_ASSETS_URL . 'css/frontend.css',
            [],
            SEP_VERSION
        );

        // Main frontend JS
        wp_register_script(
            'sep-frontend',
            SEP_ASSETS_URL . 'js/frontend.js',
            ['jquery'],
            SEP_VERSION,
            true
        );

        // Swiper.js (for carousels) — loaded from CDN for performance
        wp_register_script(
            'sep-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [],
            '11.0.0',
            true
        );

        wp_register_style(
            'sep-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.0.0'
        );

        // Lottie player
        wp_register_script(
            'sep-lottie',
            'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js',
            [],
            '5.12.2',
            true
        );

        // Chart.js (for charts widget)
        wp_register_script(
            'sep-chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
            [],
            '4.4.0',
            true
        );

        // Localize script with useful data
        wp_localize_script('sep-frontend', 'sepConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sep_frontend_nonce'),
            'pluginUrl' => SEP_PLUGIN_URL,
            'isLoggedIn' => is_user_logged_in() ? '1' : '0',
        ]);
    }

    /**
     * Enqueue admin area assets.
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets(string $hook): void
    {

        // Only load on our own settings page
        if (strpos($hook, 'super-elem-pro') === false) {
            return;
        }

        wp_enqueue_style(
            'sep-admin',
            SEP_ASSETS_URL . 'css/admin.css',
            [],
            SEP_VERSION
        );

        wp_enqueue_script(
            'sep-admin',
            SEP_ASSETS_URL . 'js/admin.js',
            ['jquery'],
            SEP_VERSION,
            true
        );

        wp_localize_script('sep-admin', 'sepAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sep_admin_nonce'),
        ]);
    }

    /**
     * Enqueue Elementor editor assets.
     *
     * @return void
     */
    public function enqueue_editor_assets(): void
    {

        wp_enqueue_style(
            'sep-editor',
            SEP_ASSETS_URL . 'css/editor.css',
            [],
            SEP_VERSION
        );

        wp_enqueue_script(
            'sep-editor',
            SEP_ASSETS_URL . 'js/editor.js',
            ['elementor-editor'],
            SEP_VERSION,
            true
        );
    }

    /**
     * Helper: Enqueue a specific widget's assets only when needed.
     * Call this from within a widget's render method.
     *
     * @param string $handle Asset handle without 'sep-' prefix.
     * @return void
     */
    public static function enqueue_widget_asset(string $handle): void
    {
        $style_handle = 'sep-' . $handle;
        $script_handle = 'sep-' . $handle;

        if (wp_style_is($style_handle, 'registered')) {
            wp_enqueue_style($style_handle);
        }

        if (wp_script_is($script_handle, 'registered')) {
            wp_enqueue_script($script_handle);
        }
    }
}