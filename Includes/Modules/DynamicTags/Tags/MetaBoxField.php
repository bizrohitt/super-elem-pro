<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class MetaBoxField extends Tag
{
    public function get_name(): string
    {
        return 'sep-metabox-field';
    }
    public function get_title(): string
    {
        return __('Meta Box Field', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_METABOX;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }

    protected function register_controls(): void
    {
        $this->add_control('field_id', ['label' => __('Field ID', 'super-elem-pro'), 'type' => Controls_Manager::TEXT]);
    }

    public function render(): void
    {
        if (!function_exists('rwmb_meta')) {
            return;
        }
        $field = sanitize_key($this->get_settings('field_id') ?? '');
        if (!$field) {
            return;
        }
        echo wp_kses_post(rwmb_meta($field));
    }
}