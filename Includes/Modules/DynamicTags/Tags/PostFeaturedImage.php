<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class PostFeaturedImage extends Data_Tag
{
    public function get_name(): string
    {
        return 'sep-post-featured-image';
    }
    public function get_title(): string
    {
        return __('Featured Image', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_POST;
    }
    public function get_categories(): array
    {
        return [TagsModule::IMAGE_CATEGORY];
    }
    public function get_value(array $options = [])
    {
        $id = get_post_thumbnail_id();
        if (!$id) {
            return [];
        }
        return ['id' => $id, 'url' => wp_get_attachment_url($id)];
    }
}