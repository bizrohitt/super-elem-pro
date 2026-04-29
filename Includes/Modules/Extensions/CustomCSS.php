<?php
/**
 * Custom CSS Extension
 *
 * Adds a "Custom CSS" tab to every Elementor widget,
 * section, and column — just like Elementor Pro.
 *
 * @package SuperElemPro\Modules\Extensions
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class CustomCSS
 */
class CustomCSS
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Add custom CSS control to every element
        add_action('elementor/element/after_section_end', [$this, 'add_controls'], 10, 2);

        // Render the custom CSS on the frontend
        add_action('elementor/element/before_render', [$this, 'render_css']);

        // Also render in the editor so it's live
        add_action('elementor/editor/after_enqueue_styles', [$this, 'editor_styles']);
    }

    /**
     * Add the Custom CSS section to all Elementor elements.
     *
     * @param Element_Base $element  Current element.
     * @param string       $section_id Section ID.
     * @return void
     */
    public function add_controls(Element_Base $element, string $section_id): void
    {

        // Only add once, on the last section
        if ('_section_responsive' !== $section_id) {
            return;
        }

        $element->start_controls_section(
            'sep_custom_css_section',
            [
                'label' => __('Custom CSS', 'super-elem-pro') . ' <span class="sep-pro-label">SEP</span>',
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'sep_custom_css',
            [
                'label' => __('Custom CSS', 'super-elem-pro'),
                'type' => Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 10,
                'description' => __('Use <code>selector</code> to target this element. Example: <code>selector { color: red; }</code>', 'super-elem-pro'),
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Inject custom CSS into the element before it renders.
     *
     * @param Element_Base $element Current element.
     * @return void
     */
    public function render_css(Element_Base $element): void
    {

        $custom_css = $element->get_settings('sep_custom_css');

        if (empty($custom_css)) {
            return;
        }

        $element_id = $element->get_id();

        // Replace "selector" placeholder with actual element selector
        $css = str_replace(
            'selector',
            '.elementor-element-' . $element_id,
            $custom_css
        );

        // Sanitize: remove dangerous properties
        $css = wp_strip_all_tags($css);

        // Output inline style
        echo '<style id="sep-custom-css-' . esc_attr($element_id) . '">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $css;
        echo '</style>';
    }

    /**
     * Add editor-specific styles to make the CSS tab look good.
     *
     * @return void
     */
    public function editor_styles(): void
    {
        // Inline style for the "SEP" label badge in the tab
        wp_add_inline_style('elementor-editor', '
            .sep-pro-label {
                background: #7c3aed;
                color: #fff;
                font-size: 9px;
                padding: 1px 4px;
                border-radius: 3px;
                vertical-align: middle;
                margin-left: 4px;
                font-weight: 600;
            }
        ');
    }
}