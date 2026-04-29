<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class CurrentDate extends Tag
{
    public function get_name(): string
    {
        return 'sep-current-date';
    }
    public function get_title(): string
    {
        return __('Current Date/Time', 'super-elem-pro');
    }
    public function get_group(): string
    {
        return DynamicTags::GROUP_SITE;
    }
    public function get_categories(): array
    {
        return [TagsModule::TEXT_CATEGORY];
    }
    protected function register_controls(): void
    {
        $this->add_control('date_format', ['label' => __('Format', 'super-elem-pro'), 'type' => Controls_Manager::TEXT, 'default' => 'F j, Y']);
    }
    public function render(): void
    {
        $format = $this->get_settings('date_format') ?: 'F j, Y';
        echo esc_html(wp_date($format));
    }
}