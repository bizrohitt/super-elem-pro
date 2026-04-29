<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class SiteUrl extends Tag
{
    public function get_name(): string
    {
        return 'sep-site-url';
    }
    public function get_title(): string
    {
        return __('Site URL', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_SITE;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY, TagsModule::URL_CATEGORY];
    }
    public function render(): void
    {
        echo esc_url(home_url('/'));
    }
}