<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) {
    exit;
}

class NavMenu extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-nav-menu';
    }
    public function get_title(): string
    {
        return __('Nav Menu', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-nav-menu';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    private function get_menus(): array
    {
        $menus = wp_get_nav_menus();
        $options = ['' => __('— Select Menu —', 'super-elem-pro')];
        foreach ($menus as $menu) {
            $options[$menu->slug] = $menu->name;
        }
        return $options;
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);
        $this->add_control('menu', [
            'label' => __('Select Menu', 'super-elem-pro'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_menus(),
        ]);
        $this->add_control('layout', [
            'label' => __('Layout', 'super-elem-pro'),
            'type' => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'super-elem-pro'),
                'vertical' => __('Vertical', 'super-elem-pro'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['menu'])) {
            echo '<p>' . esc_html__('Please select a menu.', 'super-elem-pro') . '</p>';
            return;
        }
        $layout = $settings['layout'] ?? 'horizontal';
        echo '<nav class="sep-nav-menu sep-nav-' . esc_attr($layout) . '">';
        wp_nav_menu([
            'menu' => $settings['menu'],
            'menu_class' => 'sep-menu-list',
            'container' => false,
            'fallback_cb' => false,
        ]);
        echo '</nav>';
    }
}