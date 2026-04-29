<?php
namespace SuperElemPro\Modules\ThemeBuilder\Widgets;
use Elementor\Widget_Base;
if (!defined('ABSPATH')) {
    exit;
}

class Breadcrumbs extends Widget_Base
{
    public function get_name(): string
    {
        return 'sep-breadcrumbs';
    }
    public function get_title(): string
    {
        return __('Breadcrumbs', 'super-elem-pro');
    }
    public function get_icon(): string
    {
        return 'eicon-product-breadcrumbs';
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
        echo '<nav class="sep-breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'super-elem-pro') . '">';
        echo '<a href="' . esc_url(home_url()) . '">' . esc_html__('Home', 'super-elem-pro') . '</a>';
        if (is_singular()) {
            echo ' &rsaquo; <span>' . esc_html(get_the_title()) . '</span>';
        } elseif (is_archive()) {
            echo ' &rsaquo; <span>' . esc_html(get_the_archive_title()) . '</span>';
        } elseif (is_search()) {
            echo ' &rsaquo; <span>' . esc_html__('Search Results', 'super-elem-pro') . '</span>';
        } elseif (is_404()) {
            echo ' &rsaquo; <span>' . esc_html__('404 Not Found', 'super-elem-pro') . '</span>';
        }
        echo '</nav>';
    }
}