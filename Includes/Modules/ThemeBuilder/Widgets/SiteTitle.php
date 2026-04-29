<?php
/**
 * Site Title Widget
 *
 * @package SuperElemPro\Modules\ThemeBuilder\Widgets
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\ThemeBuilder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SiteTitle
 */
class SiteTitle extends Widget_Base
{

    public function get_name(): string
    {
        return 'sep-site-title';
    }

    public function get_title(): string
    {
        return __('Site Title', 'super-elem-pro');
    }

    public function get_icon(): string
    {
        return 'eicon-site-title';
    }

    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    public function get_keywords(): array
    {
        return ['site', 'title', 'name', 'brand', 'logo'];
    }

    protected function register_controls(): void
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => __('HTML Tag', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => __('Link', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'home',
                'options' => [
                    'home' => __('Home URL', 'super-elem-pro'),
                    'none' => __('None', 'super-elem-pro'),
                    'custom' => __('Custom URL', 'super-elem-pro'),
                ],
            ]
        );

        $this->add_control(
            'custom_link',
            [
                'label' => __('Custom URL', 'super-elem-pro'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        // Style tab
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'super-elem-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .sep-site-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'super-elem-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sep-site-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .sep-site-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {

        $settings = $this->get_settings_for_display();
        $tag = $settings['header_size'];
        $site_title = get_bloginfo('name');
        $link_to = $settings['link_to'];

        // Build the link
        $link_url = '';
        if ('home' === $link_to) {
            $link_url = esc_url(home_url('/'));
        } elseif ('custom' === $link_to && !empty($settings['custom_link']['url'])) {
            $link_url = esc_url($settings['custom_link']['url']);
        }

        // Render
        echo '<' . esc_attr($tag) . ' class="sep-site-title">';

        if ($link_url) {
            echo '<a href="' . $link_url . '">';
        }

        echo esc_html($site_title);

        if ($link_url) {
            echo '</a>';
        }

        echo '</' . esc_attr($tag) . '>';
    }
}