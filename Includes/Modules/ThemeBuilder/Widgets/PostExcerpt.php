<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
if (!defined('ABSPATH')) {
    exit;
}

class PostExcerpt extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-post-excerpt';
    }
    public function get_title(): string
    {
        return __('Post Excerpt', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-post-excerpt';
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
        echo '<div class="sep-post-excerpt">' . wp_kses_post(get_the_excerpt()) . '</div>';
    }
}