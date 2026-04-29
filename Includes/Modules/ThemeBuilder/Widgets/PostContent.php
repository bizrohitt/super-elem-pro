<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
if (!defined('ABSPATH')) {
    exit;
}

class PostContent extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-post-content';
    }
    public function get_title(): string
    {
        return __('Post Content', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-post-content';
    }
    public function get_categories(): array
    {
        return ['sep-theme-elements'];
    }

    protected function register_controls(): void
    {
    }

    protected function render(): void
    {
        // In editor show placeholder, on frontend show actual content
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            echo '<div class="sep-post-content-placeholder">';
            echo '<p>' . esc_html__('Post content will appear here.', 'super-elem-pro') . '</p>';
            echo '</div>';
            return;
        }
        echo '<div class="sep-post-content">';
        the_content();
        echo '</div>';
    }
}