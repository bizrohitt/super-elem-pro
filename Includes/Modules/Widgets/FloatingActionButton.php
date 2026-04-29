<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class FloatingActionButton extends Widget_Base
{

    public function get_name()
    {
        return 'sep-floating-action-button';
    }

    public function get_title()
    {
        return __('Floating Action Button', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-button';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Button', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Text', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '+',
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __('Link', 'super-elem-pro'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __('Position', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bottom-right' => __('Bottom Right', 'super-elem-pro'),
                    'bottom-left' => __('Bottom Left', 'super-elem-pro'),
                    'top-right' => __('Top Right', 'super-elem-pro'),
                    'top-left' => __('Top Left', 'super-elem-pro'),
                ],
                'default' => 'bottom-right',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $link = !empty($settings['button_link']['url']) ? $settings['button_link']['url'] : '#';
        ?>
        <a href="<?php echo esc_url($link); ?>" class="sep-fab sep-fab-<?php echo esc_attr($settings['position']); ?>">
            <?php echo esc_html($settings['button_text']); ?>
        </a>
        <?php
    }
}