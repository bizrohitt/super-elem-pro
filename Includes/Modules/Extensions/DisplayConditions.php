<?php
/**
 * Display Conditions Extension
 *
 * Show or hide any Elementor element based on
 * user role, login status, device, date, and more.
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
 * Class DisplayConditions
 */
class DisplayConditions
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'add_controls'], 10);
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'add_controls'], 10);
        add_action('elementor/element/common/_section_responsive/after_section_end', [$this, 'add_controls'], 10);
        add_action('elementor/frontend/before_render', [$this, 'maybe_hide_element']);
    }

    /**
     * Add display condition controls to elements.
     *
     * @param Element_Base $element
     * @return void
     */
    public function add_controls(Element_Base $element): void
    {

        $element->start_controls_section(
            'sep_display_conditions',
            [
                'label' => __('Display Conditions', 'super-elem-pro') . ' <span class="sep-pro-label">SEP</span>',
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'sep_enable_conditions',
            [
                'label' => __('Enable Conditions', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $element->add_control(
            'sep_condition_login',
            [
                'label' => __('User Login Status', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'all' => __('All Users', 'super-elem-pro'),
                    'logged_in' => __('Logged In Users Only', 'super-elem-pro'),
                    'logged_out' => __('Logged Out Users Only', 'super-elem-pro'),
                ],
                'condition' => ['sep_enable_conditions' => 'yes'],
            ]
        );

        $element->add_control(
            'sep_condition_roles',
            [
                'label' => __('User Roles', 'super-elem-pro'),
                'description' => __('Show only to selected roles. Leave empty for all.', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_roles(),
                'condition' => [
                    'sep_enable_conditions' => 'yes',
                    'sep_condition_login' => 'logged_in',
                ],
            ]
        );

        $element->add_control(
            'sep_condition_date_from',
            [
                'label' => __('Show From Date', 'super-elem-pro'),
                'type' => Controls_Manager::DATE_TIME,
                'description' => __('Leave empty to always show.', 'super-elem-pro'),
                'condition' => ['sep_enable_conditions' => 'yes'],
            ]
        );

        $element->add_control(
            'sep_condition_date_to',
            [
                'label' => __('Hide After Date', 'super-elem-pro'),
                'type' => Controls_Manager::DATE_TIME,
                'condition' => ['sep_enable_conditions' => 'yes'],
            ]
        );

        $element->add_control(
            'sep_condition_device',
            [
                'label' => __('Show On Devices', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['desktop', 'tablet', 'mobile'],
                'options' => [
                    'desktop' => __('Desktop', 'super-elem-pro'),
                    'tablet' => __('Tablet', 'super-elem-pro'),
                    'mobile' => __('Mobile', 'super-elem-pro'),
                ],
                'condition' => ['sep_enable_conditions' => 'yes'],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Check conditions and hide element if needed.
     *
     * @param Element_Base $element
     * @return void
     */
    public function maybe_hide_element(Element_Base $element): void
    {

        $settings = $element->get_settings_for_display();

        if ('yes' !== ($settings['sep_enable_conditions'] ?? '')) {
            return;
        }

        if (!$this->passes_conditions($settings)) {
            // Prevent element from rendering
            add_filter('elementor/frontend/before_render', function ($should_render, $el) use ($element) {
                return $el === $element ? false : $should_render;
            }, 10, 2);

            // CSS-based hiding as fallback
            $element->add_render_attribute('_wrapper', 'style', 'display:none!important;');
        }
    }

    /**
     * Check if all conditions pass.
     *
     * @param array $settings Element settings.
     * @return bool
     */
    private function passes_conditions(array $settings): bool
    {

        // Login status check
        $login_condition = $settings['sep_condition_login'] ?? 'all';

        if ('logged_in' === $login_condition && !is_user_logged_in()) {
            return false;
        }

        if ('logged_out' === $login_condition && is_user_logged_in()) {
            return false;
        }

        // Role check
        $roles = $settings['sep_condition_roles'] ?? [];
        if (!empty($roles) && is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_roles = (array) $user->roles;
            if (empty(array_intersect($roles, $user_roles))) {
                return false;
            }
        }

        // Date range check
        $date_from = $settings['sep_condition_date_from'] ?? '';
        $date_to = $settings['sep_condition_date_to'] ?? '';
        $now = current_time('timestamp');

        if ($date_from && strtotime($date_from) > $now) {
            return false;
        }

        if ($date_to && strtotime($date_to) < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get all WordPress user roles.
     *
     * @return array
     */
    private function get_roles(): array
    {
        global $wp_roles;
        $roles = [];
        foreach ($wp_roles->roles as $key => $role) {
            $roles[$key] = $role['name'];
        }
        return $roles;
    }
}