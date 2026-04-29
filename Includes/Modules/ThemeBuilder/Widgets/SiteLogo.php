<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) {
    exit;
}

class SiteLogo extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-site-logo';
    }
    public function get_title(): string
    {
        return __('Site Logo', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-site-logo';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);
        $this->add_control('width', ['label' => __('Width', 'super-elem-pro'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'range' => ['px' => ['min' => 10, 'max' => 500]], 'selectors' => ['{{WRAPPER}} .sep-site-logo img' => 'width: {{SIZE}}{{UNIT}};']]);
        $this->add_control('link_to_home', ['label' => __('Link to Home', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $logo_id = get_theme_mod('custom_logo');
        $link = $settings['link_to_home'] ?? 'yes';
        echo '<div class="sep-site-logo">';
        if ('yes' === $link) {
            echo '<a href="' . esc_url(home_url('/')) . '">';
        }
        if ($logo_id) {
            echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => esc_attr(get_bloginfo('name'))]);
        } else {
            echo '<span class="sep-site-name">' . esc_html(get_bloginfo('name')) . '</span>';
        }
        if ('yes' === $link) {
            echo '</a>';
        }
        echo '</div>';
    }
}