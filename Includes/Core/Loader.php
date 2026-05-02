<?php
/**
 * Module Loader
 *
 * Loads and manages all plugin modules.
 * Each module can be individually enabled or disabled
 * from the plugin settings page.
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
 * Class Loader
 */
class Loader
{

    /**
     * All registered modules.
     *
     * @var array
     */
    private array $modules = [];

    /**
     * Currently enabled modules from settings.
     *
     * @var array
     */
    private array $enabled_modules = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Get enabled modules from database (defaults: all enabled)
        $this->enabled_modules = get_option(
            'sep_modules_enabled',
            sep_get_default_modules()
        );

        // Register all available modules
        $this->register_modules();
    }

    /**
     * Register all available modules.
     * Add new modules here as the plugin grows.
     *
     * @return void
     */
    private function register_modules(): void
    {

        $this->modules = [

            // ─── Theme Builder ───────────────────────────────
            'theme_builder' => [
                'label' => __('Theme Builder', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\ThemeBuilder\ThemeBuilder::class,
                'desc' => __('Build custom headers, footers, single templates, archive templates, 404 pages and more.', 'super-elem-pro'),
                'icon' => 'dashicons-layout',
                'tab' => 'theme_builder',
            ],

            // ─── Popup Builder ───────────────────────────────
            'popup_builder' => [
                'label' => __('Popup Builder', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\PopupBuilder\PopupBuilder::class,
                'desc' => __('Create advanced popups with exit intent, scroll, time delay, and click triggers.', 'super-elem-pro'),
                'icon' => 'dashicons-external',
                'tab' => 'extensions',
            ],

            // ─── Forms ───────────────────────────────────────
            'forms' => [
                'label' => __('Advanced Forms', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Forms\Forms::class,
                'desc' => __('Multi-step forms, conditional logic, file uploads, and form submission management.', 'super-elem-pro'),
                'icon' => 'dashicons-feedback',
                'tab' => 'widgets',
            ],

            // ─── Dynamic Tags ─────────────────────────────────
            'dynamic_tags' => [
                'label' => __('Dynamic Tags', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\DynamicTags\DynamicTags::class,
                'desc' => __('Pull dynamic content from posts, users, ACF, Pods, Meta Box, and more.', 'super-elem-pro'),
                'icon' => 'dashicons-tag',
                'tab' => 'dynamic_tags',
            ],

            // ─── Custom Widgets ───────────────────────────────
            'widgets' => [
                'label' => __('Custom Widgets', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Widgets\WidgetsModule::class,
                'desc' => __('Pricing tables, testimonial carousels, timelines, before/after sliders, and more.', 'super-elem-pro'),
                'icon' => 'dashicons-admin-generic',
                'tab' => 'widgets',
            ],

            // ─── Extensions ──────────────────────────────────
            'extensions' => [
                'label' => __('Extensions', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Extensions\ExtensionsModule::class,
                'desc' => __('Sticky elements, motion effects, scroll animations, custom CSS/JS, display conditions.', 'super-elem-pro'),
                'icon' => 'dashicons-superhero',
                'tab' => 'extensions',
            ],

            // ─── WooCommerce (auto-disabled if not installed) ─
            'woocommerce' => [
                'label' => __('WooCommerce Builder', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\WooCommerce\WooCommerce::class,
                'desc' => __('WooCommerce product pages, cart, checkout, quick view, wishlist, and filters.', 'super-elem-pro'),
                'icon' => 'dashicons-cart',
                'tab' => 'woocommerce',
                'requires' => 'woocommerce/woocommerce.php',
            ],

            // ─── Mega Menu ────────────────────────────────────
            'mega_menu' => [
                'label' => __('Mega Menu', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\MegaMenu\MegaMenu::class,
                'desc' => __('Build advanced mega menus with Elementor templates.', 'super-elem-pro'),
                'icon' => 'dashicons-menu',
                'tab' => 'extensions',
            ],

            // ─── Marketing ────────────────────────────────────
            'marketing' => [
                'label' => __('Marketing Tools', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Marketing\Marketing::class,
                'desc' => __('Social feeds, floating action buttons, urgency elements, and countdown timers.', 'super-elem-pro'),
                'icon' => 'dashicons-megaphone',
                'tab' => 'widgets',
            ],

            // ─── Performance ──────────────────────────────────
            'performance' => [
                'label' => __('Performance', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Performance\Performance::class,
                'desc' => __('Conditional asset loading, core web vitals optimizer, and more.', 'super-elem-pro'),
                'icon' => 'dashicons-performance',
                'tab' => 'extensions',
            ],

            // ─── Developer Tools ──────────────────────────────
            'developer' => [
                'label' => __('Developer Tools', 'super-elem-pro'),
                'class' => \SuperElemPro\Modules\Developer\Developer::class,
                'desc' => __('Role manager, global CSS/JS, template import/export, accessibility tools.', 'super-elem-pro'),
                'icon' => 'dashicons-code-standards',
                'tab' => 'developer',
            ],
        ];
    }

    /**
     * Load all enabled modules.
     * Skips modules whose required plugins are not active.
     *
     * @return void
     */
    public function init(): void
    {

        foreach ($this->modules as $key => $module) {

            // Skip if module is disabled in settings
            if (!$this->is_module_enabled($key)) {
                continue;
            }

            // Skip if required plugin is not active
            if (!empty($module['requires']) && !$this->is_plugin_active($module['requires'])) {
                continue;
            }

            // Check if class exists before instantiating
            if (!class_exists($module['class'])) {
                continue;
            }

            // Instantiate the module
            try {
                new $module['class']();
            } catch (\Throwable $e) {
                // Log error but don't crash the whole plugin
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log(
                        sprintf(
                            '[Super Elem Pro] Error loading module "%s": %s',
                            $key,
                            $e->getMessage()
                        )
                    );
                }
            }
        }
    }

    /**
     * Check if a specific module is enabled.
     *
     * @param string $key Module key.
     * @return bool
     */
    public function is_module_enabled(string $key): bool
    {
        return !empty($this->enabled_modules[$key]);
    }

    /**
     * Check if a WordPress plugin is active.
     *
     * @param string $plugin Plugin file (e.g. 'woocommerce/woocommerce.php').
     * @return bool
     */
    private function is_plugin_active(string $plugin): bool
    {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return is_plugin_active($plugin);
    }

    /**
     * Get all registered modules.
     *
     * @return array
     */
    public function get_modules(): array
    {
        return $this->modules;
    }

    /**
     * Get all enabled module keys.
     *
     * @return array
     */
    public function get_enabled_modules(): array
    {
        return $this->enabled_modules;
    }
}