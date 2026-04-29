<?php
/**
 * Sticky Elements Extension
 *
 * Makes any Elementor section or widget sticky on scroll.
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
 * Class StickyElements
 */
class StickyElements
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'add_sticky_controls'], 10, 2);
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'add_sticky_controls'], 10, 2);
        add_action('elementor/frontend/section/before_render', [$this, 'before_render']);
        add_action('elementor/frontend/container/before_render', [$this, 'before_render']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Add sticky controls to sections/containers.
     *
     * @param Element_Base $element
     * @return void
     */
    public function add_sticky_controls(Element_Base $element): void
    {

        $element->start_controls_section(
            'sep_sticky_section',
            [
                'label' => __('Sticky', 'super-elem-pro') . ' <span class="sep-pro-label">SEP</span>',
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'sep_sticky',
            [
                'label' => __('Sticky', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $element->add_control(
            'sep_sticky_offset',
            [
                'label' => __('Offset from Top (px)', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'condition' => ['sep_sticky' => 'yes'],
            ]
        );

        $element->add_control(
            'sep_sticky_on',
            [
                'label' => __('Sticky On', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['desktop', 'tablet', 'mobile'],
                'options' => [
                    'desktop' => __('Desktop', 'super-elem-pro'),
                    'tablet' => __('Tablet', 'super-elem-pro'),
                    'mobile' => __('Mobile', 'super-elem-pro'),
                ],
                'condition' => ['sep_sticky' => 'yes'],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Add data attributes to sticky elements before render.
     *
     * @param Element_Base $element
     * @return void
     */
    public function before_render(Element_Base $element): void
    {

        $settings = $element->get_settings_for_display();

        if ('yes' !== ($settings['sep_sticky'] ?? '')) {
            return;
        }

        $offset = absint($settings['sep_sticky_offset'] ?? 0);
        $stick_on = $settings['sep_sticky_on'] ?? ['desktop', 'tablet', 'mobile'];

        $element->add_render_attribute('_wrapper', [
            'data-sep-sticky' => 'yes',
            'data-sep-sticky-offset' => $offset,
            'data-sep-sticky-on' => wp_json_encode($stick_on),
        ]);
    }

    /**
     * Enqueue sticky JS when needed.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        wp_register_script(
            'sep-sticky',
            SEP_ASSETS_URL . 'js/sticky.js',
            ['jquery'],
            SEP_VERSION,
            true
        );
    }
}