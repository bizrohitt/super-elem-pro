<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class PostExcerpt extends Tag
{
    public function get_name(): string
    {
        return 'sep-post-excerpt';
    }
    public function get_title(): string
    {
        return __('Post Excerpt', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_POST;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }
    public function render(): void
    {
        echo wp_kses_post(get_the_excerpt());
    }
}