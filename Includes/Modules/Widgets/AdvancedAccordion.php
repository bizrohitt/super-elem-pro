<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class AdvancedAccordion extends Widget_Base
{

    public function get_name()
    {
        return 'sep-advanced-accordion';
    }

    public function get_title()
    {
        return __('Advanced Accordion', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_accordion',
            [
                'label' => __('Accordion', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'accordion_title',
            [
                'label' => __('Title', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Accordion Title', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'accordion_content',
            [
                'label' => __('Content', 'super-elem-pro'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Accordion content goes here.', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => __('Accordion Items', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'accordion_title' => __('Item #1', 'super-elem-pro'),
                        'accordion_content' => __('Content for item 1', 'super-elem-pro'),
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-accordion">
            <?php foreach ($settings['accordion_items'] as $index => $item): ?>
                <div class="sep-accordion-item">
                    <div class="sep-accordion-title">
                        <?php echo esc_html($item['accordion_title']); ?>
                        <span class="sep-accordion-icon">+</span>
                    </div>
                    <div class="sep-accordion-content">
                        <?php echo wp_kses_post($item['accordion_content']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('.sep-accordion-title').on('click', function () {
                    var $content = $(this).next('.sep-accordion-content');
                    var $icon = $(this).find('.sep-accordion-icon');

                    if ($content.is(':visible')) {
                        $content.slideUp();
                        $icon.text('+');
                    } else {
                        $('.sep-accordion-content').slideUp();
                        $('.sep-accordion-icon').text('+');
                        $content.slideDown();
                        $icon.text('−');
                    }
                });
            });
        </script>
        <?php
    }
}