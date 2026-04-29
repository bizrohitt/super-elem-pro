<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class PricingTable extends Widget_Base
{

    public function get_name()
    {
        return 'sep-pricing-table';
    }

    public function get_title()
    {
        return __('Pricing Table', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-price-table';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_pricing',
            [
                'label' => __('Pricing', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Basic Plan', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => __('Price', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '$29',
            ]
        );

        $this->add_control(
            'currency',
            [
                'label' => __('Currency', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );

        $this->add_control(
            'period',
            [
                'label' => __('Period', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('per month', 'super-elem-pro'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-pricing-table">
            <h3 class="sep-pricing-title"><?php echo esc_html($settings['title']); ?></h3>
            <div class="sep-pricing-price">
                <span class="sep-currency"><?php echo esc_html($settings['currency']); ?></span>
                <span class="sep-amount"><?php echo esc_html($settings['price']); ?></span>
                <span class="sep-period"><?php echo esc_html($settings['period']); ?></span>
            </div>
        </div>
        <?php
    }
}