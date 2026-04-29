<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class UserMeta extends Tag
{
    public function get_name(): string
    {
        return 'sep-user-meta';
    }
    public function get_title(): string
    {
        return __('User Meta', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_USER;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }
    protected function register_controls(): void
    {
        $this->add_control('meta_key', ['label' => __('Meta Key', 'super-elem-pro'), 'type' => Controls_Manager::TEXT, 'placeholder' => 'display_name']);
    }
    public function render(): void
    {
        if (!is_user_logged_in()) {
            return;
        }
        $key = sanitize_key($this->get_settings('meta_key') ?? '');
        if (!$key) {
            return;
        }
        echo esc_html(get_user_meta(get_current_user_id(), $key, true) ?: wp_get_current_user()->$key ?? '');
    }
}