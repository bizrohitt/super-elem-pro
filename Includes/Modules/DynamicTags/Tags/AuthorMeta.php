<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class AuthorMeta extends Tag
{
    public function get_name(): string
    {
        return 'sep-author-meta';
    }
    public function get_title(): string
    {
        return __('Author Meta', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_POST;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }
    protected function register_controls(): void
    {
        $this->add_control('meta_key', ['label' => __('Meta Key', 'super-elem-pro'), 'type' => Controls_Manager::TEXT, 'placeholder' => 'description']);
    }
    public function render(): void
    {
        $settings = $this->get_settings();
        $key = sanitize_key($settings['meta_key'] ?? '');
        if (!$key)
            return;
        $author_id = get_the_author_meta('ID');
        echo esc_html(get_the_author_meta($key, $author_id));
    }
}