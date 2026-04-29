<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SuperElemPro\Modules\DynamicTags\DynamicTags;

if (!defined('ABSPATH')) {
    exit;
}

class PostTitle extends Tag
{
    public function get_name(): string
    {
        return 'sep-post-title';
    }
    public function get_title(): string
    {
        return __('Post Title', 'super-elem-pro');
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
        echo wp_kses_post(get_the_title());
    }
}