<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class LottieAnimation extends Widget_Base
{

    public function get_name()
    {
        return 'sep-lottie';
    }

    public function get_title()
    {
        return __('Lottie Animation', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-lottie';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    public function get_script_depends()
    {
        return ['lottie-player'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_lottie',
            [
                'label' => __('Lottie', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'lottie_json_url',
            [
                'label' => __('JSON URL', 'super-elem-pro'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://assets.lottiefiles.com/...',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $json_url = !empty($settings['lottie_json_url']['url']) ? $settings['lottie_json_url']['url'] : '';

        if (empty($json_url)) {
            return;
        }

        ?>
        <lottie-player src="<?php echo esc_url($json_url); ?>" background="transparent" speed="1"
            style="width: 100%; height: 300px;" <?php echo $settings['loop'] === 'yes' ? 'loop' : ''; ?>         <?php echo $settings['autoplay'] === 'yes' ? 'autoplay' : ''; ?>></lottie-player>
        <?php
    }
}