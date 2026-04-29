<?php
namespace SuperElemPro\Modules\Extensions;

if (!defined('ABSPATH'))
    exit;

/**
 * Scroll Animations Extension
 * 
 * @since 1.0.0
 */
class ScrollAnimations
{

    public function __construct()
    {
        add_action('elementor/element/section/section_effects/after_section_start', [$this, 'add_controls']);
        add_action('elementor/element/common/_section_style/after_section_start', [$this, 'add_controls']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_controls($element)
    {
        $element->add_control(
            'sep_scroll_animation_heading',
            [
                'label' => __('Scroll Animations', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $element->add_control(
            'sep_scroll_animation',
            [
                'label' => __('Entrance Animation', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __('None', 'super-elem-pro'),
                    'fadeIn' => __('Fade In', 'super-elem-pro'),
                    'fadeInUp' => __('Fade In Up', 'super-elem-pro'),
                    'fadeInDown' => __('Fade In Down', 'super-elem-pro'),
                    'fadeInLeft' => __('Fade In Left', 'super-elem-pro'),
                    'fadeInRight' => __('Fade In Right', 'super-elem-pro'),
                    'slideInUp' => __('Slide In Up', 'super-elem-pro'),
                    'slideInDown' => __('Slide In Down', 'super-elem-pro'),
                    'slideInLeft' => __('Slide In Left', 'super-elem-pro'),
                    'slideInRight' => __('Slide In Right', 'super-elem-pro'),
                    'zoomIn' => __('Zoom In', 'super-elem-pro'),
                    'bounceIn' => __('Bounce In', 'super-elem-pro'),
                    'rotateIn' => __('Rotate In', 'super-elem-pro'),
                ],
                'default' => '',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_animation_delay',
            [
                'label' => __('Animation Delay (ms)', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'condition' => [
                    'sep_scroll_animation!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_animation_duration',
            [
                'label' => __('Animation Duration (ms)', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 100,
                'max' => 5000,
                'step' => 100,
                'condition' => [
                    'sep_scroll_animation!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_animation_offset',
            [
                'label' => __('Trigger Offset (%)', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 80,
                ],
                'condition' => [
                    'sep_scroll_animation!' => '',
                ],
                'frontend_available' => true,
            ]
        );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'sep-scroll-animations',
            SEP_ASSETS_URL . 'js/scroll-animations.js',
            ['jquery'],
            SEP_VERSION,
            true
        );
    }
}