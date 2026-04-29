<?php
namespace SuperElemPro\Modules\DynamicTags\Tags;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;
use SuperElemPro\Modules\DynamicTags\DynamicTags;
if (!defined('ABSPATH')) {
    exit;
}

class RequestParam extends Tag
{
    public function get_name(): string
    {
        return 'sep-request-param';
    }
    public function get_title(): string
    {
        return __('URL Parameter', 'super-elem-pro');
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
        $this->add_control('param_name', ['label' => __('Parameter Name', 'super-elem-pro'), 'type' => Controls_Manager::TEXT, 'placeholder' => 'ref']);
    }
    public function render(): void
    {
        $param = sanitize_key($this->get_settings('param_name') ?? '');
        if (!$param) {
            return;
        }
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        echo esc_html(sanitize_text_field($_GET[$param] ?? ''));
    }
}