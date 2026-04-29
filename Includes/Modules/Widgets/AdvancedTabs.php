<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class AdvancedTabs extends Widget_Base
{

    public function get_name()
    {
        return 'sep-advanced-tabs';
    }

    public function get_title()
    {
        return __('Advanced Tabs', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-tabs';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_tabs',
            [
                'label' => __('Tabs', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => __('Title', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Tab Title', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => __('Content', 'super-elem-pro'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Tab content goes here.', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __('Tabs Items', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => __('Tab #1', 'super-elem-pro'),
                        'tab_content' => __('Content for tab 1', 'super-elem-pro'),
                    ],
                    [
                        'tab_title' => __('Tab #2', 'super-elem-pro'),
                        'tab_content' => __('Content for tab 2', 'super-elem-pro'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-tabs">
            <div class="sep-tabs-nav">
                <?php foreach ($settings['tabs'] as $index => $tab): ?>
                    <div class="sep-tab-title <?php echo $index === 0 ? 'active' : ''; ?>"
                        data-tab="tab-<?php echo esc_attr($index); ?>">
                        <?php echo esc_html($tab['tab_title']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="sep-tabs-content">
                <?php foreach ($settings['tabs'] as $index => $tab): ?>
                    <div class="sep-tab-content <?php echo $index === 0 ? 'active' : ''; ?>"
                        id="tab-<?php echo esc_attr($index); ?>">
                        <?php echo wp_kses_post($tab['tab_content']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('.sep-tab-title').on('click', function () {
                    var tab = $(this).data('tab');
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    $('.sep-tab-content').removeClass('active');
                    $('#' + tab).addClass('active');
                });
            });
        </script>
        <?php
    }
}