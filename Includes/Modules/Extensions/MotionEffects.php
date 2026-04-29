<?php
/**
 * Motion Effects Extension
 *
 * Adds entrance animations, scroll animations,
 * and parallax effects to Elementor elements.
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
 * Class MotionEffects
 */
class MotionEffects
{

    /**
     * Available entrance animations.
     */
    private array $entrance_animations = [
        '' => __('None', 'super-elem-pro'),
        'fadeIn' => 'Fade In',
        'fadeInUp' => 'Fade In Up',
        'fadeInDown' => 'Fade In Down',
        'fadeInLeft' => 'Fade In Left',
        'fadeInRight' => 'Fade In Right',
        'slideInUp' => 'Slide In Up',
        'slideInDown' => 'Slide In Down',
        'slideInLeft' => 'Slide In Left',
        'slideInRight' => 'Slide In Right',
        'zoomIn' => 'Zoom In',
        'zoomInUp' => 'Zoom In Up',
        'zoomInDown' => 'Zoom In Down',
        'bounceIn' => 'Bounce In',
        'bounceInUp' => 'Bounce In Up',
        'rotateIn' => 'Rotate In',
        'flipInX' => 'Flip In X',
        'flipInY' => 'Flip In Y',
        'lightSpeedInRight' => 'Light Speed Right',
        'rollIn' => 'Roll In',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'add_motion_controls'], 10);
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'add_motion_controls'], 10);
        add_action('elementor/element/common/_section_responsive/after_section_end', [$this, 'add_motion_controls'], 10);
        add_action('elementor/frontend/before_render', [$this, 'before_render']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Add motion effect controls to elements.
     *
     * @param Element_Base $element
     * @return void
     */
    public function add_motion_controls(Element_Base $element): void
    {

        $element->start_controls_section(
            'sep_motion_effects_section',
            [
                'label' => __('Motion Effects', 'super-elem-pro') . ' <span class="sep-pro-label">SEP</span>',
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Entrance Animation
        $element->add_control(
            'sep_entrance_animation',
            [
                'label' => __('Entrance Animation', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->entrance_animations,
            ]
        );

        $element->add_control(
            'sep_animation_duration',
            [
                'label' => __('Duration (ms)', 'super-elem-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 800],
                'range' => ['px' => ['min' => 100, 'max' => 3000, 'step' => 100]],
                'condition' => ['sep_entrance_animation!' => ''],
                'selectors' => [
                    '{{WRAPPER}}' => 'animation-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $element->add_control(
            'sep_animation_delay',
            [
                'label' => __('Delay (ms)', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'condition' => ['sep_entrance_animation!' => ''],
            ]
        );

        // Parallax
        $element->add_control(
            'sep_parallax',
            [
                'label' => __('Parallax Effect', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $element->add_control(
            'sep_parallax_speed',
            [
                'label' => __('Parallax Speed', 'super-elem-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 0.3],
                'range' => ['px' => ['min' => -1, 'max' => 1, 'step' => 0.1]],
                'condition' => ['sep_parallax' => 'yes'],
            ]
        );

        // Scroll-based opacity
        $element->add_control(
            'sep_scroll_effects',
            [
                'label' => __('Scroll Effects', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $element->add_control(
            'sep_scroll_effect_type',
            [
                'label' => __('Effect', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'super-elem-pro'),
                    'scale' => __('Scale', 'super-elem-pro'),
                    'rotate' => __('Rotate', 'super-elem-pro'),
                    'translate_y' => __('Vertical Scroll', 'super-elem-pro'),
                    'translate_x' => __('Horizontal Scroll', 'super-elem-pro'),
                ],
                'default' => 'fade',
                'condition' => ['sep_scroll_effects' => 'yes'],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Add data attributes to elements before render.
     *
     * @param Element_Base $element
     * @return void
     */
    public function before_render(Element_Base $element): void
    {

        $settings = $element->get_settings_for_display();

        // Entrance animation
        $animation = $settings['sep_entrance_animation'] ?? '';
        if ($animation) {
            $delay = absint($settings['sep_animation_delay'] ?? 0);
            $element->add_render_attribute('_wrapper', [
                'data-sep-animation' => $animation,
                'data-sep-animation-delay' => $delay,
                'class' => 'sep-animate-ready',
            ]);
            wp_enqueue_script('sep-animations');
            wp_enqueue_style('sep-animations');
        }

        // Parallax
        if ('yes' === ($settings['sep_parallax'] ?? '')) {
            $speed = $settings['sep_parallax_speed']['size'] ?? 0.3;
            $element->add_render_attribute('_wrapper', [
                'data-sep-parallax' => 'yes',
                'data-sep-parallax-speed' => $speed,
            ]);
            wp_enqueue_script('sep-parallax');
        }

        // Scroll effects
        if ('yes' === ($settings['sep_scroll_effects'] ?? '')) {
            $effect = $settings['sep_scroll_effect_type'] ?? 'fade';
            $element->add_render_attribute('_wrapper', [
                'data-sep-scroll-effect' => $effect,
            ]);
            wp_enqueue_script('sep-scroll-effects');
        }
    }

    /**
     * Register animation assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {

        // Animate.css for entrance animations
        wp_register_style(
            'sep-animations',
            'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
            [],
            '4.1.1'
        );

        // Our custom animation observer script
        wp_register_script(
            'sep-animations',
            SEP_ASSETS_URL . 'js/animations.js',
            ['jquery'],
            SEP_VERSION,
            true
        );

        // Parallax script
        wp_register_script(
            'sep-parallax',
            SEP_ASSETS_URL . 'js/parallax.js',
            ['jquery'],
            SEP_VERSION,
            true
        );

        // Scroll effects script
        wp_register_script(
            'sep-scroll-effects',
            SEP_ASSETS_URL . 'js/scroll-effects.js',
            ['jquery'],
            SEP_VERSION,
            true
        );
    }
}