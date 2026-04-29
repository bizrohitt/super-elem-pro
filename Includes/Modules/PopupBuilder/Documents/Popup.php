<?php
/**
 * Popup Document Type
 *
 * @package SuperElemPro\Modules\PopupBuilder\Documents
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\PopupBuilder\Documents;

use Elementor\Core\Base\Document;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Popup
 */
class Popup extends Document
{

    /**
     * @return array
     */
    public static function get_properties(): array
    {
        $properties = parent::get_properties();
        $properties['support_kit'] = true;
        $properties['show_in_finder'] = true;
        $properties['support_conditions'] = true;
        return $properties;
    }

    /**
     * @return string
     */
    public static function get_title(): string
    {
        return __('SEP Popup', 'super-elem-pro');
    }

    /**
     * Register document controls — trigger settings live here.
     *
     * @return void
     */
    protected function register_controls(): void
    {
        parent::register_controls();

        // ─── Trigger Settings ────────────────────────────────
        $this->start_controls_section(
            'sep_popup_triggers',
            [
                'label' => __('Popup Triggers', 'super-elem-pro'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'trigger_on_load',
            [
                'label' => __('On Page Load', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'trigger_time_delay',
            [
                'label' => __('Time Delay (seconds)', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 0,
                'condition' => ['trigger_on_load' => 'yes'],
            ]
        );

        $this->add_control(
            'trigger_exit_intent',
            [
                'label' => __('Exit Intent', 'super-elem-pro'),
                'description' => __('Show when visitor moves mouse toward browser close.', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'trigger_scroll',
            [
                'label' => __('On Scroll', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'trigger_scroll_depth',
            [
                'label' => __('Scroll Depth (%)', 'super-elem-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 50],
                'range' => ['px' => ['min' => 1, 'max' => 100]],
                'condition' => ['trigger_scroll' => 'yes'],
            ]
        );

        $this->add_control(
            'trigger_click',
            [
                'label' => __('On Click', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'trigger_click_selector',
            [
                'label' => __('Click Selector (CSS)', 'super-elem-pro'),
                'description' => __('CSS selector of the element to click. Example: .my-button', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '.my-button',
                'condition' => ['trigger_click' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // ─── Display Settings ─────────────────────────────────
        $this->start_controls_section(
            'sep_popup_display',
            [
                'label' => __('Display Settings', 'super-elem-pro'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'popup_frequency',
            [
                'label' => __('Show popup', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'always',
                'options' => [
                    'always' => __('Every time', 'super-elem-pro'),
                    'once' => __('Once per session', 'super-elem-pro'),
                    'daily' => __('Once per day', 'super-elem-pro'),
                    'weekly' => __('Once per week', 'super-elem-pro'),
                ],
            ]
        );

        $this->add_control(
            'close_overlay',
            [
                'label' => __('Close on overlay click', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'close_esc',
            [
                'label' => __('Close on ESC key', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'popup_animation',
            [
                'label' => __('Entry Animation', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => __('Fade', 'super-elem-pro'),
                    'slide-up' => __('Slide Up', 'super-elem-pro'),
                    'zoom-in' => __('Zoom In', 'super-elem-pro'),
                    'none' => __('None', 'super-elem-pro'),
                ],
            ]
        );

        $this->end_controls_section();
    }
}