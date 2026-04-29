<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) {
    exit;
}

class PostFeaturedImage extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-post-featured-image';
    }
    public function get_title(): string
    {
        return __('Featured Image', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-featured-image';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);
        $this->add_control('image_size', [
            'label' => __('Image Size', 'super-elem-pro'),
            'type' => Controls_Manager::SELECT,
            'default' => 'full',
            'options' => [
                'thumbnail' => __('Thumbnail', 'super-elem-pro'),
                'medium' => __('Medium', 'super-elem-pro'),
                'large' => __('Large', 'super-elem-pro'),
                'full' => __('Full', 'super-elem-pro'),
            ],
        ]);
        $this->add_control('link_to_post', [
            'label' => __('Link to post', 'super-elem-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        if (!has_post_thumbnail()) {
            return;
        }
        $size = $settings['image_size'] ?? 'full';
        $link = $settings['link_to_post'];
        echo '<div class="sep-featured-image">';
        if ('yes' === $link) {
            echo '<a href="' . esc_url(get_permalink()) . '">';
        }
        the_post_thumbnail($size);
        if ('yes' === $link) {
            echo '</a>';
        }
        echo '</div>';
    }
}