<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class CountdownTimer extends Widget_Base
{

    public function get_name()
    {
        return 'sep-countdown-timer';
    }

    public function get_title()
    {
        return __('Countdown Timer', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-countdown';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    public function get_script_depends()
    {
        return ['sep-countdown'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_countdown',
            [
                'label' => __('Countdown', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'due_date',
            [
                'label' => __('Due Date', 'super-elem-pro'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-countdown" data-date="<?php echo esc_attr($settings['due_date']); ?>">
            <div class="sep-countdown-item">
                <span class="sep-countdown-number" data-days></span>
                <span class="sep-countdown-label"><?php esc_html_e('Days', 'super-elem-pro'); ?></span>
            </div>
            <div class="sep-countdown-item">
                <span class="sep-countdown-number" data-hours></span>
                <span class="sep-countdown-label"><?php esc_html_e('Hours', 'super-elem-pro'); ?></span>
            </div>
            <div class="sep-countdown-item">
                <span class="sep-countdown-number" data-minutes></span>
                <span class="sep-countdown-label"><?php esc_html_e('Minutes', 'super-elem-pro'); ?></span>
            </div>
            <div class="sep-countdown-item">
                <span class="sep-countdown-number" data-seconds></span>
                <span class="sep-countdown-label"><?php esc_html_e('Seconds', 'super-elem-pro'); ?></span>
            </div>
        </div>
        <?php
    }
}