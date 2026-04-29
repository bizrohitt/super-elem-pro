<?php
namespace SuperElemPro\Modules\Extensions;

if (!defined('ABSPATH'))
    exit;

/**
 * Mouse Effects Extension
 * 
 * @since 1.0.0
 */
class MouseEffects
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
            'sep_mouse_effects_heading',
            [
                'label' => __('Mouse Effects', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $element->add_control(
            'sep_mouse_track',
            [
                'label' => __('Mouse Track', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_mouse_track_speed',
            [
                'label' => __('Track Speed', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'condition' => [
                    'sep_mouse_track' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_tilt_effect',
            [
                'label' => __('3D Tilt on Hover', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'sep_tilt_intensity',
            [
                'label' => __('Tilt Intensity', 'super-elem-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'condition' => [
                    'sep_tilt_effect' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'sep-mouse-effects',
            SEP_ASSETS_URL . 'js/mouse-effects.js',
            ['jquery'],
            SEP_VERSION,
            true
        );
    }
}