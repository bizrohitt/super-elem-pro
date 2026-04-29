<?php
namespace SuperElemPro\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin Core Class
 *
 * The main class responsible for initializing Super Elem Pro.
 *
 * @since 1.0.0
 */
final class Plugin
{

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     * @var Plugin
     */
    private static $_instance = null;

    /**
     * Loader
     *
     * @since 1.0.0
     * @access private
     * @var Loader
     */
    private $loader;

    /**
     * Instance
     *
     * @since 1.0.0
     * @access public
     * @static
     * @return Plugin
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct()
    {
        $this->init_components();
        $this->init_modules();

        do_action('super_elem_pro/loaded');
    }

    /**
     * Initialize Components
     *
     * @since 1.0.0
     * @access private
     */
    private function init_components()
    {
        // Compatibility checks
        new Compatibility();

        // Asset manager
        new Assets();

        // Admin panel
        if (is_admin()) {
            new \SuperElemPro\Admin\Admin();
        }
    }

    /**
     * Initialize Modules
     *
     * @since 1.0.0
     * @access private
     */
    private function init_modules()
    {
        $this->loader = new Loader();
        $this->loader->init();
    }

    /**
     * Get Loader
     *
     * @since 1.0.0
     * @access public
     * @return Loader
     */
    public function get_loader()
    {
        return $this->loader;
    }
}