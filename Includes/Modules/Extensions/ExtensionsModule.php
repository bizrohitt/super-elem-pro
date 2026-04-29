<?php
/**
 * Extensions Module
 *
 * Loads all Elementor extensions (sticky, motion effects,
 * custom CSS/JS, display conditions, etc.)
 *
 * @package SuperElemPro\Modules\Extensions
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ExtensionsModule
 */
class ExtensionsModule
{

    /**
     * Constructor — load all extensions.
     */
    public function __construct()
    {
        new StickyElements();
        new MotionEffects();
        new CustomCSS();
        new CustomJS();
        new DisplayConditions();
    }
}