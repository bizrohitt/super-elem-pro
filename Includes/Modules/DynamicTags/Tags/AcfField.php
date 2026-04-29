<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class AcfField extends Tag
{
    public function get_name(): string
    {
        return 'sep-acf-field';
    }
    public function get_title(): string
    {
        return __('ACF Field', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_ACF;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY, TagsModule::URL_CATEGORY, TagsModule::NUMBER_CATEGORY];
    }

    protected function register_controls(): void
    {
        $this->add_control('field_key', ['label' => __('Field Name', 'super-elem-pro'), 'type' => Controls_Manager::TEXT, 'placeholder' => 'my_field_name']);
    }

    public function render(): void
    {
        if (!function_exists('get_field')) {
            return;
        }
        $key = sanitize_key($this->get_settings('field_key') ?? '');
        if (!$key) {
            return;
        }
        $value = get_field($key);
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        echo wp_kses_post($value);
    }
}