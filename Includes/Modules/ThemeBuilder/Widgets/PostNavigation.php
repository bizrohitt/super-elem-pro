<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
if (!defined('ABSPATH')) {
    exit;
}

class PostNavigation extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-post-navigation';
    }
    public function get_title(): string
    {
        return __('Post Navigation', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-post-navigation';
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
        echo '<div class="sep-post-navigation">';
        the_post_navigation([
            'prev_text' => '&laquo; %title',
            'next_text' => '%title &raquo;',
        ]);
        echo '</div>';
    }
}