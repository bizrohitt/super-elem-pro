<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class ImageHotspots extends Widget_Base
{

    public function get_name()
    {
        return 'sep-image-hotspots';
    }

    public function get_title()
    {
        return __('Image Hotspots', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-image-hotspot';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Choose Image', 'super-elem-pro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hotspots',
            [
                'label' => __('Hotspots', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'hotspot_x',
            [
                'label' => __('Horizontal Position (%)', 'super-elem-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_y',
            [
                'label' => __('Vertical Position (%)', 'super-elem-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_label',
            [
                'label' => __('Label', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );

        $repeater->add_control(
            'hotspot_content',
            [
                'label' => __('Content', 'super-elem-pro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Hotspot content', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'hotspots',
            [
                'label' => __('Hotspots', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ hotspot_label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-image-hotspots">
            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
            <?php foreach ($settings['hotspots'] as $index => $hotspot): ?>
                <div class="sep-hotspot"
                    style="left: <?php echo esc_attr($hotspot['hotspot_x']['size']); ?>%; top: <?php echo esc_attr($hotspot['hotspot_y']['size']); ?>%;">
                    <span class="sep-hotspot-marker"><?php echo esc_html($hotspot['hotspot_label']); ?></span>
                    <div class="sep-hotspot-tooltip">
                        <?php echo wp_kses_post($hotspot['hotspot_content']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}