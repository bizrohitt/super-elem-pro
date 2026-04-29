<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Timeline extends Widget_Base
{

    public function get_name()
    {
        return 'sep-timeline';
    }

    public function get_title()
    {
        return __('Timeline', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-time-line';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_timeline',
            [
                'label' => __('Timeline Items', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'date',
            [
                'label' => __('Date', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '2024',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Event Title', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'super-elem-pro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Event description goes here.', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => __('Timeline Items', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'date' => '2024',
                        'title' => __('First Event', 'super-elem-pro'),
                        'description' => __('Description here', 'super-elem-pro'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-timeline">
            <?php foreach ($settings['timeline_items'] as $index => $item): ?>
                <div class="sep-timeline-item">
                    <div class="sep-timeline-marker"></div>
                    <div class="sep-timeline-content">
                        <div class="sep-timeline-date"><?php echo esc_html($item['date']); ?></div>
                        <h3 class="sep-timeline-title"><?php echo esc_html($item['title']); ?></h3>
                        <p class="sep-timeline-desc"><?php echo esc_html($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}