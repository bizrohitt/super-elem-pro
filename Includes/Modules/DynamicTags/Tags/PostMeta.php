<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class PostMeta extends Tag
{
    public function get_name(): string
    {
        return 'sep-post-meta';
    }
    public function get_title(): string
    {
        return __('Post Custom Field', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_POST;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY, TagsModule::URL_CATEGORY, TagsModule::NUMBER_CATEGORY];
    }

    protected function register_controls(): void
    {
        $this->add_control('meta_key', [
            'label' => __('Meta Key', 'super-elem-pro'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => '_my_custom_field',
        ]);
    }

    public function render(): void
    {
        $settings = $this->get_settings();
        $key = sanitize_key($settings['meta_key'] ?? '');
        if (!$key) {
            return;
        }
        $value = get_post_meta(get_the_ID(), $key, true);
        echo wp_kses_post($value);
    }
}