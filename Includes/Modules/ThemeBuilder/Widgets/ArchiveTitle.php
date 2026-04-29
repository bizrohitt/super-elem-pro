<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) {
    exit;
}

class ArchiveTitle extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-archive-title';
    }
    public function get_title(): string
    {
        return __('Archive Title', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-archive-title';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);
        $this->add_control('tag', [
            'label' => __('HTML Tag', 'super-elem-pro'),
            'type' => Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'div' => 'div', 'p' => 'p'],
        ]);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $tag = $settings['tag'] ?? 'h1';
        $title = get_the_archive_title();
        echo '<' . esc_attr($tag) . ' class="sep-archive-title">' . esc_html($title) . '</' . esc_attr($tag) . '>';
    }
}