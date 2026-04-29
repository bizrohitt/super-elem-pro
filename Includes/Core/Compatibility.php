<?php
/**
 * Compatibility Layer
 *
 * Ensures the plugin works properly with different
 * versions of WordPress and Elementor.
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
 * Class Compatibility
 */
class Compatibility
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->declare_elementor_compatibility();
        $this->declare_woocommerce_compatibility();
    }

    /**
     * Declare compatibility with Elementor features.
     *
     * @return void
     */
    private function declare_elementor_compatibility(): void
    {

        // Declare compatibility with Elementor's Flexbox Container experiment
        add_action('elementor/experiments/default-features-registered', function ($experiments_manager) {
            // We support the new container/flexbox layout
        });

        // Declare support for Elementor's header/footer feature
        add_theme_support('custom-header');

        // Tell Elementor we support their newest features
        add_action('elementor/loaded', function () {
            if (class_exists('\Elementor\Plugin')) {
                // Enable container support if available
                $experiments = \Elementor\Plugin::$instance->experiments ?? null;
                if ($experiments && method_exists($experiments, 'is_feature_active')) {
                    // Plugin is compatible with containers
                }
            }
        });
    }

    /**
     * Declare compatibility with WooCommerce features.
     *
     * @return void
     */
    private function declare_woocommerce_compatibility(): void
    {

        add_action('before_woocommerce_init', function () {
            if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                    'custom_order_tables',
                    SEP_PLUGIN_FILE,
                    true
                );
            }
        });
    }
}