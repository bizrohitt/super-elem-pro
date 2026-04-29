<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) {
    exit;
}

class PostInfo extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-post-info';
    }
    public function get_title(): string
    {
        return __('Post Info', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-post-info';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Settings', 'super-elem-pro')]);
        $this->add_control('show_date', ['label' => __('Date', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('show_author', ['label' => __('Author', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('show_categories', ['label' => __('Categories', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('show_tags', ['label' => __('Tags', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('show_comments', ['label' => __('Comments Count', 'super-elem-pro'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        echo '<div class="sep-post-info">';
        if ('yes' === ($settings['show_date'] ?? '')) {
            echo '<span class="sep-post-date">' . esc_html(get_the_date()) . '</span>';
        }
        if ('yes' === ($settings['show_author'] ?? '')) {
            echo '<span class="sep-post-author">' . esc_html(get_the_author()) . '</span>';
        }
        if ('yes' === ($settings['show_categories'] ?? '')) {
            $cats = get_the_category();
            if ($cats) {
                echo '<span class="sep-post-categories">';
                foreach ($cats as $cat) {
                    echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a> ';
                }
                echo '</span>';
            }
        }
        if ('yes' === ($settings['show_tags'] ?? '')) {
            $tags = get_the_tags();
            if ($tags) {
                echo '<span class="sep-post-tags">';
                foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a> ';
                }
                echo '</span>';
            }
        }
        if ('yes' === ($settings['show_comments'] ?? '')) {
            echo '<span class="sep-post-comments">' . esc_html(get_comments_number()) . ' ' . esc_html__('Comments', 'super-elem-pro') . '</span>';
        }
        echo '</div>';
    }
}