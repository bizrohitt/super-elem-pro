<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class SocialFeed extends Widget_Base
{

    public function get_name()
    {
        return 'sep-social-feed';
    }

    public function get_title()
    {
        return __('Social Feed', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-social-icons';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_feed',
            [
                'label' => __('Feed Settings', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'platform',
            [
                'label' => __('Platform', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'instagram' => __('Instagram', 'super-elem-pro'),
                    'twitter' => __('Twitter/X', 'super-elem-pro'),
                    'facebook' => __('Facebook', 'super-elem-pro'),
                ],
                'default' => 'instagram',
            ]
        );

        $this->add_control(
            'access_token',
            [
                'label' => __('Access Token', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Enter your API access token', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'items_count',
            [
                'label' => __('Number of Items', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-social-feed sep-social-<?php echo esc_attr($settings['platform']); ?>">
            <p><?php esc_html_e('Please configure your API access token in the widget settings.', 'super-elem-pro'); ?></p>
        </div>
        <?php
    }
}