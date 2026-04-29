<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class SiteTagline extends Tag
{
    public function get_name(): string
    {
        return 'sep-site-tagline';
    }
    public function get_title(): string
    {
        return __('Site Tagline', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_SITE;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }
    public function render(): void
    {
        echo esc_html(get_bloginfo('description'));
    }
}