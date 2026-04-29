<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class PodsField extends Tag
{
    public function get_name(): string
    {
        return 'sep-pods-field';
    }
    public function get_title(): string
    {
        return __('Pods Field', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_PODS;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }

    protected function register_controls(): void
    {
        $this->add_control('field_name', ['label' => __('Field Name', 'super-elem-pro'), 'type' => Controls_Manager::TEXT]);
        $this->add_control('pod_name', ['label' => __('Pod Name', 'super-elem-pro'), 'type' => Controls_Manager::TEXT]);
    }

    public function render(): void
    {
        if (!function_exists('pods')) {
            return;
        }
        $field = sanitize_key($this->get_settings('field_name') ?? '');
        if (!$field) {
            return;
        }
        echo esc_html(pods_field($field));
    }
}