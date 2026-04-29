<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class PostDate extends Tag
{
    public function get_name(): string
    {
        return 'sep-post-date';
    }
    public function get_title(): string
    {
        return __('Post Date', 'super-elem-pro');
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
        $this->add_control('date_format', [
            'label' => __('Date Format', 'super-elem-pro'),
            'type' => Controls_Manager::TEXT,
            'default' => get_option('date_format'),
        ]);
        $this->add_control('use_modified', [
            'label' => __('Use Modified Date', 'super-elem-pro'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
        ]);
    }

    public function render(): void
    {
        $settings = $this->get_settings();
        $format = $settings['date_format'] ?: get_option('date_format');
        $use_mod = $settings['use_modified'] ?? '';
        echo esc_html('yes' === $use_mod ? get_the_modified_date($format) : get_the_date($format));
    }
}