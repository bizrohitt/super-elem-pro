<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
}

class PostTitle extends Widget_Base
{

    public function get_name(): string
    {
        return 'sep-post-title';
    }
    public function get_title(): string
    {
        return __('Post Title', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-post-title';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }
    public function get_keywords(): array
    {
        return ['post', 'title', 'heading', 'single'];
    }

    protected function register_controls(): void
    {

        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);

        $this->add_control('header_size', [
            'label' => __('HTML Tag', 'super-elem-pro'),
            'type' => Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'div' => 'div', 'span' => 'span', 'p' => 'p'],
        ]);

        $this->add_control('link', [
            'label' => __('Link to post', 'super-elem-pro'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'super-elem-pro'),
            'label_off' => __('No', 'super-elem-pro'),
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', ['label' => __('Style', 'super-elem-pro'), 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .sep-post-title',
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'super-elem-pro'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .sep-post-title, {{WRAPPER}} .sep-post-title a' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $tag = $settings['header_size'];
        $title = get_the_title();
        $link = $settings['link'];

        echo '<' . esc_attr($tag) . ' class="sep-post-title">';
        if ('yes' === $link) {
            echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html($title) . '</a>';
        } else {
            echo esc_html($title);
        }
        echo '</' . esc_attr($tag) . '>';
    }
}